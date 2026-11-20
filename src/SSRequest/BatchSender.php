<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSRequest;

use GuzzleHttp\Promise\EachPromise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Send several Bundles at once, with a fixed number in flight and one shared backoff.
 *
 * The shared part is the whole point: when the server answers 429, a worker that retries alone makes the
 * other workers arrive during the same closed window. Here a 429 moves one deadline for everybody, and a
 * request that is about to start waits for that deadline instead of hammering the API.
 *
 * Guzzle is already a dependency, so its promise pool provides the concurrency — no new package, no forked
 * processes, and the result order matches the input order so a caller can zip results back to Bundles.
 */
final class BatchSender
{
    /** @var float unix time (with microseconds) before which no request may start */
    private float $blockedUntil = 0.0;

    /**
     * @param int $concurrency requests in flight at once; >1 is the entire feature
     * @param int $maxRetries attempts after a 429/503 per Bundle, honouring the shared deadline
     */
    public function __construct(
        private readonly SSRequest $request,
        private readonly int $concurrency = 4,
        private readonly ?Journal $journal = null,
        private readonly int $maxRetries = 1,
    ) {
        if ($this->concurrency < 1) {
            throw new \InvalidArgumentException('concurrency minimal 1');
        }
    }

    /**
     * @param array<int,array<string,mixed>> $bundles
     * @param string $url resource path, e.g. '' for the base URL
     * @return array<int,array{status:int,ok:bool,error:?string,skipped:bool}>
     */
    public function send(array $bundles, string $url = ''): array
    {
        /** @var array<int,array{status:int,ok:bool,error:?string,skipped:bool}> $results */
        $results = [];
        $pending = [];

        foreach ($bundles as $index => $bundle) {
            $fingerprint = Journal::fingerprint($bundle);
            if ($this->journal !== null && $this->journal->alreadySent($fingerprint)) {
                // A resumed run must not re-send what the server already accepted.
                $results[$index] = ['status' => 0, 'ok' => true, 'error' => null, 'skipped' => true];
                continue;
            }
            $pending[$index] = ['bundle' => $bundle, 'fingerprint' => $fingerprint, 'attempt' => 0];
        }

        if ($pending === []) {
            ksort($results);

            return $results;
        }

        // A generator, not an array: EachPromise pulls the next item only when a slot is free, so the request
        // is created at the moment it may start. Building all promises up front would fire everything at once
        // and make the concurrency limit decorative.
        $items = (function () use (&$pending, $url) {
            foreach ($pending as $index => $item) {
                yield $index => $this->dispatch($index, $item, $url);
            }
        })();

        $each = new EachPromise($items, [
            'concurrency' => $this->concurrency,
            'fulfilled' => function (array $outcome, $index) use (&$results) {
                $results[$index] = $outcome;
            },
            'rejected' => function ($reason, $index) use (&$results) {
                $results[$index] = [
                    'status' => 0,
                    'ok' => false,
                    'error' => $reason instanceof \Throwable ? $reason->getMessage() : (string) $reason,
                    'skipped' => false,
                ];
            },
        ]);
        $each->promise()->wait();

        ksort($results);

        return $results;
    }

    /** @param array{bundle:array,fingerprint:string,attempt:int} $item */
    private function dispatch(int $index, array $item, string $url): PromiseInterface
    {
        $this->waitForGate();

        return $this->request->postBundleAsync($item['bundle'], $url)->then(
            function (ResponseInterface $response) use ($index, $item, $url) {
                $status = $response->getStatusCode();
                if (($status === 429 || $status >= 500) && $item['attempt'] < $this->maxRetries) {
                    $this->extendGate($response->getHeaderLine('Retry-After'), $item['attempt'] + 1);
                    $item['attempt']++;
                    // Re-entering through dispatch is what makes the retry obey the shared deadline set above.
                    return $this->dispatch($index, $item, $url);
                }
                if ($this->journal !== null) {
                    $this->journal->record($item['fingerprint'], $status, $status >= 400 ? 'HTTP ' . $status : null);
                }

                return ['status' => $status, 'ok' => $status >= 200 && $status < 300, 'error' => null, 'skipped' => false];
            },
            function ($reason) use ($item) {
                if ($this->journal !== null) {
                    $this->journal->record($item['fingerprint'], 0, $reason instanceof \Throwable ? $reason->getMessage() : (string) $reason);
                }
                throw $reason;
            },
        );
    }

    private function waitForGate(): void
    {
        $now = microtime(true);
        if ($this->blockedUntil > $now) {
            usleep((int) (($this->blockedUntil - $now) * 1_000_000));
        }
    }

    private function extendGate(string $retryAfter, int $attempt): void
    {
        $seconds = is_numeric($retryAfter) ? (float) $retryAfter : (float) (2 ** $attempt);
        $this->blockedUntil = max($this->blockedUntil, microtime(true) + $seconds);
    }
}

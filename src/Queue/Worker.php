<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Queue worker — processes pending jobs using an HTTP client.
 *
 * Handles OAuth2 token lifecycle, retry backoff, DLQ routing, rate limiting.
 * Framework-agnostic: standalone PHP or Laravel.
 *
 * Usage (standalone):
 *   $pdo  = new PDO('sqlite:' . $dbPath);
 *   $queue = new SqliteQueue($pdo);
 *   $worker = new Worker($queue, $oauth2Config, rateLimiter: new RateLimiter(300));
 *   $worker->process(50);
 *
 * Usage (Laravel):
 *   See Console/Commands/ProcessSatusehatQueue.php
 */
class Worker
{
    private QueueInterface $queue;
    private array $oauth2Config;
    private ?object $tokenCache = null;
    private int $processed = 0;
    private int $succeeded = 0;
    private int $failed = 0;
    private int $dlq = 0;
    private RateLimiter $rateLimiter;

    /**
     * @param QueueInterface $queue
     * @param array $oauth2Config ['client_id', 'client_secret', 'base_url', 'fhir_url']
     * @param RateLimiter|null $rateLimiter Per-process rate limiter (default: 300 RPM)
     */
    public function __construct(
        QueueInterface $queue,
        array $oauth2Config,
        ?RateLimiter $rateLimiter = null,
    ) {
        $this->queue = $queue;
        $this->oauth2Config = $oauth2Config;
        $this->rateLimiter = $rateLimiter ?? new RateLimiter(300);
    }

    // ── Main loop ─────────────────────────────────────────────────

    /**
     * Process up to $limit pending jobs.
     *
     * @param int $limit
     * @return array{processed: int, succeeded: int, failed: int, dlq: int}
     */
    public function process(int $limit = 50): array
    {
        $this->processed = 0;
        $this->succeeded = 0;
        $this->failed = 0;
        $this->dlq = 0;

        for ($i = 0; $i < $limit; $i++) {
            $job = $this->queue->dequeue();
            if ($job === null) {
                break; // Queue empty
            }

            $result = $this->handleJob($job);
            $this->processed++;

            if ($result->isSuccess()) $this->succeeded++;
            elseif ($result->isDlq()) $this->dlq++;
            else $this->failed++;
        }

        return [
            'processed' => $this->processed,
            'succeeded' => $this->succeeded,
            'failed'    => $this->failed,
            'dlq'       => $this->dlq,
        ];
    }

    /**
     * Handle a single job end-to-end.
     *
     * @param array $job  Job record from queue
     * @return QueueResult
     */
    public function handleJob(array $job): QueueResult
    {
        try {
            $this->rateLimiter->wait();
            $response = $this->send($job);
            $httpCode = $response['http_code'] ?? 0;
            $body = $response['body'] ?? '';
            $location = $response['location'] ?? '';

            // Classify response
            $parsed = is_string($body) && $body !== ''
                ? json_decode($body, true) ?: $body
                : $body;
            $classified = ErrorClassifier::classify($httpCode, $parsed);

            // 2xx → success
            if ($classified['category'] === ErrorClassifier::CAT_SUCCESS) {
                $this->queue->markSuccess($job['id'], $response, $location);
                return QueueResult::success($job['id'], $response, $job['attempts'] + 1);
            }

            // 401 → token likely expired — invalidate + let markFailed requeue
            if ($classified['category'] === ErrorClassifier::CAT_UNAUTHORIZED) {
                $this->tokenCache = null;
            }

            // 429 with Retry-After → honor it before requeue
            if ($classified['category'] === ErrorClassifier::CAT_RATE_LIMITED) {
                $retryAfter = RateLimiter::parseRetryAfter($response['retry_after'] ?? null);
                if ($retryAfter !== null && $retryAfter > 0) {
                    $this->queue->markFailed($job['id'], $classified['detail'], $response);
                    // Override scheduled_at with Retry-After value
                    $pdo = $this->queue->pdo();
                    $stmt = $pdo->prepare(
                        "UPDATE satusehat_queue
                         SET scheduled_at = datetime('now', :sec || ' seconds'),
                             updated_at = datetime('now')
                         WHERE id = :id AND status = 'pending'"
                    );
                    $stmt->execute([':sec' => (int) $retryAfter, ':id' => $job['id']]);
                    return QueueResult::failed($job['id'], $classified['detail'], $response, $job['attempts'] + 1);
                }
            }

            // Non-retryable → DLQ
            if (!$classified['retryable']) {
                $this->queue->markDlq($job['id'], $classified['detail'], $response);
                return QueueResult::dlq($job['id'], $classified['detail'], $response, $job['attempts'] + 1);
            }

            // Retryable → failed (SqliteQueue handles backoff scheduling)
            $this->queue->markFailed($job['id'], $classified['detail'], $response);
            return QueueResult::failed($job['id'], $classified['detail'], $response, $job['attempts'] + 1);

        } catch (\Throwable $e) {
            $this->queue->markFailed($job['id'], $e->getMessage(), []);
            return QueueResult::failed($job['id'], $e->getMessage(), [], $job['attempts'] + 1);
        }
    }

    // ── HTTP ──────────────────────────────────────────────────────

    /**
     * Send a single job's HTTP request with OAuth2 token.
     *
     * @param array $job
     * @return array{body: string, http_code: int, location: string, response: mixed}
     */
    private function send(array $job): array
    {
        $token = $this->getToken();
        $method = strtoupper($job['method'] ?? 'POST');
        $url = rtrim($this->oauth2Config['fhir_url'] ?? '', '/') . '/' . ltrim($job['url'] ?? '', '/');
        $payload = $job['bundle_payload'] ?? null;

        $headers = [
            'Authorization' => "Bearer {$token}",
            'Content-Type'  => 'application/fhir+json',
            'Accept'       => 'application/fhir+json',
        ];

        // Idempotency key via If-None-Exist (POST) or If-Match (PUT/PATCH)
        if ($method === 'POST' && !empty($job['idempotency_key'])) {
            $headers['If-None-Exist'] = "uuid={$job['idempotency_key']}";
        }
        if (in_array($method, ['PUT', 'PATCH'], true) && !empty($job['etag'])) {
            $headers['If-Match'] = $job['etag'];
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $this->buildHeaders($headers),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        if ($payload !== null && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        curl_setopt($ch, CURLOPT_HEADER, true);

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("cURL error: {$error}");
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerStr = is_string($raw) ? substr($raw, 0, $headerSize) : '';
        $body = is_string($raw) ? substr($raw, $headerSize) : '';

        $retryAfter = null;
        foreach (explode("\r\n", $headerStr) as $line) {
            if (str_starts_with(strtolower($line), 'retry-after:')) {
                $retryAfter = trim(substr($line, 11));
                break;
            }
        }

        $location = $this->extractLocation($body, $httpCode);

        return [
            'body'         => $body,
            'http_code'    => $httpCode,
            'location'     => $location,
            'retry_after'  => $retryAfter,
            'response'     => $body !== '' ? json_decode($body, true) : null,
        ];
    }

    // ── Token ─────────────────────────────────────────────────────

    private function getToken(): string
    {
        // Return cached token if still valid (allow 60s buffer)
        if ($this->tokenCache !== null
            && isset($this->tokenCache->expires_at)
            && time() < $this->tokenCache->expires_at - 60
        ) {
            return $this->tokenCache->access_token;
        }

        $this->tokenCache = $this->fetchToken();
        return $this->tokenCache->access_token;
    }

    private function fetchToken(): object
    {
        $tokenUrl = rtrim($this->oauth2Config['base_url'] ?? '', '/')
            . '/oauth2/v1/accesstoken';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL          => $tokenUrl,
            CURLOPT_POST         => true,
            CURLOPT_POSTFIELDS   => http_build_query([
                'grant_type'    => 'client_credentials',
                'client_id'     => $this->oauth2Config['client_id'],
                'client_secret' => $this->oauth2Config['client_secret'],
            ]),
            CURLOPT_HTTPHEADER  => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT    => 15,
        ]);

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$body) {
            throw new \RuntimeException(
                "OAuth2 token fetch failed (HTTP {$httpCode}): {$body}"
            );
        }

        $data = json_decode($body, false);
        if (!$data || empty($data->access_token)) {
            throw new \RuntimeException("Invalid OAuth2 token response: {$body}");
        }

        $data->expires_at = time() + (int) ($data->expires_in ?? 3600);
        return $data;
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function buildHeaders(array $headers): array
    {
        $out = [];
        foreach ($headers as $k => $v) {
            $out[] = "{$k}: {$v}";
        }
        return $out;
    }

    private function extractError(string $body): string
    {
        if (!$body) return 'Empty response';
        $data = json_decode($body, true);
        if (isset($data['issue'][0]['diagnostics'])) {
            return $data['issue'][0]['diagnostics'];
        }
        if (isset($data['message'])) {
            return $data['message'];
        }
        return substr($body, 0, 200);
    }

    private function extractLocation(string $body, int $httpCode): string
    {
        // Location header from POST → 201
        // Or from response body (SATUSEHAT sometimes embeds it)
        $data = json_decode($body, true);
        if (isset($data['fullUrl'])) {
            return $data['fullUrl'];
        }
        if (isset($data['resource']['id'])) {
            return ($data['resource']['resourceType'] ?? '') . '/' . $data['resource']['id'];
        }
        return '';
    }
}

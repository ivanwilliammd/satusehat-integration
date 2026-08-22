<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Queue worker — processes pending jobs using an HTTP client.
 *
 * Handles OAuth2 token lifecycle, retry backoff, DLQ routing.
 * Framework-agnostic: works with or without Laravel.
 *
 * Usage (standalone):
 *   $pdo = new PDO('sqlite:' . $dbPath);
 *   $queue = new SqliteQueue($pdo);
 *   $worker = new Worker($queue, $oauth2Config);
 *   $worker->process(50);
 *
 * Usage (Laravel — via artisan command):
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

    /**
     * @param QueueInterface $queue
     * @param array $oauth2Config  ['client_id', 'client_secret', 'base_url', 'fhir_url']
     */
    public function __construct(QueueInterface $queue, array $oauth2Config)
    {
        $this->queue = $queue;
        $this->oauth2Config = $oauth2Config;
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
            $response = $this->send($job);
            $httpCode = $response['http_code'] ?? 0;
            $body = $response['body'] ?? '';
            $location = $response['location'] ?? '';

            // 2xx → success
            if ($httpCode >= 200 && $httpCode < 300) {
                $this->queue->markSuccess($job['id'], $response, $location);
                return QueueResult::success(
                    $job['id'],
                    $response,
                    $job['attempts'] + 1
                );
            }

            // Non-retriable 4xx → DLQ
            if ($httpCode >= 400 && $httpCode < 500 && !in_array($httpCode, [408, 429], true)) {
                $error = $this->extractError($body);
                $this->queue->markDlq($job['id'], $error, $response);
                return QueueResult::dlq($job['id'], $error, $response, $job['attempts'] + 1);
            }

            // Retriable: 408, 429, 5xx → failed (will be requeued by markFailed)
            $error = $this->extractError($body);
            $this->queue->markFailed($job['id'], $error, $response);
            return QueueResult::failed(
                $job['id'],
                $error,
                $response,
                $job['attempts'] + 1
            );

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

        $body = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("cURL error: {$error}");
        }

        $location = $this->extractLocation($body, $httpCode);

        return [
            'body'      => is_string($body) ? $body : '',
            'http_code' => $httpCode,
            'location'  => $location,
            'response'  => $body ? json_decode($body, true) : null,
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

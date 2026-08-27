<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Classifies SATUSEHAT FHIR API HTTP responses into actionable categories.
 *
 * Maps HTTP status codes to:
 *   - category: human-readable error bucket
 *   - retryable: whether the operation should be retried
 *   - status: the queue status this maps to
 */
final class ErrorClassifier
{
    public const CAT_SUCCESS          = 'success';
    public const CAT_INVALID_REQUEST  = 'invalid_request';
    public const CAT_UNAUTHORIZED     = 'unauthorized';
    public const CAT_FORBIDDEN        = 'forbidden';
    public const CAT_NOT_FOUND        = 'not_found';
    public const CAT_CONFLICT         = 'conflict';
    public const CAT_VALIDATION_ERROR = 'validation_error';
    public const CAT_RATE_LIMITED     = 'rate_limited';
    public const CAT_SERVER_ERROR     = 'server_error';
    public const CAT_NETWORK_ERROR    = 'network_error';
    public const CAT_UNEXPECTED       = 'unexpected';

    /**
     * Classify an HTTP response.
     *
     * @param int               $httpCode
     * @param string|array|null $responseBody  Parsed JSON or raw string
     * @return array{category: string, retryable: bool, status: string, detail: string}
     */
    public static function classify(int $httpCode, mixed $responseBody = null): array
    {
        // 2xx — always success
        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'category'  => self::CAT_SUCCESS,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_SUCCESS,
                'detail'   => "HTTP {$httpCode}",
            ];
        }

        // 4xx ranges
        if ($httpCode === 400) {
            return [
                'category'  => self::CAT_INVALID_REQUEST,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractDetail($responseBody) ?: "Bad request (HTTP 400)",
            ];
        }

        if ($httpCode === 401) {
            return [
                'category'  => self::CAT_UNAUTHORIZED,
                'retryable' => true, // token refresh
                'status'    => QueueInterface::STATUS_PENDING,
                'detail'   => self::extractDetail($responseBody) ?: "Unauthorized (HTTP 401)",
            ];
        }

        if ($httpCode === 403) {
            return [
                'category'  => self::CAT_FORBIDDEN,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractDetail($responseBody) ?: "Forbidden (HTTP 403)",
            ];
        }

        if ($httpCode === 404) {
            return [
                'category'  => self::CAT_NOT_FOUND,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractDetail($responseBody) ?: "Resource not found (HTTP 404)",
            ];
        }

        if ($httpCode === 408) {
            return [
                'category'  => self::CAT_NETWORK_ERROR,
                'retryable' => true,
                'status'    => QueueInterface::STATUS_PENDING,
                'detail'   => "Request timeout (HTTP 408)",
            ];
        }

        if ($httpCode === 409) {
            return [
                'category'  => self::CAT_CONFLICT,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractDetail($responseBody) ?: "Conflict (HTTP 409)",
            ];
        }

        if ($httpCode === 412) {
            return [
                'category'  => self::CAT_CONFLICT,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => "Precondition failed (HTTP 412) — resource changed",
            ];
        }

        if ($httpCode === 422) {
            return [
                'category'  => self::CAT_VALIDATION_ERROR,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractFhirIssues($responseBody)
                    ?: self::extractDetail($responseBody)
                    ?: "Validation error (HTTP 422)",
            ];
        }

        if ($httpCode === 429) {
            return [
                'category'  => self::CAT_RATE_LIMITED,
                'retryable' => true,
                'status'    => QueueInterface::STATUS_PENDING,
                'detail'   => "Rate limited (HTTP 429)",
            ];
        }

        // 5xx — server errors
        if ($httpCode >= 500 && $httpCode < 600) {
            return [
                'category'  => self::CAT_SERVER_ERROR,
                'retryable' => true,
                'status'    => QueueInterface::STATUS_PENDING,
                'detail'   => "Server error (HTTP {$httpCode})",
            ];
        }

        // Other 4xx
        if ($httpCode >= 400 && $httpCode < 500) {
            return [
                'category'  => self::CAT_INVALID_REQUEST,
                'retryable' => false,
                'status'    => QueueInterface::STATUS_DLQ,
                'detail'   => self::extractDetail($responseBody) ?: "Client error (HTTP {$httpCode})",
            ];
        }

        // Fallback
        return [
            'category'  => self::CAT_UNEXPECTED,
            'retryable' => false,
            'status'    => QueueInterface::STATUS_DLQ,
            'detail'   => "Unexpected HTTP {$httpCode}",
        ];
    }

    private static function extractDetail(mixed $body): ?string
    {
        if (is_array($body)) {
            if (!empty($body['issue'][0]['diagnostics'])) {
                return substr((string) $body['issue'][0]['diagnostics'], 0, 200);
            }
            if (!empty($body['message'])) {
                return substr((string) $body['message'], 0, 200);
            }
            if (!empty($body['error_description'])) {
                return substr((string) $body['error_description'], 0, 200);
            }
        }
        if (is_string($body) && $body !== '') {
            $data = @json_decode($body, true);
            if (is_array($data)) {
                return self::extractDetail($data);
            }
            return substr($body, 0, 200);
        }
        return null;
    }

    /**
     * Extract FHIR OperationOutcome issues as a concatenated string.
     */
    private static function extractFhirIssues(mixed $body): ?string
    {
        if (!is_array($body)) {
            return null;
        }
        $issues = $body['issue'] ?? [];
        if (empty($issues)) {
            return null;
        }
        $lines = [];
        foreach (array_slice($issues, 0, 5) as $issue) {
            $msg = $issue['diagnostics'] ?? $issue['location'] ?? 'Unknown';
            $sev = strtoupper($issue['severity'] ?? 'ERROR');
            $lines[] = "[{$sev}] " . substr($msg, 0, 100);
        }
        return implode(' | ', $lines);
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Framework-agnostic contract for the SATUSEHAT queue.
 *
 * Implementations:
 *  - SqliteQueue       — standalone PDO, no framework required
 *  - SatusehatQueue    — Laravel Eloquent adapter (extends SqliteQueue)
 *
 * Status flow: pending → processing → success | failed (retry) | dlq
 */
interface QueueInterface
{
    // ── Status constants ────────────────────────────────────────────
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS    = 'success';
    public const STATUS_FAILED     = 'failed';
    public const STATUS_DLQ        = 'dlq';

    public const DEFAULT_MAX_ATTEMPTS = 5;

    // ── Enqueue ────────────────────────────────────────────────────

    /**
     * Enqueue a FHIR Bundle transaction/batch payload.
     *
     * @param array  $bundlePayload  Full Bundle JSON array
     * @param string $bundleType     'transaction' | 'batch'
     * @param string $userId         Originating user/system identifier
     * @param int    $maxAttempts
     * @param array  $metadata       Extra context
     * @return array Job record (assoc array)
     */
    public function enqueueBundle(
        array  $bundlePayload,
        string $bundleType = 'transaction',
        string $userId = 'system',
        int    $maxAttempts = self::DEFAULT_MAX_ATTEMPTS,
        array  $metadata = [],
    ): array;

    /**
     * Enqueue a single FHIR resource operation.
     *
     * @param string $method     HTTP method: POST | PUT | PATCH | DELETE
     * @param string $resourceType  e.g. "Patient"
     * @param string $url        e.g. "Patient" or "Patient/123"
     * @param array|null $payload
     * @param string|null $etag
     * @param string|null $idempotencyKey  UUID for If-None-Exist
     * @param string $userId
     * @param int $maxAttempts
     * @param array $metadata
     * @return array Job record
     */
    public function enqueue(
        string  $method,
        string  $resourceType,
        string  $url,
        ?array  $payload = null,
        ?string $etag = null,
        ?string $idempotencyKey = null,
        string  $userId = 'system',
        int     $maxAttempts = self::DEFAULT_MAX_ATTEMPTS,
        array   $metadata = [],
    ): array;

    // ── Dequeue ───────────────────────────────────────────────────

    /**
     * Atomically claim one pending job and mark it processing.
     *
     * @return array|null  Job record or null if queue empty
     */
    public function dequeue(): ?array;

    // ── Update ─────────────────────────────────────────────────────

    public function markSuccess(int $id, array $response = [], ?string $location = ''): bool;
    public function markFailed(int $id, string $error, array $response = []): bool;
    public function markDlq(int $id, string $reason, array $response = []): bool;
    public function reset(int $id): bool;

    // ── Status helpers ──────────────────────────────────────────────

    /**
     * @param int $id
     * @return array|null
     */
    public function get(int $id): ?array;

    /**
     * @return int Count of pending jobs ready to process
     */
    public function pendingCount(): int;

    /**
     * @return int Count of DLQ jobs
     */
    public function dlqCount(): int;

    /**
     * @return array{pending: int, processing: int, success: int, failed: int, dlq: int}
     */
    public function stats(): array;

    /**
     * @param int $limit
     * @return array[]
     */
    public function pendingJobs(int $limit = 20): array;

    /**
     * @param int $limit
     * @return array[]
     */
    public function dlqJobs(int $limit = 20): array;

    // ── Admin ───────────────────────────────────────────────────────

    /**
     * Reset all failed + dlq jobs back to pending.
     * @return int Number of jobs reset
     */
    public function resetAll(): int;

    /**
     * Purge all success jobs older than $days.
     * @param int $days
     * @return int Number of jobs purged
     */
    public function purgeSuccess(int $days = 7): int;
}

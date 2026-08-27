<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

use PDO;
use PDOException;

/**
 * Standalone PDO queue — no framework dependencies.
 *
 * Works with any PDO connection (SQLite, MySQL, PostgreSQL).
 * Schema is identical to SatusehatQueue Eloquent model.
 *
 * Usage (standalone):
 *   $pdo = new PDO('sqlite:' . __DIR__ . '/../../queue.db');
 *   $queue = new SqliteQueue($pdo);
 *
 * Usage (Laravel):
 *   See SatusehatQueue — extends this class and adds Eloquent events.
 */
class SqliteQueue implements QueueInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->ensureTable();
    }

    // ── Table ─────────────────────────────────────────────────────

    private function ensureTable(): void
    {
        $sql = <<<'SQL'
        CREATE TABLE IF NOT EXISTS satusehat_queue (
            id                  INTEGER PRIMARY KEY AUTOINCREMENT,
            uuid                TEXT    UNIQUE NOT NULL,
            bundle_type         TEXT    DEFAULT 'single',
            bundle_payload      TEXT,
            resource_type       TEXT,
            method              TEXT    NOT NULL DEFAULT 'POST',
            url                 TEXT,
            status              TEXT    NOT NULL DEFAULT 'pending',
            attempts            INTEGER NOT NULL DEFAULT 0,
            max_attempts        INTEGER NOT NULL DEFAULT 5,
            last_error          TEXT,
            last_response       TEXT,
            etag                TEXT,
            idempotency_key     TEXT,
            scheduled_at        TEXT,
            completed_at        TEXT,
            dlq_reason         TEXT,
            user_id             TEXT,
            metadata            TEXT,
            created_at          TEXT    NOT NULL DEFAULT (datetime('now')),
            updated_at          TEXT    NOT NULL DEFAULT (datetime('now'))
        );
        CREATE INDEX IF NOT EXISTS idx_queue_status ON satusehat_queue(status);
        CREATE INDEX IF NOT EXISTS idx_queue_scheduled ON satusehat_queue(scheduled_at);
        CREATE INDEX IF NOT EXISTS idx_queue_uuid ON satusehat_queue(uuid);
        SQL;
        $this->pdo->exec($sql);
    }

    // ── Enqueue ────────────────────────────────────────────────────

    public function enqueueBundle(
        array  $bundlePayload,
        string $bundleType = 'transaction',
        string $userId = 'system',
        int    $maxAttempts = self::DEFAULT_MAX_ATTEMPTS,
        array  $metadata = [],
    ): array {
        $uuid = $this->generateUuid();
        $stmt = $this->pdo->prepare(
            "INSERT INTO satusehat_queue
               (uuid, bundle_type, bundle_payload, resource_type, method, url,
                status, attempts, max_attempts, user_id, metadata, created_at, updated_at)
             VALUES
               (:uuid, :bundle_type, :bundle_payload, :resource_type, :method, :url,
                :status, 0, :max_attempts, :user_id, :metadata, datetime('now'), datetime('now'))"
        );
        $stmt->execute([
            ':uuid'          => $uuid,
            ':bundle_type'   => $bundleType,
            ':bundle_payload' => json_encode($bundlePayload),
            ':resource_type' => 'Bundle',
            ':method'        => 'POST',
            ':url'           => 'Bundle',
            ':status'        => self::STATUS_PENDING,
            ':max_attempts'  => $maxAttempts,
            ':user_id'       => $userId,
            ':metadata'      => $metadata ? json_encode($metadata) : null,
        ]);
        return $this->get((int) $this->pdo->lastInsertId());
    }

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
    ): array {
        $uuid = $idempotencyKey ?? $this->generateUuid();
        $stmt = $this->pdo->prepare(
            "INSERT INTO satusehat_queue
               (uuid, bundle_type, bundle_payload, resource_type, method, url,
                status, attempts, max_attempts, etag, idempotency_key, user_id, metadata,
                created_at, updated_at)
             VALUES
               (:uuid, 'single', :bundle_payload, :resource_type, :method, :url,
                :status, 0, :max_attempts, :etag, :idempotency_key, :user_id, :metadata,
                datetime('now'), datetime('now'))"
        );
        $stmt->execute([
            ':uuid'          => $uuid,
            ':bundle_payload' => $payload ? json_encode($payload) : null,
            ':resource_type' => $resourceType,
            ':method'        => strtoupper($method),
            ':url'           => $url,
            ':status'        => self::STATUS_PENDING,
            ':max_attempts'  => $maxAttempts,
            ':etag'          => $etag,
            ':idempotency_key' => $idempotencyKey,
            ':user_id'       => $userId,
            ':metadata'      => $metadata ? json_encode($metadata) : null,
        ]);
        return $this->get((int) $this->pdo->lastInsertId());
    }

    // ── Dequeue ───────────────────────────────────────────────────

    /**
     * Atomically claim the oldest ready-to-process pending job.
     * Uses row-level locking via UPDATE ... WHERE status = 'pending'.
     */
    public function dequeue(): ?array
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "SELECT * FROM satusehat_queue
                 WHERE status = :pending
                   AND (scheduled_at IS NULL OR scheduled_at <= datetime('now'))
                 ORDER BY id ASC
                 LIMIT 1
                 FOR UPDATE"
            );
            $stmt->execute([':pending' => self::STATUS_PENDING]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $this->pdo->rollBack();
                return null;
            }

            $update = $this->pdo->prepare(
                "UPDATE satusehat_queue
                 SET status = :status, attempts = attempts + 1, updated_at = datetime('now')
                 WHERE id = :id AND status = :pending"
            );
            $update->execute([
                ':status'   => self::STATUS_PROCESSING,
                ':id'       => $row['id'],
                ':pending'  => self::STATUS_PENDING,
            ]);

            if ($update->rowCount() === 0) {
                $this->pdo->rollBack();
                return null;
            }

            $this->pdo->commit();
            return $this->decode($row);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw QueueException::dequeue($e->getMessage(), $e);
        }
    }

    // ── Update ─────────────────────────────────────────────────────

    public function markSuccess(int $id, array $response = [], ?string $location = ''): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE satusehat_queue
             SET status = :status, last_response = :response,
                 completed_at = datetime('now'), last_error = NULL, updated_at = datetime('now')
             WHERE id = :id"
        );
        $response['location'] = $location;
        $stmt->execute([
            ':status'   => self::STATUS_SUCCESS,
            ':response' => json_encode($response),
            ':id'       => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function markFailed(int $id, string $error, array $response = []): bool
    {
        $row = $this->get($id);
        if (!$row) return false;

        if ($row['attempts'] >= $row['max_attempts']) {
            return $this->markDlq($id, $error, $response);
        }

        // Exponential backoff: attempts^2 * 30 seconds, max 5 min
        $backoffSeconds = min(300, (int) pow($row['attempts'], 2) * 30);

        $stmt = $this->pdo->prepare(
            "UPDATE satusehat_queue
             SET status = :status, last_error = :error,
                 last_response = :response,
                 scheduled_at = datetime('now', :backoff || ' seconds'),
                 updated_at = datetime('now')
             WHERE id = :id"
        );
        $stmt->execute([
            ':status'    => self::STATUS_PENDING,
            ':error'     => $error,
            ':response'  => json_encode($response),
            ':backoff'   => $backoffSeconds,
            ':id'        => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function markDlq(int $id, string $reason, array $response = []): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE satusehat_queue
             SET status = :status, dlq_reason = :reason,
                 last_error = :reason, last_response = :response,
                 updated_at = datetime('now')
             WHERE id = :id"
        );
        $stmt->execute([
            ':status'   => self::STATUS_DLQ,
            ':reason'   => $reason,
            ':response' => json_encode($response),
            ':id'       => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    public function reset(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE satusehat_queue
             SET status = :status, attempts = 0, scheduled_at = NULL,
                 last_error = NULL, dlq_reason = NULL, updated_at = datetime('now')
             WHERE id = :id"
        );
        $stmt->execute([
            ':status' => self::STATUS_PENDING,
            ':id'     => $id,
        ]);
        return $stmt->rowCount() > 0;
    }

    // ── Status helpers ──────────────────────────────────────────────

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM satusehat_queue WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->decode($row) : null;
    }

    public function pendingCount(): int
    {
        $stmt = $this->pdo->query(
            "SELECT COUNT(*) FROM satusehat_queue
             WHERE status = 'pending'
               AND (scheduled_at IS NULL OR scheduled_at <= datetime('now'))"
        );
        return (int) $stmt->fetchColumn();
    }

    public function dlqCount(): int
    {
        $stmt = $this->pdo->query(
            "SELECT COUNT(*) FROM satusehat_queue WHERE status = 'dlq'"
        );
        return (int) $stmt->fetchColumn();
    }

    public function stats(): array
    {
        $stmt = $this->pdo->query(
            "SELECT status, COUNT(*) as cnt FROM satusehat_queue GROUP BY status"
        );
        $out = ['pending' => 0, 'processing' => 0, 'success' => 0, 'failed' => 0, 'dlq' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $out[$row['status']] = (int) $row['cnt'];
        }
        return $out;
    }

    public function pendingJobs(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM satusehat_queue
             WHERE status = 'pending'
               AND (scheduled_at IS NULL OR scheduled_at <= datetime('now'))
             ORDER BY id ASC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => $this->decode($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function dlqJobs(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM satusehat_queue WHERE status = 'dlq' ORDER BY id DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return array_map(fn($r) => $this->decode($r), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // ── Admin ─────────────────────────────────────────────────────

    public function resetAll(): int
    {
        $stmt = $this->pdo->prepare(
            "UPDATE satusehat_queue
             SET status = 'pending', attempts = 0, scheduled_at = NULL,
                 last_error = NULL, dlq_reason = NULL, updated_at = datetime('now')
             WHERE status IN ('failed', 'dlq')"
        );
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function purgeSuccess(int $days = 7): int
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM satusehat_queue
             WHERE status = 'success'
               AND completed_at <= datetime('now', :days || ' days')"
        );
        $stmt->execute([':days' => -$days]);
        return $stmt->rowCount();
    }

    // ── Utilities ───────────────────────────────────────────────────

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Expose PDO for monitoring tools.
     * Use sparingly — direct PDO bypasses queue abstraction.
     */
    public function pdo(): \PDO
    {
        return $this->pdo;
    }

    private function decode(array $row): array
    {
        if ($row['bundle_payload'] !== null && $row['bundle_payload'] !== '') {
            $row['bundle_payload'] = json_decode($row['bundle_payload'], true);
        }
        if ($row['last_response'] !== null && $row['last_response'] !== '') {
            $row['last_response'] = json_decode($row['last_response'], true);
        }
        if ($row['metadata'] !== null && $row['metadata'] !== '') {
            $row['metadata'] = json_decode($row['metadata'], true);
        }
        return $row;
    }
}

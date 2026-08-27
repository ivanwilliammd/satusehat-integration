<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Queue monitoring — stats, health, error analytics.
 *
 * Usage (standalone):
 *   $pdo = new PDO('sqlite:' . $dbPath);
 *   $queue = new SqliteQueue($pdo);
 *   $monitor = new QueueMonitor($queue);
 *   print_r($monitor->summary());
 */
class QueueMonitor
{
    private SqliteQueue $queue;

    public function __construct(SqliteQueue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * High-level summary: counts, rates, queue depth.
     */
    public function summary(): array
    {
        $stats = $this->queue->stats();
        $total = array_sum($stats);

        // Success rate (last 7 days — approximated by non-dlq)
        $nonDlq = $stats['success'] + $stats['pending'] + $stats['processing'] + $stats['failed'];
        $successRate = $total > 0
            ? round($stats['success'] / $total * 100, 1)
            : 0.0;

        // Oldest pending job
        $oldest = $this->oldestPending();

        return [
            'total'        => $total,
            'pending'      => $stats['pending'],
            'processing'   => $stats['processing'],
            'success'      => $stats['success'],
            'failed'       => $stats['failed'],
            'dlq'          => $stats['dlq'],
            'success_rate' => $successRate,
            'oldest_pending_at' => $oldest !== null && isset($oldest['created_at'])
                ? $oldest['created_at'] : null,
            'dlq_count'    => $stats['dlq'],
        ];
    }

    /**
     * Health check — returns status + recommendations.
     */
    public function healthCheck(): array
    {
        $stats = $this->queue->stats();

        $status = 'healthy';
        $issues = [];

        if ($stats['dlq'] > 0 && $stats['dlq'] >= $stats['success']) {
            $status = 'critical';
            $issues[] = 'DLQ count >= success count — investigate failed jobs';
        } elseif ($stats['dlq'] > 20) {
            $status = 'degraded';
            $issues[] = "{$stats['dlq']} jobs in DLQ — review and requeue or fix payload";
        }

        if ($stats['pending'] > 500) {
            $status = $status === 'healthy' ? 'degraded' : $status;
            $issues[] = "{$stats['pending']} pending jobs — worker may be idle or under-provisioned";
        }

        $oldest = $this->oldestPending();
        if ($oldest !== null && isset($oldest['created_at'])) {
            $created = strtotime($oldest['created_at']);
            $ageMinutes = (time() - $created) / 60;
            if ($ageMinutes > 30 && $stats['processing'] === 0) {
                $status = $status === 'healthy' ? 'degraded' : $status;
                $issues[] = "Oldest pending job is {$ageMinutes}min old — worker may be down";
            }
        }

        return [
            'status'  => $status,
            'issues'  => $issues,
            'summary' => $this->summary(),
        ];
    }

    /**
     * Top error categories from failed + DLQ jobs.
     */
    public function topErrors(int $limit = 10): array
    {
        $pdo = $this->pdo();
        $stmt = $pdo->query(
            "SELECT last_error, COUNT(*) as cnt
             FROM satusehat_queue
             WHERE status IN ('failed','dlq') AND last_error IS NOT NULL
             GROUP BY last_error
             ORDER BY cnt DESC
             LIMIT {$limit}"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function oldestPending(): ?array
    {
        $pdo = $this->pdo();
        $stmt = $pdo->query(
            "SELECT id, created_at, method, url, status, attempts
             FROM satusehat_queue
             WHERE status IN ('pending','failed')
             ORDER BY created_at ASC
             LIMIT 1"
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function pdo(): \PDO
    {
        return $this->queue->pdo();
    }
}

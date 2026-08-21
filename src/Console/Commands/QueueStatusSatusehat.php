<?php

namespace Satusehat\Integration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Satusehat\Integration\Models\SatusehatQueue;

class QueueStatusSatusehat extends Command
{
    protected $signature = 'satusehat:queue-status
        {--dlq : Show only DLQ entries}';

    protected $description = 'Show SATUSEHAT queue status summary';

    public function handle(): int
    {
        $showDlq = (bool) $this->option('dlq');

        if ($showDlq) {
            return $this->showDlq();
        }

        $total     = SatusehatQueue::count();
        $pending   = SatusehatQueue::pending()->count();
        $processing = SatusehatQueue::where('status', SatusehatQueue::STATUS_PROCESSING)->count();
        $success   = SatusehatQueue::where('status', SatusehatQueue::STATUS_SUCCESS)->count();
        $failed    = SatusehatQueue::failed()->count();
        $dlq       = SatusehatQueue::dlq()->count();

        $this->newLine();
        $this->info('═══ SATUSEHAT Queue Status ═══');
        $this->table(
            ['Status', 'Count'],
            [
                ['pending',     $pending],
                ['processing',  $processing],
                ['success',     $success],
                ['failed',      $failed],
                ['dlq',         $dlq],
                ['TOTAL',       $total],
            ]
        );

        if ($dlq > 0) {
            $this->newLine();
            $this->warn("⚠  {$dlq} entries in DLQ.");
            $this->line('  View: php artisan satusehat:queue-status --dlq');
            $this->line('  Retry all: php artisan satusehat:process-queue --reset');
        }

        // Show pending + ready entries
        $ready = SatusehatQueue::ready()->limit(10)->get(['id', 'resource_type', 'method', 'url', 'status', 'attempts', 'scheduled_at']);
        if ($ready->isNotEmpty()) {
            $this->newLine();
            $this->info('═══ Next Pending Entries ═══');
            $this->table(
                ['ID', 'Resource', 'Method', 'URL', 'Attempts', 'Scheduled'],
                $ready->map(fn ($e) => [
                    $e->id,
                    $e->resource_type,
                    $e->method,
                    $e->url,
                    "{$e->attempts}/{$e->max_attempts}",
                    $e->scheduled_at?->toDateTimeString() ?? 'now',
                ])->toArray()
            );
        }

        return Command::SUCCESS;
    }

    private function showDlq(): int
    {
        $entries = SatusehatQueue::dlq()
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get(['id', 'resource_type', 'method', 'url', 'attempts', 'dlq_reason', 'updated_at']);

        if ($entries->isEmpty()) {
            $this->info('DLQ is empty.');
            return Command::SUCCESS;
        }

        $this->warn("═══ Dead Letter Queue ({$entries->count()} entries) ═══");
        $this->table(
            ['ID', 'Resource', 'Method', 'URL', 'Attempts', 'Reason'],
            $entries->map(fn ($e) => [
                $e->id,
                $e->resource_type,
                $e->method,
                $e->url,
                $e->attempts,
                Str::limit($e->dlq_reason ?? '-', 40),
            ])->toArray()
        );

        $this->newLine();
        $this->line('Re-queue all: php artisan satusehat:process-queue --reset');
        $this->line('Re-queue single: UPDATE satusehat_queue SET status=\'pending\', attempts=0 WHERE id=?');

        return Command::SUCCESS;
    }
}

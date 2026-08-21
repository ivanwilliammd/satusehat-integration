<?php

namespace Satusehat\Integration\Console\Commands;

use Illuminate\Console\Command;
use Satusehat\Integration\Models\SatusehatQueue;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\SSRequest\SSRequest;
use Satusehat\Integration\SSResponse\BundleResponse;

class ProcessSatusehatQueue extends Command
{
    protected $signature = 'satusehat:process-queue
        {--limit=50 : Max entries to process per run}
        {--retry : Include previously failed entries (status=failed)}
        {--dlq : Process DLQ entries (attempts=max, status=dlq)}
        {--reset : Reset failed/dlq entries back to pending before processing}
        {--once : Process once and exit (for cron, not daemon)}';

    protected $description = 'Process pending SATUSEHAT FHIR queue entries';

    private int $processed = 0;
    private int $success = 0;
    private int $failed = 0;
    private int $dlq = 0;

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $retry = (bool) $this->option('retry');
        $dlq   = (bool) $this->option('dlq');
        $reset = (bool) $this->option('reset');
        $once  = (bool) $this->option('once');

        if ($reset) {
            return $this->resetAll();
        }

        if ($dlq) {
            $this->processDlq($limit);
        } else {
            $this->processPending($limit, $retry);
        }

        $this->newLine();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Processed', $this->processed],
                ['Success',   $this->success],
                ['Failed',    $this->failed],
                ['DLQ',       $this->dlq],
            ]
        );

        if ($once || $this->processed >= $limit) {
            return Command::SUCCESS;
        }

        sleep(5);
        return Command::SUCCESS;
    }

    private function processPending(int $limit, bool $retry): void
    {
        $query = SatusehatQueue::query()
            ->where(function ($q) {
                $q->where('status', SatusehatQueue::STATUS_PENDING)
                  ->where(function ($inner) {
                      $inner->whereNull('scheduled_at')
                            ->orWhere('scheduled_at', '<=', now());
                  });
            });

        if ($retry) {
            $query->orWhere('status', SatusehatQueue::STATUS_FAILED);
        }

        $entries = $query->orderBy('created_at')->limit($limit)->get();

        if ($entries->isEmpty()) {
            $this->info('No pending entries.');
            return;
        }

        $this->info('Processing ' . $entries->count() . ' pending entries...');

        foreach ($entries as $entry) {
            $this->processEntry($entry);
        }
    }

    private function processDlq(int $limit): void
    {
        $entries = SatusehatQueue::where('status', SatusehatQueue::STATUS_DLQ)
            ->limit($limit)
            ->get();

        if ($entries->isEmpty()) {
            $this->info('DLQ is empty.');
            return;
        }

        $this->warn('Processing ' . $entries->count() . ' DLQ entries...');

        foreach ($entries as $entry) {
            $this->info('DLQ entry #' . $entry->id . ': ' . $entry->dlq_reason);
            $this->dlq++;
            $this->processed++;
        }
    }

    private function processEntry(SatusehatQueue $entry): void
    {
        $this->processed++;

        try {
            $entry->markProcessing();
        } catch (\Throwable) {
            return;
        }

        try {
            $ssRequest = new SSRequest(new OAuth2Client());

            if ($entry->bundle_type === 'single') {
                $response = $this->processSingle($ssRequest, $entry);
            } else {
                $response = $ssRequest->post('Bundle', $entry->bundle_payload);
            }

            $httpCode = $response->getHttpCode();
            $body     = $response->getBody();

            if ($httpCode >= 200 && $httpCode < 300) {
                $location = isset($body['entry'][0]['response']['location'])
                    ? $body['entry'][0]['response']['location']
                    : '';
                $entry->markSuccess($body, $location);
                $this->success++;
                $this->line('  [OK] #' . $entry->id . ' ' . $entry->method . ' ' . $entry->url . ' => ' . $httpCode);

                return;
            }

            if ($entry->isRetriableStatus($httpCode)) {
                $error = $this->extractError($body);
                $entry->markFailed($error, $body);
                $this->failed++;
                $this->warn('  [RETRY] #' . $entry->id . ' ' . $entry->method . ' ' . $entry->url . ' => ' . $httpCode . ' (attempt ' . $entry->attempts . ')');

                return;
            }

            $error = $this->extractError($body);
            $entry->markDlq($error, $body);
            $this->dlq++;
            $this->error('  [DLQ] #' . $entry->id . ' ' . $entry->method . ' ' . $entry->url . ' => ' . $httpCode);

        } catch (\Throwable $e) {
            $entry->markFailed($e->getMessage());
            $this->failed++;
            $this->error('  [ERR] #' . $entry->id . ' EXCEPTION: ' . $e->getMessage());
        }
    }

    private function processSingle(SSRequest $ssRequest, SatusehatQueue $entry)
    {
        $method  = strtoupper($entry->method);
        $url     = $entry->url;
        $payload = $entry->bundle_payload;

        switch ($method) {
            case 'POST':
                return $ssRequest->post($url, $payload);
            case 'PUT':
                return $ssRequest->put($url, $payload);
            case 'PATCH':
                return $ssRequest->patch($url, $payload);
            case 'DELETE':
                return $ssRequest->delete($url, $payload);
            default:
                throw new \RuntimeException('Unsupported HTTP method: ' . $method);
        }
    }

    private function extractError(array $body): string
    {
        if (! isset($body['resourceType']) || $body['resourceType'] !== 'OperationOutcome') {
            return 'HTTP error (resourceType not OperationOutcome)';
        }

        $messages = [];
        $issues = isset($body['issue']) && is_array($body['issue']) ? $body['issue'] : [];

        foreach ($issues as $issue) {
            $msg = isset($issue['details']['text'])
                ? $issue['details']['text']
                : (isset($issue['diagnostics'])
                    ? $issue['diagnostics']
                    : (isset($issue['details']['coding'][0]['display'])
                        ? $issue['details']['coding'][0]['display']
                        : 'Unknown error'));
            $messages[] = $msg;
        }

        return implode('; ', $messages);
    }

    private function resetAll(): int
    {
        $count = SatusehatQueue::whereIn('status', [
            SatusehatQueue::STATUS_FAILED,
            SatusehatQueue::STATUS_DLQ,
        ])->update([
            'status'       => SatusehatQueue::STATUS_PENDING,
            'attempts'     => 0,
            'scheduled_at' => null,
            'dlq_reason'   => null,
        ]);

        $this->info('Reset ' . $count . ' failed/dlq entries back to pending.');

        return Command::SUCCESS;
    }
}

<?php

namespace Satusehat\Integration\Console\Commands;

use Illuminate\Console\Command;
use Satusehat\Integration\Models\SatusehatQueue;

class EnqueueSatusehat extends Command
{
    protected $signature = 'satusehat:enqueue
        {method : HTTP method (POST|PUT|PATCH|DELETE)}
        {url : FHIR URL (e.g. Bundle, Patient/123)}
        {--payload= : JSON payload file path or inline JSON}
        {--bundle : Treat payload as a Bundle JSON (for batch/transaction)}
        {--max-attempts=5 : Max retry attempts}
        {--scheduled= : ISO8601 datetime to defer execution}
        {--idempotency= : Custom idempotency key (UUID)}
        {--etag= : ETag for conditional PUT/PATCH}
        {--metadata= : JSON metadata context}';

    protected $description = 'Enqueue a SATUSEHAT FHIR operation to the durable queue';

    public function handle(): int
    {
        $method  = strtoupper($this->argument('method'));
        $url     = $this->argument('url');
        $payload = $this->parsePayload();

        // Extract resource type from URL
        $resourceType = $this->parseResourceType($url);

        $entry = SatusehatQueue::create([
            'uuid'             => $this->option('idempotency') ?? \Illuminate\Support\Str::uuid()->toString(),
            'bundle_type'      => $this->option('bundle') ? 'transaction' : 'single',
            'bundle_payload'   => $payload,
            'resource_type'    => $resourceType,
            'method'           => $method,
            'url'              => $url,
            'status'           => SatusehatQueue::STATUS_PENDING,
            'attempts'         => 0,
            'max_attempts'     => (int) $this->option('max-attempts'),
            'etag'             => $this->option('etag'),
            'idempotency_key'  => $this->option('idempotency'),
            'scheduled_at'     => $this->option('scheduled')
                                    ? \Carbon\Carbon::parse($this->option('scheduled'))
                                    : null,
            'user_id'          => config('satusehatintegration.log_user_id', 'system'),
            'metadata'         => $this->option('metadata')
                                    ? json_decode($this->option('metadata'), true)
                                    : null,
        ]);

        $scheduled = $this->option('scheduled')
            ? " (scheduled: {$this->option('scheduled')})"
            : '';

        $this->info("Queued #{$entry->id}: {$method} {$url}{$scheduled}");
        $this->line("  UUID: {$entry->uuid}");

        return Command::SUCCESS;
    }

    private function parsePayload(): ?array
    {
        $raw = $this->option('payload');

        if (! $raw) {
            // Try reading from stdin
            $stdin = fopen('php://stdin', 'r');
            $stream = '';
            if (stream_set_blocking($stdin, false)) {
                $stream = trim(fgets($stdin));
            }
            fclose($stdin);

            if ($stream && $stream !== '' && $stream !== 'null') {
                $raw = $stream;
            }
        }

        if (! $raw || $raw === '' || $raw === 'null') {
            return null;
        }

        // Check if it's a file path
        if (file_exists($raw)) {
            return json_decode(file_get_contents($raw), true, 512, JSON_THROW_ON_ERROR);
        }

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    private function parseResourceType(string $url): string
    {
        // e.g. "Bundle" → "Bundle", "Patient/123" → "Patient"
        $parts = explode('/', trim($url, '/'));
        return $parts[0] ?? 'Unknown';
    }
}

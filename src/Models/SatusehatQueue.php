<?php

namespace Satusehat\Integration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Satusehat\Integration\Models\SatusehatQueue.
 *
 * Durable outbox queue for SATUSEHAT FHIR transactions.
 * Uses SQLite-compatible schema so it can run in-process without
 * requiring an external queue server (Redis, RabbitMQ, etc.).
 *
 * Status flow: pending → processing → success | failed | dlq
 * Retry: pending entries are picked up by ProcessSatusehatQueue command.
 *
 * @property int $id
 * @property string $uuid             UUID for idempotency key (If-None-Exist)
 * @property string $bundle_type      Bundle type: transaction | batch
 * @property array $bundle_payload    Full Bundle JSON payload
 * @property string $resource_type    e.g. "Bundle"
 * @property string $method           HTTP method: POST | PUT | PATCH | DELETE
 * @property string $url              e.g. "Bundle"
 * @property string $status           pending | processing | success | failed | dlq
 * @property int $attempts            Number of attempts made
 * @property int $max_attempts        Max retries (default: 5)
 * @property string|null $last_error  Last error message
 * @property array|null $last_response Last server response
 * @property string|null $etag         ETag for conditional updates
 * @property string|null $idempotency_key For If-None-Exist lookup
 * @property string|null $scheduled_at When to process (null = now)
 * @property string|null $completed_at When it succeeded
 * @property string|null $dlq_reason  Why sent to DLQ
 * @property string|null $user_id      Originating user/system
 * @property string|null $metadata     Extra context (JSON)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SatusehatQueue extends Model
{
    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS    = 'success';
    public const STATUS_FAILED     = 'failed';
    public const STATUS_DLQ        = 'dlq';  // Dead Letter Queue

    public const DEFAULT_MAX_ATTEMPTS = 5;

    public $table;

    public $guarded = [];

    public function __construct(array $attributes = [])
    {
        if (! isset($this->connection)) {
            $this->setConnection(config('satusehatintegration.database_connection_satusehat'));
        }

        if (! isset($this->table)) {
            $this->setTable(config('satusehatintegration.queue_table_name'));
        }

        parent::__construct($attributes);
    }

    protected $casts = [
        'bundle_payload'  => 'array',
        'last_response'   => 'array',
        'metadata'        => 'array',
        'attempts'        => 'integer',
        'max_attempts'    => 'integer',
        'scheduled_at'    => 'datetime',
        'completed_at'    => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeReady($query)
    {
        return $query
            ->where('status', self::STATUS_PENDING)
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                  ->orWhere('scheduled_at', '<=', now());
            });
    }

    public function scopeDlq($query)
    {
        return $query->where('status', self::STATUS_DLQ);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isDlq(): bool
    {
        return $this->status === self::STATUS_DLQ;
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->max_attempts
            && ! $this->isDlq();
    }

    public function isRetriableStatus(int $httpCode): bool
    {
        return in_array($httpCode, [408, 429, 500, 502, 503, 504], true);
    }

    public function markProcessing(): self
    {
        $this->update([
            'status'    => self::STATUS_PROCESSING,
            'attempts'  => $this->attempts + 1,
        ]);

        return $this;
    }

    public function markSuccess(array $response = [], string $location = ''): self
    {
        $this->update([
            'status'        => self::STATUS_SUCCESS,
            'last_response' => $response,
            'completed_at'  => now(),
            'last_error'    => null,
        ]);

        return $this;
    }

    public function markFailed(string $error, array $response = []): self
    {
        if (! $this->canRetry()) {
            return $this->markDlq($error, $response);
        }

        // Exponential backoff: attempts^2 seconds, max 5 min
        $backoffSeconds = min(300, (int) pow($this->attempts, 2) * 30);

        $this->update([
            'status'        => self::STATUS_PENDING,
            'last_error'    => $error,
            'last_response' => $response,
            'scheduled_at'  => now()->addSeconds($backoffSeconds),
        ]);

        return $this;
    }

    public function markDlq(string $reason, array $response = []): self
    {
        $this->update([
            'status'        => self::STATUS_DLQ,
            'dlq_reason'   => $reason,
            'last_response' => $response,
        ]);

        return $this;
    }

    public function reset(): self
    {
        $this->update([
            'status'      => self::STATUS_PENDING,
            'attempts'    => 0,
            'scheduled_at' => null,
            'last_error'  => null,
        ]);

        return $this;
    }

    // -------------------------------------------------------------------------
    // Enqueue helpers
    // -------------------------------------------------------------------------

    /**
     * Enqueue a Bundle payload for async processing.
     */
    public static function enqueueBundle(
        array  $bundlePayload,
        string $bundleType = 'transaction',
        ?string $userId = null,
        ?int $maxAttempts = null,
        ?string $metadata = null
    ): self {
        return static::create([
            'uuid'          => \Illuminate\Support\Str::uuid()->toString(),
            'bundle_type'   => $bundleType,
            'bundle_payload' => $bundlePayload,
            'resource_type' => 'Bundle',
            'method'        => 'POST',
            'url'           => 'Bundle',
            'status'        => self::STATUS_PENDING,
            'attempts'      => 0,
            'max_attempts'  => $maxAttempts ?? self::DEFAULT_MAX_ATTEMPTS,
            'user_id'       => $userId ?? config('satusehatintegration.log_user_id', 'system'),
            'metadata'      => $metadata ? json_decode($metadata, true) : null,
        ]);
    }

    /**
     * Enqueue a single resource operation (POST/PUT/PATCH/DELETE).
     */
    public static function enqueue(
        string $method,
        string $resourceType,
        string $url,
        ?array $payload = null,
        ?string $etag = null,
        ?string $idempotencyKey = null,
        ?string $userId = null,
        ?int $maxAttempts = null,
        ?string $metadata = null
    ): self {
        return static::create([
            'uuid'             => $idempotencyKey ?? \Illuminate\Support\Str::uuid()->toString(),
            'bundle_type'      => 'single',
            'bundle_payload'   => $payload,
            'resource_type'   => $resourceType,
            'method'           => strtoupper($method),
            'url'              => $url,
            'status'           => self::STATUS_PENDING,
            'attempts'         => 0,
            'max_attempts'     => $maxAttempts ?? self::DEFAULT_MAX_ATTEMPTS,
            'etag'             => $etag,
            'idempotency_key'  => $idempotencyKey,
            'user_id'          => $userId ?? config('satusehatintegration.log_user_id', 'system'),
            'metadata'         => $metadata ? json_decode($metadata, true) : null,
        ]);
    }
}

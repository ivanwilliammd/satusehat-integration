<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Immutable result of a queue job processed by Worker.
 */
final class QueueResult
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED  = 'failed';
    public const STATUS_DLQ    = 'dlq';

    private function __construct(
        public readonly int    $id,
        public readonly string $status,
        public readonly ?string $error,
        public readonly ?array  $response,
        public readonly int     $attempts,
    ) {}

    public static function success(int $id, array $response = [], int $attempts = 1): self
    {
        return new self($id, self::STATUS_SUCCESS, null, $response, $attempts);
    }

    public static function failed(int $id, string $error, array $response = [], int $attempts = 1): self
    {
        return new self($id, self::STATUS_FAILED, $error, $response, $attempts);
    }

    public static function dlq(int $id, string $reason, array $response = [], int $attempts = 0): self
    {
        return new self($id, self::STATUS_DLQ, $reason, $response, $attempts);
    }

    public function isSuccess(): bool  { return $this->status === self::STATUS_SUCCESS; }
    public function isFailed(): bool   { return $this->status === self::STATUS_FAILED; }
    public function isDlq(): bool      { return $this->status === self::STATUS_DLQ; }
    public function isRetriable(): bool { return $this->isFailed() && $this->attempts < 5; }
}

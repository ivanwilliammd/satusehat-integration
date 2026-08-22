<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

use Exception;

/**
 * Thrown by queue operations on unrecoverable errors.
 */
final class QueueException extends Exception
{
    public const E_CONNECTION  = 1;
    public const E_ENQUEUE    = 2;
    public const E_DEQUEUE    = 3;
    public const E_UPDATE    = 4;

    public static function connection(string $msg, ?\Throwable $prev = null): self
    {
        return new self("Queue connection error: {$msg}", self::E_CONNECTION, $prev);
    }

    public static function enqueue(string $msg, ?\Throwable $prev = null): self
    {
        return new self("Failed to enqueue: {$msg}", self::E_ENQUEUE, $prev);
    }

    public static function dequeue(string $msg, ?\Throwable $prev = null): self
    {
        return new self("Failed to dequeue: {$msg}", self::E_DEQUEUE, $prev);
    }

    public static function update(string $msg, ?\Throwable $prev = null): self
    {
        return new self("Failed to update job: {$msg}", self::E_UPDATE, $prev);
    }
}

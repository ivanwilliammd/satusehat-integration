<?php

declare(strict_types=1);

namespace Satusehat\Integration\Queue;

/**
 * Per-process rate limiter using sliding window.
 *
 * Prevents hammering SATUSEHAT API when processing batches.
 *
 * Usage:
 *   $limiter = new RateLimiter(300); // 300 RPM
 *   while ($jobs) {
 *       $limiter->wait();              // blocks until slot available
 *       $this->send($job);
 *   }
 */
class RateLimiter
{
    /** @var float Requests per minute */
    private float $rpm;

    /** @var float Seconds between requests */
    private float $interval;

    /** @var float|null Last request timestamp */
    private ?float $lastRequestAt = null;

    public function __construct(float $rpm = 300)
    {
        $this->rpm = max(1, $rpm);
        $this->interval = 60.0 / $this->rpm;
    }

    /**
     * Block until a request slot is available.
     */
    public function wait(): void
    {
        if ($this->lastRequestAt !== null) {
            $elapsed = microtime(true) - $this->lastRequestAt;
            $remaining = $this->interval - $elapsed;
            if ($remaining > 0) {
                usleep((int) ($remaining * 1_000_000));
            }
        }
        $this->lastRequestAt = microtime(true);
    }

    /**
     * Seconds to wait based on Retry-After header value.
     */
    public static function parseRetryAfter(?string $header): ?float
    {
        if ($header === null || $header === '') {
            return null;
        }

        // HTTP-date format: "Wed, 31 Oct 2026 12:00:00 GMT"
        if (preg_match('/^[A-Z][a-z]{2},/', $header)) {
            $ts = @strtotime($header);
            return $ts !== false ? max(0, $ts - time()) : null;
        }

        // Integer seconds
        $seconds = filter_var(trim($header), FILTER_VALIDATE_INT);
        return $seconds !== false ? (float) $seconds : null;
    }
}

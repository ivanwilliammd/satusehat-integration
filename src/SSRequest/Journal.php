<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSRequest;

/**
 * Append-only record of what was already sent, so a failed run can be resumed without re-sending anything
 * that returned 2xx. One line per payload, JSONL, because a crash mid-write must cost at most the last line.
 *
 * The fingerprint is over the encoded payload, not over the file name: the same Bundle sent twice is the same
 * hash, while a corrected payload deliberately gets a new one and is sent again.
 */
final class Journal
{
    /** @var array<string,true>|null loaded once; a resume run asks for the same file hundreds of times */
    private ?array $sent = null;

    public function __construct(private readonly string $path)
    {
    }

    /** SHA-256 truncated to 16 hex characters: enough to tell payloads apart, short enough to read in a log. */
    public static function fingerprint(array $payload): string
    {
        return substr(hash('sha256', json_encode($payload, JSON_THROW_ON_ERROR)), 0, 16);
    }

    /** @return array<string,true> fingerprints that already have a 2xx line */
    public function sentFingerprints(): array
    {
        if ($this->sent !== null) {
            return $this->sent;
        }
        $this->sent = [];
        if (!is_file($this->path)) {
            return $this->sent;
        }
        $handle = fopen($this->path, 'rb');
        if ($handle === false) {
            return $this->sent;
        }
        while (($line = fgets($handle)) !== false) {
            $row = json_decode(trim($line), true);
            if (!is_array($row) || !isset($row['hash'], $row['status'])) {
                continue; // a half-written last line is not evidence of anything
            }
            $status = (int) $row['status'];
            if ($status >= 200 && $status < 300) {
                $this->sent[(string) $row['hash']] = true;
            }
        }
        fclose($handle);

        return $this->sent;
    }

    public function alreadySent(string $fingerprint): bool
    {
        return isset($this->sentFingerprints()[$fingerprint]);
    }

    public function record(string $fingerprint, int $status, ?string $error = null): void
    {
        $line = json_encode([
            'hash' => $fingerprint,
            'status' => $status,
            'at' => gmdate('Y-m-d\TH:i:s\Z'),
            'err' => $error,
        ], JSON_THROW_ON_ERROR);

        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($this->path, $line . "\n", FILE_APPEND | LOCK_EX);

        if ($status >= 200 && $status < 300) {
            $this->sent[$fingerprint] = true;
        }
    }
}

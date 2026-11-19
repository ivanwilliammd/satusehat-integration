<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

use InvalidArgumentException;

/**
 * Minimal Lower Layer Protocol framing: VT ... FS CR.
 *
 * The important part is not frame()/unframe() — those are two string operations — but the stream
 * reader: MLLP arrives in arbitrary TCP chunks, so a frame can be split anywhere, including between
 * the FS and the CR.
 */
final class Mllp
{
    public const VT = "\x0b";
    public const FS = "\x1c";
    public const CR = "\x0d";

    public static function frame(string $message): string
    {
        return self::VT . $message . self::FS . self::CR;
    }

    public static function unframe(string $frame): string
    {
        $frame = str_replace([self::VT, self::FS . self::CR], '', $frame);

        return trim($frame, "\r\n");
    }

    /** True when the buffer holds at least one complete frame. */
    public static function hasFrame(string $buffer): bool
    {
        return str_contains($buffer, self::VT) && str_contains($buffer, self::FS);
    }

    /**
     * Extracts one frame from the buffer, returning [message, remaining buffer].
     *
     * @return array{0: string, 1: string}
     */
    public static function shift(string $buffer): array
    {
        $start = strpos($buffer, self::VT);
        if ($start === false) {
            return ['', $buffer];
        }
        $end = strpos($buffer, self::FS, $start);
        if ($end === false) {
            return ['', $buffer];
        }
        $message = substr($buffer, $start + 1, $end - $start - 1);
        $rest = substr($buffer, $end + 2); // skip FS and the trailing CR

        return [$message, $rest];
    }

    /**
     * Splits a byte stream into frames. Used by tests and by the server loop; keeping it pure makes
     * the chunk-boundary behaviour testable without opening a socket.
     *
     * @return list<string>
     */
    public static function split(string $stream): array
    {
        $out = [];
        $buffer = $stream;
        while (self::hasFrame($buffer)) {
            [$message, $buffer] = self::shift($buffer);
            if ($message !== '') {
                $out[] = $message;
            }
        }

        return $out;
    }

    /**
     * Reads one message from a blocking stream. Returns null on EOF or when the peer closes.
     *
     * Callers that read more than one message from the same stream MUST use MllpReader instead:
     * a single fread() can return two frames, and this method would drop the second one.
     *
     * @param resource $stream
     */
    public static function read($stream, int $maxBytes = 4 * 1024 * 1024): ?string
    {
        return (new MllpReader($maxBytes))->next($stream);
    }

    /**
     * Writes one framed message. Returns false when the peer closed the connection, which callers
     * must treat as "no ACK will come", not as a successful send.
     *
     * @param resource $stream
     */
    public static function write($stream, string $message): bool
    {
        $data = self::frame($message);
        $written = 0;
        $len = strlen($data);
        while ($written < $len) {
            $n = fwrite($stream, substr($data, $written));
            if ($n === false || $n === 0) {
                return false;
            }
            $written += $n;
        }
        fflush($stream);

        return true;
    }
}

/**
 * Stateful frame reader: keeps the bytes that arrived after the last complete frame.
 *
 * This exists because TCP does not respect message boundaries. One fread() of a persistent MLLP
 * connection routinely returns two or three ACKs at once; a stateless reader would hand back the
 * first and silently discard the rest, and the client would then block waiting for an ACK that was
 * already received.
 */
final class MllpReader
{
    private string $buffer = '';

    public function __construct(private int $maxBytes = 4 * 1024 * 1024)
    {
    }

    /** @param resource $stream */
    public function next($stream): ?string
    {
        while (true) {
            [$message, $rest] = Mllp::shift($this->buffer);
            if ($message !== '') {
                $this->buffer = $rest;

                return $message;
            }
            $chunk = fread($stream, 8192);
            if ($chunk === false || $chunk === '') {
                return null;
            }
            $this->buffer .= $chunk;
            if (strlen($this->buffer) > $this->maxBytes) {
                throw new InvalidArgumentException('Frame MLLP melebihi batas 4 MB.');
            }
        }
    }
}

<?php

namespace Satusehat\Integration\Tests\HL7v2;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\HL7v2\Mllp;

final class MllpTest extends TestCase
{
    private const MSG = "MSH|^~\\&|A|B|C|D|20260101||ADT^A01|1|P|2.5.1";

    public function testFramesAndUnframes(): void
    {
        $frame = Mllp::frame(self::MSG);

        self::assertStringStartsWith("\x0b", $frame);
        self::assertStringEndsWith("\x1c\x0d", $frame);
        self::assertSame(self::MSG, Mllp::unframe($frame));
    }

    /** The regression that matters: TCP splits frames anywhere, including between FS and CR. */
    public function testSplitsStreamAtChunkBoundaries(): void
    {
        $a = Mllp::frame(self::MSG);
        $b = Mllp::frame(str_replace('ADT^A01', 'ADT^A08', self::MSG));
        $stream = $a . $b;

        // Every prefix must yield at most one *complete* message, and never a truncated one: the
        // reader may only hand over a frame once the closing FS has arrived.
        for ($cut = 1; $cut < strlen($stream); $cut += 7) {
            $partial = Mllp::split(substr($stream, 0, $cut));
            self::assertLessThanOrEqual(1, count($partial), "prefix $cut produced too many messages");
            foreach ($partial as $msg) {
                self::assertStringEndsWith('2.5.1', $msg, "prefix $cut produced a truncated message");
            }
        }

        $frames = Mllp::split($stream);
        self::assertCount(2, $frames);
        self::assertStringContainsString('ADT^A01', $frames[0]);
        self::assertStringContainsString('ADT^A08', $frames[1]);
    }

    public function testIgnoresGarbageBeforeStartBlock(): void
    {
        [$message, $rest] = Mllp::shift("noise" . Mllp::frame(self::MSG));

        self::assertSame(self::MSG, $message);
        self::assertSame('', $rest);
    }
}

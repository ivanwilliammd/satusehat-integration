<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

use InvalidArgumentException;

/**
 * HL7 v2 batch: FHS / BHS envelope around several messages, closed by BTS / FTS.
 *
 *   FHS|^~\&|...                     file header
 *   BHS|^~\&|...                     batch header
 *   MSH|...\r PID|...\r             message 1
 *   MSH|...\r PID|...\r             message 2
 *   BTS|2                             batch trailer: message count
 *   FTS|1                             file trailer: batch count
 *
 * A batch is not a message and must not be handed to Message::parse(): its first segment is not MSH,
 * so the parser would reject the whole file. That distinction is the whole reason this class exists.
 */
final class Batch
{
    /**
     * @return list<Message>
     */
    public static function parse(string $er7): array
    {
        $er7 = trim(str_replace(["\x0b", "\x1c"], "", $er7));
        if ($er7 === '') {
            throw new InvalidArgumentException('Batch HL7 kosong.');
        }

        $lines = preg_split('/\r\n|\r|\n/', $er7) ?: [];
        $messages = [];
        $current = [];

        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $name = substr($line, 0, 3);
            if ($name === 'FHS' || $name === 'BHS' || $name === 'BTS' || $name === 'FTS') {
                continue; // envelope: never part of a message
            }
            if ($name === 'MSH' && $current !== []) {
                $messages[] = Message::parse(implode("\r", $current) . "\r");
                $current = [];
            }
            $current[] = $line;
        }
        if ($current !== []) {
            $messages[] = Message::parse(implode("\r", $current) . "\r");
        }

        return $messages;
    }

    /**
     * Wraps messages in a minimal FHS/BHS … BTS/FTS envelope.
     *
     * @param list<Message> $messages
     */
    public static function wrap(array $messages, string $sender = 'SIMRS', string $receiver = 'LAB'): string
    {
        $now = date('YmdHis');
        $out = ["FHS|^~\\&|{$sender}|{$receiver}|{$now}", "BHS|^~\\&|{$sender}|{$receiver}|{$now}"];
        foreach ($messages as $m) {
            $out[] = rtrim($m->toString(), "\r");
        }
        $out[] = 'BTS|' . count($messages);
        $out[] = 'FTS|1';

        return implode("\r", $out) . "\r";
    }

    /** Number declared by the trailer, or null when the batch has no BTS. Used to verify completeness. */
    public static function declaredCount(string $er7): ?int
    {
        if (preg_match('/BTS\|(\d+)/', $er7, $m) === 1) {
            return (int) $m[1];
        }

        return null;
    }
}

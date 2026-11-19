<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/**
 * Builds the acknowledgement for an inbound message.
 *
 * MSA-1 is the application-level verdict (AA accepted, AE error, AR rejected) and MSA-2 echoes
 * MSH-10, which is how the sender correlates the ACK with what it sent. An ACK without that echo is
 * indistinguishable from a stale one.
 */
final class Ack
{
    public const ACCEPT = 'AA';
    public const ERROR = 'AE';
    public const REJECT = 'AR';

    public static function build(Message $inbound, string $code = self::ACCEPT, ?string $text = null): Message
    {
        $msh = $inbound->segment('MSH');
        $now = date('YmdHis');
        $controlId = substr(bin2hex(random_bytes(8)), 0, 16);

        $fields = [
            1 => '|',
            2 => '^~\&',
            3 => $msh?->get('5') ?: '',   // receiving app becomes sending app
            4 => $msh?->get('6') ?: '',
            5 => $msh?->get('3') ?: '',   // sending app becomes receiving app
            6 => $msh?->get('4') ?: '',
            7 => $now,
            8 => '',
            9 => 'ACK',
            10 => $controlId,
            11 => 'P',
            12 => $msh?->get('12.1') ?: '2.5.1',
        ];

        $ack = Message::fromSegments([
            Segment::fromParts(array_merge(['MSH'], array_values(array_slice($fields, 1))), '|'),
            Segment::fromParts(['MSA', $code, $inbound->controlId(), $text ?? ''], '|'),
        ]);

        // Segment::fromParts treats MSH-1 as the separator, so MSH-2 onward must land at index 2.
        return $ack;
    }

    /** NAK helper: AE with a human-readable reason in MSA-3. */
    public static function error(Message $inbound, string $reason): Message
    {
        return self::build($inbound, self::ERROR, mb_substr($reason, 0, 200));
    }
}

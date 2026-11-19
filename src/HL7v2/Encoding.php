<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/**
 * Escape sequences defined by HL7 v2 chapter 2.
 *
 * They are the reason a naive `explode('|')` corrupts real messages: a pipe inside a patient name
 * arrives as \F\, and a caret inside an address arrives as \S\. Decoding happens on read and
 * encoding on write, so the stored field text is exactly what went over the wire.
 */
final class Encoding
{
    /** @var array<string,string> */
    public const DECODE = [
        '\\F\\' => '|',
        '\\S\\' => '^',
        '\\R\\' => '~',
        '\\E\\' => '\\',
        '\\T\\' => '&',
    ];

    /**
     * Decodes the five single-character escapes and \Xdd..\ hexadecimal sequences.
     * \Z..\ (locally defined) and \.br\ (formatting) are returned untouched: inventing a
     * meaning for them would silently change clinical text.
     */
    public static function decode(string $value): string
    {
        if ($value === '' || !str_contains($value, '\\')) {
            return $value;
        }

        $out = preg_replace_callback(
            '/\\\\X([0-9A-Fa-f]+)\\\\/',
            static fn (array $m): string => (string) hex2bin($m[1]),
            $value
        ) ?? $value;

        return strtr($out, self::DECODE);
    }

    /** Escapes the reserved characters so the field survives a round trip. */
    public static function encode(string $value): string
    {
        return strtr($value, [
            '\\' => '\\E\\',
            '|' => '\\F\\',
            '^' => '\\S\\',
            '~' => '\\R\\',
            '&' => '\\T\\',
        ]);
    }

    /**
     * MSH-18 declares the character set. Reporting it is enough: the parser is byte-oriented, and
     * re-encoding bytes would corrupt messages whose declared charset is wrong (common in the field).
     */
    public static function charsetOf(?string $msh18): string
    {
        return match (strtoupper(trim((string) $msh18))) {
            'ASCII', '' => 'ASCII',
            '8859/1', 'ISO IR6', 'ISO IR100' => 'ISO-8859-1',
            'UNICODE UTF-8', 'UNICODE UTF8', 'UTF-8' => 'UTF-8',
            default => strtoupper(trim((string) $msh18)),
        };
    }
}

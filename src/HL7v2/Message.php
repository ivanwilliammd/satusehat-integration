<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

use InvalidArgumentException;

/**
 * An HL7 v2 message: an ordered list of segments, addressed by name and position.
 *
 *   $msg = Message::parse($er7);
 *   $msg->get('PID.5.1');          // patient family name
 *   $msg->get('OBX.5');            // first repetition of OBX-5
 *   $msg->set('PID.8', 'F');       // writes back with escaping
 *   echo $msg->toString();         // ER7, CR-separated
 */
final class Message
{
    /** @var list<Segment> */
    private array $segments = [];

    private string $separator = '|';

    private function __construct()
    {
    }

    public static function parse(string $er7): self
    {
        $er7 = trim($er7, "\r\n\x0b\x1c");
        if ($er7 === '') {
            throw new InvalidArgumentException('Pesan HL7 kosong.');
        }

        $lines = preg_split('/\r\n|\r|\n/', $er7) ?: [];
        $msg = new self();
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $separator = $line[3] ?? '|';
            $parts = explode($separator, $line);
            if (($parts[0] ?? '') === '' ) {
                continue;
            }
            if ($parts[0] === 'MSH') {
                $msg->separator = $separator;
            }
            $msg->segments[] = Segment::fromParts($parts, $separator);
        }

        if ($msg->segments === [] || $msg->segments[0]->name !== 'MSH') {
            throw new InvalidArgumentException('Pesan HL7 harus dimulai dengan segmen MSH.');
        }

        return $msg;
    }

    /** Wraps an existing list of segments, e.g. when building an ACK. */
    public static function fromSegments(array $segments, string $separator = '|'): self
    {
        $msg = new self();
        $msg->segments = array_values($segments);
        $msg->separator = $separator;

        return $msg;
    }

    /** @return list<Segment> */
    public function segments(): array
    {
        return $this->segments;
    }

    public function segment(string $name, int $occurrence = 1): ?Segment
    {
        $seen = 0;
        foreach ($this->segments as $s) {
            if ($s->name === $name && ++$seen === $occurrence) {
                return $s;
            }
        }

        return null;
    }

    /** @return list<Segment> */
    public function all(string $name): array
    {
        return array_values(array_filter($this->segments, static fn (Segment $s): bool => $s->name === $name));
    }

    /**
     * Path is "SEG.n" or "SEG.n.c" or "SEG.n.c.s". The first segment of that name is used; for
     * repeating segments (OBX, NK1) call segment($name, $n) first.
     */
    public function get(string $path): string
    {
        [$name, $rest] = array_pad(explode('.', $path, 2), 2, '');
        $segment = $this->segment($name);
        if (!$segment instanceof Segment || $rest === '') {
            return '';
        }

        return $segment->get($rest);
    }

    public function set(string $path, string $value): self
    {
        [$name, $rest] = array_pad(explode('.', $path, 2), 2, '');
        $segment = $this->segment($name);
        if ($segment instanceof Segment && $rest !== '') {
            $segment->set($rest, $value);
        }

        return $this;
    }

    public function messageType(): string
    {
        return $this->get('MSH.9.1');
    }

    public function triggerEvent(): string
    {
        return $this->get('MSH.9.2');
    }

    public function controlId(): string
    {
        return $this->get('MSH.10');
    }

    public function version(): string
    {
        return $this->get('MSH.12.1');
    }

    public function charset(): string
    {
        return Encoding::charsetOf($this->segment('MSH')?->field(18));
    }

    public function append(Segment $segment): self
    {
        $this->segments[] = $segment;

        return $this;
    }

    /**
     * ER7 always ends with a carriage return after the last segment; senders and receivers both
     * rely on it, and dropping it makes a file-diff of two identical messages look different.
     */
    public function toString(): string
    {
        $body = implode("\r", array_map(static fn (Segment $s): string => (string) $s, $this->segments));

        return $body === '' ? '' : $body . "\r";
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

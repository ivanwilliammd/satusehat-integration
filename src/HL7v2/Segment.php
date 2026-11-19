<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/**
 * One HL7 v2 segment. Fields are kept as raw text and split on demand, so a message round-trips
 * byte-for-byte even when it contains repetitions or subcomponents this SDK does not model.
 */
final class Segment
{
    /** @var array<int,string> 1-based, index 0 unused (HL7 numbers fields from 1) */
    private array $fields = [];

    /** @param array<int,string> $fields */
    public function __construct(public string $name, array $fields = [], private string $fieldSeparator = '|')
    {
        $this->fields = $fields;
    }

    /**
     * Builds a segment from already-split fields. MSH is special: MSH-1 is the field separator
     * itself and MSH-2 holds the encoding characters, so its split starts at index 2.
     *
     * @param array<int,string> $parts
     */
    public static function fromParts(array $parts, string $separator = '|'): self
    {
        $name = array_shift($parts) ?? '';
        $offset = $name === 'MSH' ? 1 : 0; // MSH.1 is the separator, not a field in `parts`
        $fields = [];
        foreach ($parts as $i => $value) {
            $fields[$i + 1 + $offset] = $value;
        }
        if ($name === 'MSH') {
            $fields[1] = $separator;
        }

        return new self($name, $fields, $separator);
    }

    public function field(int $n): string
    {
        return $this->fields[$n] ?? '';
    }

    /**
     * Reads a path like "5.1" (field 5, component 1) or "3.1.2" (subcomponent). Returns the first
     * repetition; use fieldAll() when repetitions matter (identifiers, phone numbers).
     */
    public function get(string $path): string
    {
        $parts = explode('.', $path);
        $field = $this->firstRepetition($this->field((int) array_shift($parts)));
        if ($parts === []) {
            return Encoding::decode($field);
        }

        $components = explode('^', $field);
        $value = $components[(int) $parts[0] - 1] ?? '';
        if (count($parts) > 1) {
            $subs = explode('&', $value);
            $value = $subs[(int) $parts[1] - 1] ?? '';
        }

        return Encoding::decode($value);
    }

    /** @return list<string> */
    public function fieldAll(int $n): array
    {
        $raw = $this->field($n);

        return $raw === '' ? [] : array_map(static fn (string $v): string => Encoding::decode($v), explode('~', $raw));
    }

    public function set(string $path, string $value): self
    {
        $parts = explode('.', $path);
        $n = (int) array_shift($parts);
        if ($parts === []) {
            $this->fields[$n] = Encoding::encode($value);

            return $this;
        }

        $reps = $this->field($n) === '' ? [''] : explode('~', $this->field($n));
        $components = explode('^', $reps[0]);
        $idx = (int) $parts[0] - 1;
        $components[$idx] = Encoding::encode($value);
        $reps[0] = implode('^', $components);
        $this->fields[$n] = implode('~', $reps);

        return $this;
    }

    public function __toString(): string
    {
        $max = $this->fields === [] ? 0 : max(array_keys($this->fields));
        // MSH keeps its separator inside field 1, so rendering starts at field 2 to avoid "MSH|||".
        $first = $this->name === 'MSH' ? 2 : 1;
        $out = [$this->name];
        for ($i = $first; $i <= $max; $i++) {
            $out[] = $this->fields[$i] ?? '';
        }
        // Trailing empty fields carry no information and only add noise to logs.
        while (count($out) > 2 && end($out) === '') {
            array_pop($out);
        }

        return implode($this->fieldSeparator, $out);
    }

    private function firstRepetition(string $value): string
    {
        $pos = strpos($value, '~');

        return $pos === false ? $value : substr($value, 0, $pos);
    }

}

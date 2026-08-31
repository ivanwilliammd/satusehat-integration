<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

// PHP 7 polyfills for str_contains / str_starts_with
if (!function_exists('Satusehat\Integration\Builder\str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('Satusehat\Integration\Builder\str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) === 0;
    }
}

abstract class Builder
{
    protected array $data = [];

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function set(string $key, $value): void
    {
        if (str_contains($key, '/')) {
            $keys = explode('/', $key);
            $ref = &$this->data;
            foreach (array_slice($keys, 0, -1) as $k) {
                if (!isset($ref[$k]) || !is_array($ref[$k])) {
                    $ref[$k] = [];
                }
                $ref = &$ref[$k];
            }
            $ref[array_slice($keys, -1)[0]] = $value;
            return;
        }
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    protected function push(string $key, $value): void
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = [];
        }
        $this->data[$key][] = $value;
    }

    /**
     * Set a CodeableConcept field with terminology casting support.
     * Accepts: CodeableConcept object, "System:Code" string (e.g. "ICD10:A00", "LOINC:2951-2"),
     *          "plain text" string, or raw array.
     */
    protected function setCodeable(string $key, $value, ?string $defaultSystem = null, ?string $defaultDisplay = null): void
    {
        if (is_string($value) && !str_contains($value, ':')) {
            // plain text without system prefix → free-text CodeableConcept, or default system if provided
            if ($defaultSystem !== null) {
                $this->set($key, [
                    'coding' => [['system' => $defaultSystem, 'code' => $value, 'display' => $defaultDisplay ?? $value]],
                ]);
                return;
            }
            $this->set($key, ['text' => $value]);
            return;
        }
        if (is_string($value) && str_contains($value, ':')) {
            // "System:Code" notation — use the Resolver
            try {
                $resolved = \Satusehat\Integration\Terminology\Resolver::resolve($value);
                $this->set($key, $resolved->toArray());
                return;
            } catch (\Throwable $e) {
                // Resolver unavailable or failed — fall back to text
                $this->set($key, ['text' => $value]);
                return;
            }
        }
        if (is_array($value)) {
            $this->set($key, $value);
            return;
        }
        // CodeableConcept object or other — delegate to toArray if available
        if (is_object($value) && method_exists($value, 'toArray')) {
            $this->set($key, $value->toArray());
            return;
        }
        $this->set($key, ['text' => (string) $value]);
    }

    protected function merge(array $arr): void
    {
        $this->data = array_merge($this->data, $arr);
    }

    public function build(): array
    {
        return array_filter($this->data, fn($v) => $v !== null && $v !== []);
    }

    /**
     * Return JSON-serializable array. Override in subclass for validation.
     * @return array<string, mixed>
     * @throws \RuntimeException
     */
    public function json(): array
    {
        return $this->build();
    }
}

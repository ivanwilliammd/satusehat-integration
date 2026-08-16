<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

abstract class Builder
{
    protected array $data = [];

    protected function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    protected function push(string $key, mixed $value): void
    {
        if (!isset($this->data[$key])) $this->data[$key] = [];
        $this->data[$key][] = $value;
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

<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

abstract class DataType
{
    public function toArray(): array
    {
        return array_filter(
            $this->toArrayRecursive(get_object_vars($this)),
            fn($v) => $v !== null && $v !== []
        );
    }

    /**
     * Recursively convert nested DataType objects to arrays.
     * @param mixed $value
     * @return mixed
     */
    protected function toArrayRecursive($value)
    {
        if ($value === null || is_scalar($value)) {
            return $value;
        }

        if (is_array($value)) {
            return array_map(fn($v) => $this->toArrayRecursive($v), $value);
        }

        if ($value instanceof DataType) {
            return $value->toArray();
        }

        return $value;
    }

    protected function bool(?bool $val): ?bool { return $val; }
    protected function str(?string $val): ?string { return $val; }
    protected function int(?int $val): ?int { return $val; }
    protected function float(?float $val): ?float { return $val; }
    protected function dt(?string $val): ?string { return $val; }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

abstract class DataType
{
    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($v) => $v !== null && $v !== []);
    }

    protected function bool(?bool $val): ?bool { return $val; }
    protected function str(?string $val): ?string { return $val; }
    protected function int(?int $val): ?int { return $val; }
    protected function float(?float $val): ?float { return $val; }
    protected function dt(?string $val): ?string { return $val; }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Period extends DataType
{
    public ?string $start = null; // dateTime
    public ?string $end = null;   // dateTime

    public function __construct(?string $start = null, ?string $end = null)
    {
        $this->start = $this->dt($start);
        $this->end = $this->dt($end);
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($v) => $v !== null && $v !== []);
    }
}

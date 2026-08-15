<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Quantity extends DataType
{
    public ?float $value = null;
    public ?string $comparator = null;
    public ?string $unit = null;
    public ?string $system = null;
    public ?string $code = null;

    public function __construct(
        ?float $value = null,
        ?string $unit = null,
        ?string $system = null,
        ?string $code = null,
        ?string $comparator = null
    ) {
        $this->value = $value;
        $this->comparator = $comparator;
        $this->unit = $unit;
        $this->system = $system;
        $this->code = $code;
    }
}

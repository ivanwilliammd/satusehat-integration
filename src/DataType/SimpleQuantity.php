<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class SimpleQuantity extends Quantity
{
    public function __construct(
        ?float $value = null,
        ?string $unit = null,
        ?string $system = null,
        ?string $code = null
    ) {
        parent::__construct($value, $unit, $system, $code, null);
    }
}

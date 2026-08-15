<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property float|null $value
 * @property string|null $comparator
 * @property string|null $unit
 * @property string|null $system
 * @property string|null $code
 */
class SimpleQuantity extends Quantity
{
    public function __construct(
        ?float $value = null,
        ?string $unit = null,
        ?string $system = null,
        ?string $code = null
    ) {
        $this->value = $value;
        $this->unit = $unit;
        $this->system = $system;
        $this->code = $code;
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property Quantity|null $low
 * @property Quantity|null $high
 */
class Range extends DataType
{
    public ?Quantity $low = null;
    public ?Quantity $high = null;

    public function __construct(?Quantity $low = null, ?Quantity $high = null)
    {
        $this->low = $low;
        $this->high = $high;
    }
}

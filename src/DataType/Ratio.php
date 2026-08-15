<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property Quantity|null $numerator
 * @property Quantity|null $denominator
 */
class Ratio extends DataType
{
    public ?Quantity $numerator = null;
    public ?Quantity $denominator = null;

    public function __construct(?Quantity $numerator = null, ?Quantity $denominator = null)
    {
        $this->numerator = $numerator;
        $this->denominator = $denominator;
    }
}

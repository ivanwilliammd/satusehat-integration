<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Range extends DataType
{
    public ?SimpleQuantity $low = null;
    public ?SimpleQuantity $high = null;

    public function __construct(?SimpleQuantity $low = null, ?SimpleQuantity $high = null)
    {
        $this->low = $low;
        $this->high = $high;
    }
}

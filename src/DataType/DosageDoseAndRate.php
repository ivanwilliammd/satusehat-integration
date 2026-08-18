<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class DosageDoseAndRate extends DataType
{
    public ?CodeableConcept $type = null;
    /** @var Range|SimpleQuantity|null */
    public $dose = null;
    /** @var Range|Ratio|SimpleQuantity|null */
    public $rate = null;

    public function setType(CodeableConcept $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setDose($dose): self
    {
        $this->dose = $dose;
        return $this;
    }

    public function setRate($rate): self
    {
        $this->rate = $rate;
        return $this;
    }
}

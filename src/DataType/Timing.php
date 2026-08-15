<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Timing extends DataType
{
    /** @var string[] */
    public array $event = [];
    public ?TimingRepeat $repeat = null;
    public ?CodeableConcept $code = null;

    public function __construct()
    {
    }

    public function addEvent(string $event): static
    {
        $this->event[] = $event;
        return $this;
    }

    public function setRepeat(TimingRepeat $repeat): static
    {
        $this->repeat = $repeat;
        return $this;
    }

    public function setCode(CodeableConcept $code): static
    {
        $this->code = $code;
        return $this;
    }
}

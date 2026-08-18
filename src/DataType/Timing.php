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

    public function addEvent(string $event): self
    {
        $this->event[] = $event;
        return $this;
    }

    public function setRepeat(TimingRepeat $repeat): self
    {
        $this->repeat = $repeat;
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->code = $code;
        return $this;
    }
}

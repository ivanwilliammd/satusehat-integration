<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Dosage extends DataType
{
    public ?int $sequence = null;
    public ?string $text = null;
    public ?Timing $timing = null;
    /** @var bool|null */
    public $asNeeded = null;
    public ?CodeableConcept $site = null;
    public ?CodeableConcept $route = null;
    public ?CodeableConcept $method = null;
    /** @var DosageDoseAndRate[] */
    public array $doseAndRate = [];

    public function __construct()
    {
    }

    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;
        return $this;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function setTiming(Timing $timing): self
    {
        $this->timing = $timing;
        return $this;
    }

    public function setAsNeeded(bool $asNeeded): self
    {
        $this->asNeeded = $asNeeded;
        return $this;
    }

    public function setSite(CodeableConcept $site): self
    {
        $this->site = $site;
        return $this;
    }

    public function setRoute(CodeableConcept $route): self
    {
        $this->route = $route;
        return $this;
    }

    public function setMethod(CodeableConcept $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function addDoseAndRate(DosageDoseAndRate $doseAndRate): self
    {
        $this->doseAndRate[] = $doseAndRate;
        return $this;
    }
}

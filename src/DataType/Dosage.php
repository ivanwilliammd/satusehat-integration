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

    public function setSequence(int $sequence): static
    {
        $this->sequence = $sequence;
        return $this;
    }

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function setTiming(Timing $timing): static
    {
        $this->timing = $timing;
        return $this;
    }

    public function setAsNeeded(bool $asNeeded): static
    {
        $this->asNeeded = $asNeeded;
        return $this;
    }

    public function setSite(CodeableConcept $site): static
    {
        $this->site = $site;
        return $this;
    }

    public function setRoute(CodeableConcept $route): static
    {
        $this->route = $route;
        return $this;
    }

    public function setMethod(CodeableConcept $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function addDoseAndRate(DosageDoseAndRate $doseAndRate): static
    {
        $this->doseAndRate[] = $doseAndRate;
        return $this;
    }
}

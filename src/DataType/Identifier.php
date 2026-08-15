<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Identifier extends DataType
{
    public ?string $use = null;
    public ?CodeableConcept $type = null;
    public ?string $system = null;
    public ?string $value = null;
    public ?Period $period = null;
    public ?Reference $assigner = null;

    public function __construct(
        ?string $system = null,
        ?string $value = null,
        ?string $use = null,
        ?CodeableConcept $type = null,
        ?Period $period = null,
        ?Reference $assigner = null
    ) {
        $this->system = $this->str($system);
        $this->value = $this->str($value);
        $this->use = $this->str($use);
        $this->type = $type;
        $this->period = $period;
        $this->assigner = $assigner;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        if (isset($data['type']) && $data['type'] instanceof CodeableConcept) {
            $data['type'] = $data['type']->toArray();
        }
        if (isset($data['period']) && $data['period'] instanceof Period) {
            $data['period'] = $data['period']->toArray();
        }
        if (isset($data['assigner']) && $data['assigner'] instanceof Reference) {
            $data['assigner'] = $data['assigner']->toArray();
        }

        return array_filter($data, fn($v) => $v !== null && $v !== []);
    }
}

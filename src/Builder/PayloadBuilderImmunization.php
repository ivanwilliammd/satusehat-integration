<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class PayloadBuilderImmunization extends Builder
{
    protected string $resourceType = 'Immunization';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setVaccineCode(CodeableConcept $vaccineCode): self
    {
        $this->set('vaccineCode', $vaccineCode->toArray());
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setOccurrenceDateTime(string $dateTime): self
    {
        $this->set('occurrenceDateTime', $dateTime);
        return $this;
    }

    public function addPerformer(Reference $actor, CodeableConcept $function): self
    {
        $this->push('performer', [
            'actor' => $actor->toArray(),
            'function' => $function->toArray(),
        ]);
        return $this;
    }
}

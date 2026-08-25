<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

class Immunization extends Element
{
    public array $data = ['resourceType' => 'Immunization'];

    public function setStatus(string $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setVaccineCode(array $coding): self
    {
        $this->data['vaccineCode']['coding'] = $coding;
        return $this;
    }

    public function setPatient(array $reference): self
    {
        $this->data['patient'] = $reference;
        return $this;
    }

    public function setOccurrenceDateTime(string $dateTime): self
    {
        $this->data['occurrenceDateTime'] = $dateTime;
        return $this;
    }

    public function addPerformer(array $actor, array $function): self
    {
        $this->data['performer'][] = [
            'actor' => $actor,
            'function' => $function,
        ];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

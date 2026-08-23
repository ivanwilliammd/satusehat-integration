<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

class MedicationAdministration extends Element
{
    public array $data = ['resourceType' => 'MedicationAdministration'];

    public function setStatus(string $s): self
    {
        $this->data['status'] = $s;
        return $this;
    }

    public function setMedicationReference(string $reference): self
    {
        $this->data['medicationReference']['reference'] = $reference;
        return $this;
    }

    public function setSubject(string $subjectId): self
    {
        $this->data['subject']['reference'] = 'Patient/' . $subjectId;
        return $this;
    }

    public function setContext(string $contextId): self
    {
        $this->data['context']['reference'] = 'Encounter/' . $contextId;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

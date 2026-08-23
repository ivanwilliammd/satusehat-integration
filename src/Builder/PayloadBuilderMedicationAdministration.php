<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

class PayloadBuilderMedicationAdministration extends Builder
{
    protected string $resourceType = 'MedicationAdministration';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setStatus(string $s): self
    {
        $this->set('status', $s);
        return $this;
    }

    public function setMedicationReference(string $reference): self
    {
        $this->set('medicationReference/reference', $reference);
        return $this;
    }

    public function setSubject(string $subjectId): self
    {
        $this->set('subject/reference', 'Patient/' . $subjectId);
        return $this;
    }

    public function setContext(string $contextId): self
    {
        $this->set('context/reference', 'Encounter/' . $contextId);
        return $this;
    }
}

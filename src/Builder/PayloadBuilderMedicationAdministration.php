<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

/**
 * MedicationAdministration FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medicationadministration.html
 */
class PayloadBuilderMedicationAdministration extends Builder
{
    protected string $resourceType = 'MedicationAdministration';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setMedicationReference(Reference $medicationReference): self
    {
        $this->set('medicationReference', $medicationReference->toArray());
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setContext(Reference $context): self
    {
        $this->set('context', $context->toArray());
        return $this;
    }

    public function setEffectivePeriod(Period $effectivePeriod): self
    {
        $this->set('effectivePeriod', $effectivePeriod->toArray());
        return $this;
    }

    public function addPerformer(Reference $performer): self
    {
        $this->push('performer', [
            'actor' => $performer->toArray(),
        ]);
        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode): self
    {
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function setRequest(Reference $request): self
    {
        $this->set('request', $request->toArray());
        return $this;
    }

    public function setDosage(
        Quantity $dose,
        CodeableConcept $route,
        CodeableConcept $site
    ): self {
        $this->set('dosage', [
            'dose' => $dose->toArray(),
            'route' => $route->toArray(),
            'site' => $site->toArray(),
        ]);
        return $this;
    }

    public function addContained(array $resource): self
    {
        $this->push('contained', $resource);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

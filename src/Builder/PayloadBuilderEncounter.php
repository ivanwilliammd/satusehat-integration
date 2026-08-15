<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * Encounter FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/encounter.html
 */
class PayloadBuilderEncounter extends Builder
{
    protected string $resourceType = 'Encounter';

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

    public function setClass(Coding $class): self
    {
        $this->set('class', $class->toArray());
        return $this;
    }

    public function addType(CodeableConcept $type): self
    {
        $this->push('type', $type->toArray());
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function addParticipant(Reference $individual, ?CodeableConcept $type = null, ?Period $period = null): self
    {
        $participant = ['individual' => $individual->toArray()];

        if ($type !== null) {
            $participant['type'] = [$type->toArray()];
        }

        if ($period !== null) {
            $participant['period'] = $period->toArray();
        }

        $this->push('participant', $participant);
        return $this;
    }

    public function addLocation(Reference $location, ?string $status = null, ?CodeableConcept $physicalType = null): self
    {
        $locationData = ['location' => $location->toArray()];

        if ($status !== null) {
            $locationData['status'] = $status;
        }

        if ($physicalType !== null) {
            $locationData['physicalType'] = $physicalType->toArray();
        }

        $this->push('location', $locationData);
        return $this;
    }

    public function setPeriod(Period $period): self
    {
        $this->set('period', $period->toArray());
        return $this;
    }

    public function setServiceProvider(Reference $serviceProvider): self
    {
        $this->set('serviceProvider', $serviceProvider->toArray());
        return $this;
    }

    public function addDiagnosis(
        Reference $condition,
        ?int $rank = null,
        ?CodeableConcept $use = null,
        ?CodeableConcept $role = null
    ): self {
        $diagnosis = ['condition' => $condition->toArray()];

        if ($rank !== null) {
            $diagnosis['rank'] = $rank;
        }

        if ($use !== null) {
            $diagnosis['use'] = $use->toArray();
        }

        if ($role !== null) {
            $diagnosis['role'] = $role->toArray();
        }

        $this->push('diagnosis', $diagnosis);
        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode): self
    {
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function addReasonReference(Reference $reasonReference): self
    {
        $this->push('reasonReference', $reasonReference->toArray());
        return $this;
    }

    public function addExtension(string $url, mixed $value, ?string $valueType = null): self
    {
        $extension = ['url' => $url];

        if ($valueType !== null) {
            $extension['value' . ucfirst($valueType)] = $value;
        } else {
            $extension['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('extension', $extension);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

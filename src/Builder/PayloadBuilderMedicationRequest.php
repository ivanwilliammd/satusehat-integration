<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Dosage;
use Satusehat\Integration\DataType\Duration;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

/**
 * MedicationRequest FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medicationrequest.html
 */
class PayloadBuilderMedicationRequest extends Builder
{
    protected string $resourceType = 'MedicationRequest';

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

    public function setIntent(string $intent): self
    {
        $this->set('intent', $intent);
        return $this;
    }

    public function addCategory(CodeableConcept $category): self
    {
        $this->push('category', $category->toArray());
        return $this;
    }

    // medication[x] polymorphic setters
    public function setMedicationCodeableConcept(CodeableConcept $medication): self
    {
        $this->set('medicationCodeableConcept', $medication->toArray());
        return $this;
    }

    public function setMedicationReference(Reference $medication): self
    {
        $this->set('medicationReference', $medication->toArray());
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setEncounter(Reference $encounter): self
    {
        $this->set('encounter', $encounter->toArray());
        return $this;
    }

    public function setAuthoredOn(string $authoredOn): self
    {
        $this->set('authoredOn', $authoredOn);
        return $this;
    }

    public function setRequester(Reference $requester): self
    {
        $this->set('requester', $requester->toArray());
        return $this;
    }

    public function setRecorder(Reference $recorder): self
    {
        $this->set('recorder', $recorder->toArray());
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

    public function addDosageInstruction(Dosage $dosage): self
    {
        $this->push('dosageInstruction', $dosage->toArray());
        return $this;
    }

    public function setDispenseRequest(
        ?Reference $validatPeriod = null,
        ?int $numberOfRepeatsAllowed = null,
        ?Quantity $quantity = null,
        ?Duration $expectedSupplyDuration = null,
        ?CodeableConcept $performer = null
    ): self {
        $dispenseRequest = [];

        if ($validatPeriod !== null) {
            $dispenseRequest['validityPeriod'] = $validatPeriod->toArray();
        }

        if ($numberOfRepeatsAllowed !== null) {
            $dispenseRequest['numberOfRepeatsAllowed'] = $numberOfRepeatsAllowed;
        }

        if ($quantity !== null) {
            $dispenseRequest['quantity'] = $quantity->toArray();
        }

        if ($expectedSupplyDuration !== null) {
            $dispenseRequest['expectedSupplyDuration'] = $expectedSupplyDuration->toArray();
        }

        if ($performer !== null) {
            $dispenseRequest['performer'] = $performer->toArray();
        }

        $this->set('dispenseRequest', array_filter($dispenseRequest, fn($v) => $v !== null));
        return $this;
    }

    public function setDispenseInterval(Duration $dispenseInterval): self
    {
        $dispenseRequest = $this->data['dispenseRequest'] ?? [];
        $dispenseRequest['dispenseInterval'] = $dispenseInterval->toArray();
        $this->set('dispenseRequest', $dispenseRequest);
        return $this;
    }

    public function setInitialFill(Quantity $initialFill): self
    {
        $dispenseRequest = $this->data['dispenseRequest'] ?? [];
        $dispenseRequest['initialFill'] = $initialFill->toArray();
        $this->set('dispenseRequest', $dispenseRequest);
        return $this;
    }

    public function setSubstitution(CodeableConcept $substitution, ?bool $allowed = null): self
    {
        $substitutionData = ['code' => $substitution->toArray()];

        if ($allowed !== null) {
            $substitutionData['allowedBoolean'] = $allowed;
        }

        $this->set('substitution', $substitutionData);
        return $this;
    }

    public function addPriorPrescription(Reference $priorPrescription): self
    {
        $this->push('priorPrescription', $priorPrescription->toArray());
        return $this;
    }

    public function addDetectedIssue(Reference $detectedIssue): self
    {
        $this->push('detectedIssue', $detectedIssue->toArray());
        return $this;
    }

    public function addEventHistory(Reference $eventHistory): self
    {
        $this->push('eventHistory', $eventHistory->toArray());
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

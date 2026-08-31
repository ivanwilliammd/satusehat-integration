<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

/**
 * MedicationStatement FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medicationstatement.html
 */
class PayloadBuilderMedicationStatement extends Builder
{
    protected string $resourceType = 'MedicationStatement';

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

    public function addStatusReason(CodeableConcept $statusReason): self
    {
        $this->push('statusReason', $statusReason->toArray());
        return $this;
    }

    public function setCategory(CodeableConcept $category): self
    {
        $this->set('category', $category->toArray());
        return $this;
    }

    public function setMedicationCodeableConcept(CodeableConcept $medication): self
    {
        $this->set('medicationCodeableConcept', $medication->toArray());
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

    public function setDateAsserted(string $dateAsserted): self
    {
        $this->set('dateAsserted', $dateAsserted);
        return $this;
    }

    public function setEffectiveDateTime(string $effectiveDateTime): self
    {
        $this->set('effectiveDateTime', $effectiveDateTime);
        return $this;
    }

    public function setEffectivePeriod(Period $effectivePeriod): self
    {
        $this->set('effectivePeriod', $effectivePeriod->toArray());
        return $this;
    }

    public function setInformationSource(Reference $informationSource): self
    {
        $this->set('informationSource', $informationSource->toArray());
        return $this;
    }

    public function setDerivedFrom(string $derivedFrom): self
    {
        $this->push('derivedFrom', ['reference' => $derivedFrom]);
        return $this;
    }

    public function setReasonCode(CodeableConcept $reasonCode): self
    {
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function setReasonReference(Reference $reasonReference): self
    {
        $this->push('reasonReference', $reasonReference->toArray());
        return $this;
    }

    public function addNote(string $text): self
    {
        $this->push('note', ['text' => $text]);
        return $this;
    }

    public function addDosageInstruction(
        ?string $text = null,
        ?int $sequence = null,
        ?CodeableConcept $timingCode = null,
        ?Period $timingPeriod = null,
        ?Quantity $doseQuantity = null,
        ?Range $doseRange = null,
        ?string $route = null,
        ?CodeableConcept $routeCode = null
    ): self {
        $dosage = [];

        if ($text !== null) {
            $dosage['text'] = $text;
        }

        if ($sequence !== null) {
            $dosage['sequence'] = $sequence;
        }

        if ($timingCode !== null) {
            $dosage['timing']['code'] = $timingCode->toArray();
        }

        if ($timingPeriod !== null) {
            $dosage['timing']['repeat']['boundsPeriod'] = $timingPeriod->toArray();
        }

        if ($doseQuantity !== null) {
            $dosage['doseAndRate'][0]['doseQuantity'] = $doseQuantity->toArray();
        }

        if ($doseRange !== null) {
            $dosage['doseAndRate'][0]['doseRange'] = $doseRange->toArray();
        }

        if ($route !== null) {
            $dosage['route'] = ['text' => $route];
        }

        if ($routeCode !== null) {
            $dosage['route'] = $routeCode->toArray();
        }

        $this->push('dosage', $dosage);
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

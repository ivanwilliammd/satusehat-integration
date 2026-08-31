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

    public function addStatusReason(string $code, ?string $display = null, ?string $system = null): self
    {
        $concept = [
            'coding' => [['code' => $code, 'display' => $display ?? $code]],
        ];
        if ($system !== null) {
            $concept['coding'][0]['system'] = $system;
        }
        $this->push('statusReason', $concept);
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

    public function setMedicationReference(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Medication/' . $reference;
        }
        $this->set('medicationReference', array_filter([
            'reference' => $reference,
            'display' => $display,
        ], fn($v) => $v !== null));
        return $this;
    }

    public function setSubject(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Patient/' . $reference;
        }
        $this->set('subject', array_filter([
            'reference' => $reference,
            'display' => $display,
        ], fn($v) => $v !== null));
        return $this;
    }

    public function setContext(string $reference, ?string $display = null): self
    {
        if (strpos($reference, '/') === false) {
            $reference = 'Encounter/' . $reference;
        }
        $this->set('context', array_filter([
            'reference' => $reference,
            'display' => $display,
        ], fn($v) => $v !== null));
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

    public function setInformationSource(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Patient/' . $reference;
        }
        $this->set('informationSource', array_filter([
            'reference' => $reference,
            'display' => $display,
        ], fn($v) => $v !== null));
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

    public function addContained(array $resource): self
    {
        $this->push('contained', $resource);
        return $this;
    }

    public function addDosageInstruction(
        ?string $text = null,
        ?int $frequency = null,
        ?int $period = null,
        ?string $periodUnit = null
    ): self {
        $dosage = [];

        if ($text !== null) {
            $dosage['text'] = $text;
        }
        if ($frequency !== null) {
            $dosage['sequence'] = $frequency;
            $dosage['timing']['repeat']['frequency'] = $frequency;
        }
        if ($period !== null) {
            $dosage['timing']['repeat']['period'] = $period;
        }
        if ($periodUnit !== null) {
            $dosage['timing']['repeat']['periodUnit'] = $periodUnit;
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

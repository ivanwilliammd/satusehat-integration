<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * FamilyMemberHistory FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/familymemberhistory.html
 */
class PayloadBuilderFamilyMemberHistory extends Builder
{
    protected string $resourceType = 'FamilyMemberHistory';

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

    public function setInstantiatesCanonical(string $instantiatesCanonical): self
    {
        $this->set('instantiatesCanonical', $instantiatesCanonical);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $validStatuses = ['partial', 'completed', 'entered-in-error', 'health-unknown'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status: ' . $status);
        }
        $this->set('status', $status);
        return $this;
    }

    public function setDataAbsentReason(CodeableConcept $dataAbsentReason): self
    {
        $this->set('dataAbsentReason', $dataAbsentReason->toArray());
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setDate(string $dateTime): self
    {
        $this->set('date', $dateTime);
        return $this;
    }

    public function setName(?string $name): self
    {
        if ($name !== null) {
            $this->set('name', $name);
        }
        return $this;
    }

    public function setRelationship(CodeableConcept $relationship): self
    {
        $this->set('relationship', $relationship->toArray());
        return $this;
    }

    public function setSex(CodeableConcept $sex): self
    {
        $this->set('sex', $sex->toArray());
        return $this;
    }

    public function setBornPeriod(CodeableConcept $bornPeriod): self
    {
        $this->set('bornPeriod', $bornPeriod->toArray());
        return $this;
    }

    public function setBornDate(string $bornDate): self
    {
        $this->set('bornDate', $bornDate);
        return $this;
    }

    public function setBornString(string $bornString): self
    {
        $this->set('bornString', $bornString);
        return $this;
    }

    public function setAgeAge(int $value, string $unit): self
    {
        $this->set('ageAge', $value);
        $this->set('ageUnit', $unit);
        return $this;
    }

    public function setAgeRange(int $value, string $low, string $high): self
    {
        $this->set('ageRange', [
            'value' => $value,
            'low' => ['value' => $low],
            'high' => ['value' => $high],
        ]);
        return $this;
    }

    public function setAgeString(string $ageString): self
    {
        $this->set('ageString', $ageString);
        return $this;
    }

    public function setDeceasedBoolean(bool $deceased): self
    {
        $this->set('deceasedBoolean', $deceased);
        return $this;
    }

    public function setDeceasedAge(int $value, string $unit): self
    {
        $this->set('deceasedAge', $value);
        $this->set('deceasedAgeUnit', $unit);
        return $this;
    }

    public function setDeceasedRange(int $value, string $low, string $high): self
    {
        $this->set('deceasedRange', [
            'value' => $value,
            'low' => ['value' => $low],
            'high' => ['value' => $high],
        ]);
        return $this;
    }

    public function setDeceasedDate(string $deceasedDate): self
    {
        $this->set('deceasedDate', $deceasedDate);
        return $this;
    }

    public function setDeceasedString(string $deceasedString): self
    {
        $this->set('deceasedString', $deceasedString);
        return $this;
    }

    public function setDeceasedCodeableConcept(CodeableConcept $deceased): self
    {
        $this->set('deceasedCodeableConcept', $deceased->toArray());
        return $this;
    }

    public function setReasonCode(CodeableConcept $reasonCode): self
    {
        $this->set('reasonCode', $reasonCode->toArray());
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

    public function setNote(string $note): self
    {
        $this->push('note', ['text' => $note]);
        return $this;
    }

    public function addCondition(
        CodeableConcept $code,
        ?string $onsetString = null,
        ?CodeableConcept $outcome = null,
        ?bool $contributedToDeath = null
    ): self {
        $condition = [
            'code' => $code->toArray(),
        ];

        if ($onsetString !== null) {
            $condition['onsetString'] = $onsetString;
        }

        if ($outcome !== null) {
            $condition['outcome'] = $outcome->toArray();
        }

        if ($contributedToDeath !== null) {
            $condition['contributedToDeath'] = $contributedToDeath;
        }

        $this->push('condition', $condition);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

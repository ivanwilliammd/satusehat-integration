<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

/**
 * Condition FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/condition.html
 */
class PayloadBuilderCondition extends Builder
{
    protected string $resourceType = 'Condition';

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

    public function setClinicalStatus(CodeableConcept $clinicalStatus): self
    {
        $this->set('clinicalStatus', $clinicalStatus->toArray());
        return $this;
    }

    public function setVerificationStatus(CodeableConcept $verificationStatus): self
    {
        $this->set('verificationStatus', $verificationStatus->toArray());
        return $this;
    }

    public function addCategory(CodeableConcept $category): self
    {
        $this->push('category', $category->toArray());
        return $this;
    }

    public function setSeverity(CodeableConcept $severity): self
    {
        $this->set('severity', $severity->toArray());
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
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

    // onset[x] polymorphic setters
    public function setOnsetDateTime(string $dateTime): self
    {
        $this->set('onsetDateTime', $dateTime);
        return $this;
    }

    public function setOnsetAge(Range $age): self
    {
        $this->set('onsetAge', $age->toArray());
        return $this;
    }

    public function setOnsetPeriod(Period $period): self
    {
        $this->set('onsetPeriod', $period->toArray());
        return $this;
    }

    public function setOnsetRange(Range $range): self
    {
        $this->set('onsetRange', $range->toArray());
        return $this;
    }

    public function setOnsetString(string $onsetString): self
    {
        $this->set('onsetString', $onsetString);
        return $this;
    }

    // abatement[x] polymorphic setters
    public function setAbatementDateTime(string $dateTime): self
    {
        $this->set('abatementDateTime', $dateTime);
        return $this;
    }

    public function setAbatementAge(Range $age): self
    {
        $this->set('abatementAge', $age->toArray());
        return $this;
    }

    public function setAbatementPeriod(Period $period): self
    {
        $this->set('abatementPeriod', $period->toArray());
        return $this;
    }

    public function setAbatementRange(Range $range): self
    {
        $this->set('abatementRange', $range->toArray());
        return $this;
    }

    public function setAbatementString(string $abatementString): self
    {
        $this->set('abatementString', $abatementString);
        return $this;
    }

    public function setRecordedDate(string $recordedDate): self
    {
        $this->set('recordedDate', $recordedDate);
        return $this;
    }

    public function setRecorder(Reference $recorder): self
    {
        $this->set('recorder', $recorder->toArray());
        return $this;
    }

    public function setAsserter(Reference $asserter): self
    {
        $this->set('asserter', $asserter->toArray());
        return $this;
    }

    public function addStage(CodeableConcept $summary, ?Reference $assessment = null): self
    {
        $stage = ['summary' => $summary->toArray()];

        if ($assessment !== null) {
            $stage['assessment'] = [$assessment->toArray()];
        }

        $this->push('stage', $stage);
        return $this;
    }

    public function addEvidence(CodeableConcept $code, ?Reference $detail = null): self
    {
        $evidence = ['code' => [$code->toArray()]];

        if ($detail !== null) {
            $evidence['detail'] = [$detail->toArray()];
        }

        $this->push('evidence', $evidence);
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
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

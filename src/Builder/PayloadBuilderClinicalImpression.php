<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * ClinicalImpression FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/clinicalimpression.html
 */
class PayloadBuilderClinicalImpression extends Builder
{
    protected string $resourceType = 'ClinicalImpression';

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

    public function setDate(string $date): self
    {
        $this->set('date', $date);
        return $this;
    }

    public function setAssessor(Reference $assessor): self
    {
        $this->set('assessor', $assessor->toArray());
        return $this;
    }

    public function setPreviousOpinion(Reference $previousOpinion): self
    {
        $this->set('previousOpinion', $previousOpinion->toArray());
        return $this;
    }

    public function addInvestigation(array $investigation): self
    {
        $this->push('investigation', $investigation);
        return $this;
    }

    public function addFindingCodeableConcept(CodeableConcept $finding): self
    {
        $this->push('finding', ['itemCodeableConcept' => $finding->toArray()]);
        return $this;
    }

    public function addFindingReference(Reference $finding): self
    {
        $this->push('finding', ['itemReference' => $finding->toArray()]);
        return $this;
    }

    public function addPrognosisCodeableConcept(CodeableConcept $prognosis): self
    {
        $this->push('prognosisCodeableConcept', $prognosis->toArray());
        return $this;
    }

    public function addPrognosisReference(Reference $prognosis): self
    {
        $this->push('prognosisReference', $prognosis->toArray());
        return $this;
    }

    public function addSupportingInfo(Reference $supportingInfo): self
    {
        $this->push('supportingInfo', $supportingInfo->toArray());
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

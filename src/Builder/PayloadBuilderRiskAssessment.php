<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * RiskAssessment FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/riskassessment.html
 */
class PayloadBuilderRiskAssessment extends Builder
{
    protected string $resourceType = 'RiskAssessment';

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

    public function setOccurrenceDateTime(string $dateTime): self
    {
        $this->set('occurrenceDateTime', $dateTime);
        return $this;
    }

    public function setCondition(Reference $condition): self
    {
        $this->set('condition', $condition->toArray());
        return $this;
    }

    public function setPerformer(Reference $performer): self
    {
        $this->set('performer', $performer->toArray());
        return $this;
    }

    public function addReasonReference(Reference $reasonReference): self
    {
        $this->push('reasonReference', $reasonReference->toArray());
        return $this;
    }

    public function addBasis(Reference $basis): self
    {
        $this->push('basis', $basis->toArray());
        return $this;
    }

    public function addPrediction(
        CodeableConcept $outcome,
        float $probabilityDecimal,
        ?CodeableConcept $qualitativeRisk = null,
        ?float $relativeRisk = null
    ): self {
        $prediction = [
            'outcome' => $outcome->toArray(),
            'probabilityDecimal' => $probabilityDecimal,
        ];

        if ($qualitativeRisk !== null) {
            $prediction['qualitativeRisk'] = $qualitativeRisk->toArray();
        }

        if ($relativeRisk !== null) {
            $prediction['relativeRisk'] = $relativeRisk;
        }

        $this->push('prediction', $prediction);
        return $this;
    }

    public function setMitigation(string $mitigation): self
    {
        $this->set('mitigation', $mitigation);
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

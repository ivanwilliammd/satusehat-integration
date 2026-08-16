<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * DiagnosticReport FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/diagnosticreport.html
 */
class PayloadBuilderDiagnosticReport extends Builder
{
    protected string $resourceType = 'DiagnosticReport';

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

    public function addCategory(CodeableConcept $category): self
    {
        $this->push('category', $category->toArray());
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

    public function setEffectiveDateTime(string $dateTime): self
    {
        $this->set('effectiveDateTime', $dateTime);
        return $this;
    }

    public function setIssued(string $instant): self
    {
        $this->set('issued', $instant);
        return $this;
    }

    public function addPerformer(Reference $performer): self
    {
        $this->push('performer', $performer->toArray());
        return $this;
    }

    public function addResult(Reference $result): self
    {
        $this->push('result', $result->toArray());
        return $this;
    }

    public function addSpecimen(Reference $specimen): self
    {
        $this->push('specimen', $specimen->toArray());
        return $this;
    }

    public function addConclusionCode(CodeableConcept $conclusionCode): self
    {
        $this->push('conclusionCode', $conclusionCode->toArray());
        return $this;
    }

    public function addBasedOn(Reference $basedOn): self
    {
        $this->push('basedOn', $basedOn->toArray());
        return $this;
    }

    public function setConclusion(string $conclusion): self
    {
        $this->set('conclusion', $conclusion);
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

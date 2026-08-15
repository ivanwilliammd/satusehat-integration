<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

/**
 * Procedure FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/procedure.html
 */
class PayloadBuilderProcedure extends Builder
{
    protected string $resourceType = 'Procedure';

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

    public function setCategory(CodeableConcept $category): self
    {
        $this->set('category', $category->toArray());
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

    // performed[x] polymorphic setters
    public function setPerformedDateTime(string $dateTime): self
    {
        $this->set('performedDateTime', $dateTime);
        return $this;
    }

    public function setPerformedPeriod(Period $period): self
    {
        $this->set('performedPeriod', $period->toArray());
        return $this;
    }

    public function setPerformedString(string $performedString): self
    {
        $this->set('performedString', $performedString);
        return $this;
    }

    public function setPerformedAge(Range $age): self
    {
        $this->set('performedAge', $age->toArray());
        return $this;
    }

    public function setPerformedRange(Range $range): self
    {
        $this->set('performedRange', $range->toArray());
        return $this;
    }

    public function addPerformer(
        Reference $actor,
        ?CodeableConcept $function = null,
        ?Reference $onBehalfOf = null
    ): self {
        $performer = ['actor' => $actor->toArray()];

        if ($function !== null) {
            $performer['function'] = $function->toArray();
        }

        if ($onBehalfOf !== null) {
            $performer['onBehalfOf'] = $onBehalfOf->toArray();
        }

        $this->push('performer', $performer);
        return $this;
    }

    public function setOutcome(CodeableConcept $outcome): self
    {
        $this->set('outcome', $outcome->toArray());
        return $this;
    }

    public function addReport(Reference $report): self
    {
        $this->push('report', $report->toArray());
        return $this;
    }

    public function addFollowUp(CodeableConcept $followUp): self
    {
        $this->push('followUp', $followUp->toArray());
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }

    public function addFocalDevice(
        CodeableConcept $action,
        Reference $manufactureItem = null,
        ?Reference $device = null
    ): self {
        $focalDevice = ['action' => $action->toArray()];

        if ($device !== null) {
            $focalDevice['device'] = $device->toArray();
        }

        if ($manufactureItem !== null) {
            $focalDevice['manufactureItem'] = $manufactureItem->toArray();
        }

        $this->push('focalDevice', $focalDevice);
        return $this;
    }

    public function addUsedReference(Reference $reference, ?CodeableConcept $type = null): self
    {
        $used = $reference->toArray();

        if ($type !== null) {
            $used['type'] = $type->toArray();
        }

        $this->push('usedReference', $used);
        return $this;
    }

    public function addUsedCode(CodeableConcept $usedCode): self
    {
        $this->push('usedCode', $usedCode->toArray());
        return $this;
    }

    public function addBodySite(CodeableConcept $bodySite): self
    {
        $this->push('bodySite', $bodySite->toArray());
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

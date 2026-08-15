<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Age;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

/**
 * Goal FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/goal.html
 */
class PayloadBuilderGoal extends Builder
{
    protected string $resourceType = 'Goal';

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

    public function setLifecycleStatus(string $lifecycleStatus): self
    {
        $this->set('lifecycleStatus', $lifecycleStatus);
        return $this;
    }

    public function setDescription(CodeableConcept $description): self
    {
        $this->set('description', $description->toArray());
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setStartDateTime(string $startDateTime): self
    {
        $this->set('startDateTime', $startDateTime);
        return $this;
    }

    public function setStartAge(Age $startAge): self
    {
        $this->set('startAge', $startAge->toArray());
        return $this;
    }

    public function setStartPeriod(Period $startPeriod): self
    {
        $this->set('startPeriod', $startPeriod->toArray());
        return $this;
    }

    public function addTargetDetailQuantity(Quantity $detail): self
    {
        $target = ['detailQuantity' => $detail->toArray()];
        $this->push('target', $target);
        return $this;
    }

    public function addTargetDetailCodeableConcept(CodeableConcept $detail): self
    {
        $target = ['detailCodeableConcept' => $detail->toArray()];
        $this->push('target', $target);
        return $this;
    }

    public function addTargetRange(Range $range): self
    {
        $target = ['detailRange' => $range->toArray()];
        $this->push('target', $target);
        return $this;
    }

    public function setStatusReason(string $statusReason): self
    {
        $this->set('statusReason', $statusReason);
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }

    public function addOutcomeReference(Reference $outcomeReference): self
    {
        $this->push('outcomeReference', $outcomeReference->toArray());
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

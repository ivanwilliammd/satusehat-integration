<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

/**
 * ServiceRequest FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/servicerequest.html
 */
class PayloadBuilderServiceRequest extends Builder
{
    protected string $resourceType = 'ServiceRequest';

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

    public function setRequisition(Identifier $identifier): self
    {
        $this->set('requisition', $identifier->toArray());
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

    public function setPriority(string $priority): self
    {
        $this->set('priority', $priority);
        return $this;
    }

    public function setDoNotPerform(bool $doNotPerform): self
    {
        $this->set('doNotPerform', $doNotPerform);
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
        return $this;
    }

    public function setQuantityQuantity(Quantity $quantity): self
    {
        $this->set('quantityQuantity', $quantity->toArray());
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

    public function setAuthoredOn(string $dateTime): self
    {
        $this->set('authoredOn', $dateTime);
        return $this;
    }

    public function setRequester(Reference $requester): self
    {
        $this->set('requester', $requester->toArray());
        return $this;
    }

    public function addPerformer(Reference $performer): self
    {
        $this->push('performer', $performer->toArray());
        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode): self
    {
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function addSupportingInfo(Reference $supportingInfo): self
    {
        $this->push('supportingInfo', $supportingInfo->toArray());
        return $this;
    }

    public function addSpecimen(Reference $specimen): self
    {
        $this->push('specimen', $specimen->toArray());
        return $this;
    }

    public function addNote(string $text): self
    {
        $this->push('note', ['text' => $text]);
        return $this;
    }

    public function setPatientInstruction(string $instruction): self
    {
        $this->set('patientInstruction', $instruction);
        return $this;
    }

    public function addRelevantHistory(Reference $relevantHistory): self
    {
        $this->push('relevantHistory', $relevantHistory->toArray());
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

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

/**
 * Task FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/task.html
 */
class PayloadBuilderTask extends Builder
{
    protected string $resourceType = 'Task';

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

    public function setInstantiatesUri(string $instantiatesUri): self
    {
        $this->set('instantiatesUri', $instantiatesUri);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setStatusReason(CodeableConcept $statusReason): self
    {
        $this->set('statusReason', $statusReason->toArray());
        return $this;
    }

    public function setBusinessStatus(CodeableConcept $businessStatus): self
    {
        $this->set('businessStatus', $businessStatus->toArray());
        return $this;
    }

    public function setIntent(string $intent): self
    {
        $this->set('intent', $intent);
        return $this;
    }

    public function setPriority(string $priority): self
    {
        $this->set('priority', $priority);
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setFocus(Reference $focus): self
    {
        $this->set('focus', $focus->toArray());
        return $this;
    }

    public function setFor(Reference $for): self
    {
        $this->set('for', $for->toArray());
        return $this;
    }

    public function setEncounter(Reference $encounter): self
    {
        $this->set('encounter', $encounter->toArray());
        return $this;
    }

    public function setExecutionPeriod(Period $executionPeriod): self
    {
        $this->set('executionPeriod', $executionPeriod->toArray());
        return $this;
    }

    public function setAuthoredOn(string $authoredOn): self
    {
        $this->set('authoredOn', $authoredOn);
        return $this;
    }

    public function setLastModified(string $lastModified): self
    {
        $this->set('lastModified', $lastModified);
        return $this;
    }

    public function setRequester(Reference $requester): self
    {
        $this->set('requester', $requester->toArray());
        return $this;
    }

    public function setOwner(Reference $owner): self
    {
        $this->set('owner', $owner->toArray());
        return $this;
    }

    public function setLocation(Reference $location): self
    {
        $this->set('location', $location->toArray());
        return $this;
    }

    public function setReasonCode(CodeableConcept $reasonCode): self
    {
        $this->set('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function setReasonReference(Reference $reasonReference): self
    {
        $this->set('reasonReference', $reasonReference->toArray());
        return $this;
    }

    public function addInput(CodeableConcept $type, mixed $value, ?string $valueType = null): self
    {
        $input = ['type' => $type->toArray()];

        if ($valueType !== null) {
            $input['value' . ucfirst($valueType)] = $value;
        } else {
            $input['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('input', $input);
        return $this;
    }

    public function addOutput(CodeableConcept $type, mixed $value, ?string $valueType = null): self
    {
        $output = ['type' => $type->toArray()];

        if ($valueType !== null) {
            $output['value' . ucfirst($valueType)] = $value;
        } else {
            $output['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('output', $output);
        return $this;
    }

    public function addRestriction(Reference $requester, ?int $repetitions = null, ?Period $period = null): self
    {
        $restriction = [];

        if ($requester !== null) {
            $restriction['requester'] = $requester->toArray();
        }

        if ($repetitions !== null) {
            $restriction['repetitions'] = $repetitions;
        }

        if ($period !== null) {
            $restriction['period'] = $period->toArray();
        }

        $this->push('restriction', $restriction);
        return $this;
    }

    public function addNote(string $text): self
    {
        $this->push('note', ['text' => $text]);
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

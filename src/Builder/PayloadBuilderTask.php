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

    public function addIdentifier(string|Identifier $identifier, ?string $value = null): self
    {
        if ($identifier instanceof Identifier) {
            $this->push('identifier', $identifier->toArray());
        } else {
            $this->push('identifier', ['system' => $identifier, 'value' => $value]);
        }
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

    private const VALID_STATUSES = [
        'draft', 'requested', 'received', 'accepted', 'rejected', 'ready',
        'cancelled', 'in-progress', 'on-hold', 'failed', 'completed', 'entered-in-error',
    ];

    private const VALID_INTENTS = [
        'unknown', 'proposal', 'plan', 'order', 'original-order',
        'reflex-order', 'filler-order', 'instance-order', 'option',
    ];

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
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
        if (!in_array($intent, self::VALID_INTENTS, true)) {
            throw new \InvalidArgumentException("Invalid intent: {$intent}");
        }
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

    public function setFocus(string|Reference $focus, ?string $display = null): self
    {
        if ($focus instanceof Reference) {
            $this->set('focus', $focus->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $focus) && strpos($focus, '/') === false) {
                $focus = 'QuestionnaireResponse/' . $focus;
            }
            $this->set('focus', array_filter(['reference' => $focus, 'display' => $display], fn($v) => $v !== null));
        }
        return $this;
    }

    public function setFor(string|Reference $for, ?string $display = null): self
    {
        if ($for instanceof Reference) {
            $this->set('for', $for->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $for) && strpos($for, '/') === false) {
                $for = 'Patient/' . $for;
            }
            $this->set('for', array_filter(['reference' => $for, 'display' => $display], fn($v) => $v !== null));
        }
        return $this;
    }

    public function setEncounter(string|Reference $encounter, ?string $display = null): self
    {
        if ($encounter instanceof Reference) {
            $this->set('encounter', $encounter->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $encounter) && strpos($encounter, '/') === false) {
                $encounter = 'Encounter/' . $encounter;
            }
            $this->set('encounter', array_filter(['reference' => $encounter, 'display' => $display], fn($v) => $v !== null));
        }
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

    public function setRequester(string|Reference $requester, ?string $display = null): self
    {
        if ($requester instanceof Reference) {
            $this->set('requester', $requester->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $requester) && strpos($requester, '/') === false) {
                $requester = 'Practitioner/' . $requester;
            }
            $this->set('requester', array_filter(['reference' => $requester, 'display' => $display], fn($v) => $v !== null));
        }
        return $this;
    }

    public function setOwner(string|Reference $owner, ?string $display = null): self
    {
        if ($owner instanceof Reference) {
            $this->set('owner', $owner->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $owner) && strpos($owner, '/') === false) {
                $owner = 'Practitioner/' . $owner;
            }
            $this->set('owner', array_filter(['reference' => $owner, 'display' => $display], fn($v) => $v !== null));
        }
        return $this;
    }

    public function setLocation(string|Reference $location, ?string $display = null): self
    {
        if ($location instanceof Reference) {
            $this->set('location', $location->toArray());
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $location) && strpos($location, '/') === false) {
                $location = 'Location/' . $location;
            }
            $this->set('location', array_filter(['reference' => $location, 'display' => $display], fn($v) => $v !== null));
        }
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

    public function addInput(CodeableConcept|string $type, mixed $value): self
    {
        $input = [];
        if ($type instanceof CodeableConcept) {
            $input['type'] = $type->toArray();
        } else {
            $input['type'] = ['text' => $type];
        }
        $input['valueString'] = is_string($value) ? $value : (string) $value;
        $this->push('input', $input);
        return $this;
    }

    public function addOutput(CodeableConcept|string $type, mixed $value): self
    {
        $output = [];
        if ($type instanceof CodeableConcept) {
            $output['type'] = $type->toArray();
        } else {
            $output['type'] = ['text' => $type];
        }
        $output['valueString'] = is_string($value) ? $value : (string) $value;
        $this->push('output', $output);
        return $this;
    }

    public function addRestriction(string|Reference $requester, ?int $repetitions = null, ?Period $period = null): self
    {
        $ref = $requester instanceof Reference
            ? $requester->toArray()['reference']
            : $requester;
        if (!preg_match('/^(urn:|https?:\/\/)/', $ref) && strpos($ref, '/') === false) {
            $ref = 'Patient/' . $ref;
        }
        $restriction = ['requester' => ['reference' => $ref]];

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

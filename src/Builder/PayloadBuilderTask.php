<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Task FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/task.html
 */
class PayloadBuilderTask extends Builder
{
    protected string $resourceType = 'Task';

    private const VALID_STATUSES = [
        'draft', 'requested', 'received', 'accepted', 'rejected', 'ready',
        'cancelled', 'in-progress', 'on-hold', 'failed', 'completed', 'entered-in-error',
    ];

    private const VALID_INTENTS = [
        'unknown', 'proposal', 'plan', 'order', 'original-order',
        'reflex-order', 'filler-order', 'instance-order', 'option',
    ];

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', [
            'system' => $system,
            'value' => $value,
        ]);
        return $this;
    }

    public function setInstantiatesUri(string $uri): self
    {
        $this->set('instantiatesUri', $uri);
        return $this;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::VALID_STATUSES)) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }
        $this->set('status', $status);
        return $this;
    }

    public function setIntent(string $intent): self
    {
        if (!in_array($intent, self::VALID_INTENTS)) {
            throw new \InvalidArgumentException("Invalid intent: $intent");
        }
        $this->set('intent', $intent);
        return $this;
    }

    public function setPriority(string $priority): self
    {
        $this->set('priority', $priority);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setFor(string $reference): self
    {
        $this->set('for', ['reference' => $reference]);
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->set('encounter', ['reference' => $reference]);
        return $this;
    }

    public function setAuthoredOn(string $dateTime): self
    {
        $this->set('authoredOn', $dateTime);
        return $this;
    }

    public function setRequester(string $reference): self
    {
        $this->set('requester', ['reference' => $reference]);
        return $this;
    }

    public function addInput(string $type, string $value): self
    {
        $this->push('input', [
            'type' => ['text' => $type],
            'valueString' => $value,
        ]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Task extends OAuth2Client
{
    public array $task = ['resourceType' => 'Task'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->task['identifier'][] = $identifier;
        return $this;
    }

    public function setInstantiatesUri($uri)
    {
        $this->task['instantiatesUri'] = $uri;
        return $this;
    }

    public function setStatus($status = 'requested')
    {
        $validStatuses = ['draft', 'requested', 'received', 'accepted', 'rejected', 'ready', 'cancelled', 'in-progress', 'on-hold', 'failed', 'completed', 'entered-in-error'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->task['status'] = $status;
        return $this;
    }

    public function setIntent($intent = 'original-order')
    {
        $validIntents = ['unknown', 'proposal', 'plan', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option'];
        if (! in_array($intent, $validIntents)) {
            throw new FHIRInvalidPropertyValue('Invalid intent');
        }
        $this->task['intent'] = $intent;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->task['priority'] = $priority;
        return $this;
    }

    public function setDescription($description)
    {
        $this->task['description'] = $description;
        return $this;
    }

    public function setFor($reference)
    {
        $this->task['for'] = ['reference' => $reference];
        return $this;
    }

    public function setEncounter($reference)
    {
        $this->task['encounter'] = ['reference' => $reference];
        return $this;
    }

    public function setAuthoredOn($dateTime)
    {
        $this->task['authoredOn'] = $dateTime;
        return $this;
    }

    public function setLastModified($dateTime)
    {
        $this->task['lastModified'] = $dateTime;
        return $this;
    }

    public function setRequester($reference)
    {
        $this->task['requester'] = ['reference' => $reference];
        return $this;
    }

    public function setOwner($reference)
    {
        $this->task['owner'] = ['reference' => $reference];
        return $this;
    }

    public function addInput($type, $value)
    {
        $this->task['input'][] = [
            'type' => ['text' => $type],
            'valueString' => $value,
        ];
        return $this;
    }

    public function addOutput($type, $value)
    {
        $this->task['output'][] = [
            'type' => ['text' => $type],
            'valueString' => $value,
        ];
        return $this;
    }

    public function json(): string
    {
        if (! array_key_exists('status', $this->task)) {
            throw new FHIRMissingProperty('Task.status is required');
        }

        if (! array_key_exists('intent', $this->task)) {
            throw new FHIRMissingProperty('Task.intent is required');
        }

        if (! array_key_exists('encounter', $this->task)) {
            throw new FHIRMissingProperty('Task.encounter is required');
        }

        if (! array_key_exists('input', $this->task)) {
            throw new FHIRMissingProperty('Task.input is required');
        }

        return json_encode($this->task, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post(): array
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Task', $payload);

        return [$statusCode, $res];
    }

    public function put($id): array
    {
        $this->task['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Task', $id, $payload);

        return [$statusCode, $res];
    }
}

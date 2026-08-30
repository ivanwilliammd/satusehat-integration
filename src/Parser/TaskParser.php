<?php

namespace Satusehat\Integration\Parser;

class TaskParser
{
    private $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function getIdentifiers()
    {
        return $this->task['identifier'] ?? null;
    }

    public function getInstantiatesUri()
    {
        return $this->task['instantiatesUri'] ?? null;
    }

    public function getStatus()
    {
        return $this->task['status'] ?? null;
    }

    public function getIntent()
    {
        return $this->task['intent'] ?? null;
    }

    public function getPriority()
    {
        return $this->task['priority'] ?? null;
    }

    public function getDescription()
    {
        return $this->task['description'] ?? null;
    }

    public function getFor()
    {
        return $this->task['for'] ?? null;
    }

    public function getForReference()
    {
        return $this->removePrefix($this->task['for']['reference'] ?? null, 'Patient/');
    }

    public function getEncounter()
    {
        return $this->task['encounter'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->task['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getAuthoredOn()
    {
        return $this->task['authoredOn'] ?? null;
    }

    public function getRequester()
    {
        return $this->task['requester'] ?? null;
    }

    public function getRequesterReference()
    {
        return $this->removePrefix($this->task['requester']['reference'] ?? null, 'Practitioner/');
    }

    public function getInputs()
    {
        return $this->task['input'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

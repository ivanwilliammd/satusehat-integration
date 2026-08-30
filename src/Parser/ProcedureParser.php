<?php

namespace Satusehat\Integration\Parser;

class ProcedureParser
{
    private $procedure;

    public function __construct($procedure)
    {
        $this->procedure = $procedure;
    }

    public function getIdentifiers()
    {
        return $this->procedure['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->procedure['status'] ?? null;
    }

    public function getCategory()
    {
        return $this->procedure['category'] ?? null;
    }

    public function getCode()
    {
        return $this->procedure['code'] ?? null;
    }

    public function getSubject()
    {
        return $this->procedure['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->procedure['subject']['reference'] ?? null, 'Patient/');
    }

    public function getSubjectDisplay()
    {
        return $this->procedure['subject']['display'] ?? null;
    }

    public function getEncounter()
    {
        return $this->procedure['encounter'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->procedure['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getEncounterDisplay()
    {
        return $this->procedure['encounter']['display'] ?? null;
    }

    public function getPerformers()
    {
        return $this->procedure['performer'] ?? null;
    }

    public function getPerformerReferences()
    {
        return array_map(function($performer) {
            return $this->removePrefix($performer['actor']['reference'] ?? null, 'Practitioner/');
        }, $this->procedure['performer'] ?? []);
    }

    public function getPerformerDisplays()
    {
        return array_map(function($performer) {
            return $performer['actor']['display'] ?? null;
        }, $this->procedure['performer'] ?? []);
    }

    public function getPerformedPeriod()
    {
        return $this->procedure['performedPeriod'] ?? null;
    }

    public function getReasonCodes()
    {
        return $this->procedure['reasonCode'] ?? null;
    }

    public function getBodySites()
    {
        return $this->procedure['bodySite'] ?? null;
    }

    public function getNotes()
    {
        return $this->procedure['note'] ?? null;
    }

    public function getUsedCodes()
    {
        return $this->procedure['usedCode'] ?? null;
    }

    public function getBasedOn()
    {
        return $this->procedure['basedOn'] ?? null;
    }

    public function getLocation()
    {
        return $this->removePrefix($this->procedure['location']['reference'] ?? null, 'Location/');
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

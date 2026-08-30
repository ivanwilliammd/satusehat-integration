<?php

namespace Satusehat\Integration\Parser;

class RiskAssessmentParser
{
    private $riskAssessment;

    public function __construct($riskAssessment)
    {
        $this->riskAssessment = $riskAssessment;
    }

    public function getStatus()
    {
        return $this->riskAssessment['status'] ?? null;
    }

    public function getIdentifiers()
    {
        return $this->riskAssessment['identifier'] ?? null;
    }

    public function getCode()
    {
        return $this->riskAssessment['code'] ?? null;
    }

    public function getSubject()
    {
        return $this->riskAssessment['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->riskAssessment['subject']['reference'] ?? null, 'Patient/');
    }

    public function getEncounter()
    {
        return $this->riskAssessment['encounter'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->riskAssessment['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getOccurrenceDateTime()
    {
        return $this->riskAssessment['occurrenceDateTime'] ?? null;
    }

    public function getCondition()
    {
        return $this->riskAssessment['condition'] ?? null;
    }

    public function getConditionReference()
    {
        return $this->removePrefix($this->riskAssessment['condition']['reference'] ?? null, 'Condition/');
    }

    public function getPerformer()
    {
        return $this->riskAssessment['performer'] ?? null;
    }

    public function getPerformerReference()
    {
        return $this->removePrefix($this->riskAssessment['performer']['reference'] ?? null, 'Practitioner/');
    }

    public function getReasonReferences()
    {
        return $this->riskAssessment['reasonReference'] ?? null;
    }

    public function getBasis()
    {
        return $this->riskAssessment['basis'] ?? null;
    }

    public function getPredictions()
    {
        return $this->riskAssessment['prediction'] ?? null;
    }

    public function getMitigation()
    {
        return $this->riskAssessment['mitigation'] ?? null;
    }

    public function getNotes()
    {
        return $this->riskAssessment['note'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

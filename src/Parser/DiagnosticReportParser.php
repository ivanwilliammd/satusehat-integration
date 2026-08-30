<?php

namespace Satusehat\Integration\Parser;

class DiagnosticReportParser
{
    private $diagnosticReport;

    public function __construct($diagnosticReport)
    {
        $this->diagnosticReport = $diagnosticReport;
    }

    public function getIdentifiers()
    {
        return $this->diagnosticReport['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->diagnosticReport['status'] ?? null;
    }

    public function getCategories()
    {
        return $this->diagnosticReport['category'] ?? null;
    }

    public function getCode()
    {
        return $this->diagnosticReport['code'] ?? null;
    }

    public function getSubject()
    {
        return $this->diagnosticReport['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->diagnosticReport['subject']['reference'] ?? null, 'Patient/');
    }

    public function getEncounter()
    {
        return $this->diagnosticReport['encounter'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->diagnosticReport['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getEffectiveDateTime()
    {
        return $this->diagnosticReport['effectiveDateTime'] ?? null;
    }

    public function getIssued()
    {
        return $this->diagnosticReport['issued'] ?? null;
    }

    public function getPerformers()
    {
        return $this->diagnosticReport['performer'] ?? null;
    }

    public function getPerformerReferences()
    {
        return array_map(function($performer) {
            return $this->removePrefix($performer['reference'] ?? null, 'Practitioner/');
        }, $this->diagnosticReport['performer'] ?? []);
    }

    public function getResults()
    {
        return $this->diagnosticReport['result'] ?? null;
    }

    public function getResultReferences()
    {
        return array_map(function($result) {
            return $this->removePrefix($result['reference'] ?? null, 'Observation/');
        }, $this->diagnosticReport['result'] ?? []);
    }

    public function getSpecimens()
    {
        return $this->diagnosticReport['specimen'] ?? null;
    }

    public function getSpecimenReferences()
    {
        return array_map(function($specimen) {
            return $this->removePrefix($specimen['reference'] ?? null, 'Specimen/');
        }, $this->diagnosticReport['specimen'] ?? []);
    }

    public function getConclusionCodes()
    {
        return $this->diagnosticReport['conclusionCode'] ?? null;
    }

    public function getBasedOn()
    {
        return $this->diagnosticReport['basedOn'] ?? null;
    }

    public function getConclusion()
    {
        return $this->diagnosticReport['conclusion'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

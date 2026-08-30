<?php

namespace Satusehat\Integration\Parser;

class GenomicStudyParser
{
    private $genomicStudy;

    public function __construct($genomicStudy)
    {
        $this->genomicStudy = $genomicStudy;
    }

    public function getIdentifiers()
    {
        return $this->genomicStudy['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->genomicStudy['status'] ?? null;
    }

    public function getTypes()
    {
        return $this->genomicStudy['type'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->genomicStudy['subject']['reference'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->genomicStudy['encounter']['reference'] ?? null;
    }

    public function getStartDate()
    {
        return $this->genomicStudy['startDate'] ?? null;
    }

    public function getBasedOnReferences()
    {
        return $this->genomicStudy['basedOn'] ?? null;
    }

    public function getReferrerReference()
    {
        return $this->genomicStudy['referrer']['reference'] ?? null;
    }

    public function getInterpreterReferences()
    {
        return $this->genomicStudy['interpreter'] ?? null;
    }

    public function getReasons()
    {
        return $this->genomicStudy['reason'] ?? null;
    }

    public function getNotes()
    {
        return $this->genomicStudy['note'] ?? null;
    }

    public function getDescription()
    {
        return $this->genomicStudy['description'] ?? null;
    }

    public function getAnalyses()
    {
        return $this->genomicStudy['analysis'] ?? null;
    }

    public function getPerformers()
    {
        return $this->genomicStudy['performer'] ?? null;
    }
}

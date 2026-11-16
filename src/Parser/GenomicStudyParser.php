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

    public function getMeta()
    {
        return $this->data['meta'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getType()
    {
        return $this->data['type'] ?? null;
    }

    public function getSubject()
    {
        return $this->data['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->data['encounter'] ?? null;
    }

    public function getBasedOn()
    {
        return $this->data['basedOn'] ?? null;
    }

    public function getReferrer()
    {
        return $this->data['referrer'] ?? null;
    }

    public function getInterpreter()
    {
        return $this->data['interpreter'] ?? null;
    }

    public function getReason()
    {
        return $this->data['reason'] ?? null;
    }

    public function getNote()
    {
        return $this->data['note'] ?? null;
    }

    public function getAnalysis()
    {
        return $this->data['analysis'] ?? null;
    }

    public function getPerformer()
    {
        return $this->data['performer'] ?? null;
    }
}

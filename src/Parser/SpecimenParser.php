<?php

namespace Satusehat\Integration\Parser;

class SpecimenParser
{
    private $specimen;

    public function __construct($specimen)
    {
        $this->specimen = $specimen;
    }

    public function getIdentifiers()
    {
        return $this->specimen['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->specimen['status'] ?? null;
    }

    public function getType()
    {
        return $this->specimen['type'] ?? null;
    }

    public function getSubject()
    {
        return $this->specimen['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->specimen['subject']['reference'] ?? null, 'Patient/');
    }

    public function getSubjectDisplay()
    {
        return $this->specimen['subject']['display'] ?? null;
    }

    public function getReceivedTime()
    {
        return $this->specimen['receivedTime'] ?? null;
    }

    public function getRequests()
    {
        return $this->specimen['request'] ?? null;
    }

    public function getCollectedDateTime()
    {
        return $this->specimen['collection']['collectedDateTime'] ?? null;
    }

    public function getCollector()
    {
        return $this->specimen['collection']['collector'] ?? null;
    }

    public function getCollectorReference()
    {
        return $this->removePrefix($this->specimen['collection']['collector']['reference'] ?? null, 'Practitioner/');
    }

    public function getCollectorDisplay()
    {
        return $this->specimen['collection']['collector']['display'] ?? null;
    }

    public function getFastingStatus()
    {
        return $this->specimen['collection']['fastingStatusCodeableConcept'] ?? null;
    }

    public function getMethod()
    {
        return $this->specimen['collection']['method'] ?? null;
    }

    public function getQuantity()
    {
        return $this->specimen['collection']['quantity'] ?? null;
    }

    public function getBodySite()
    {
        return $this->specimen['collection']['bodySite'] ?? null;
    }

    public function getConditions()
    {
        return $this->specimen['condition'] ?? null;
    }

    public function getExtensions()
    {
        return $this->specimen['extension'] ?? null;
    }

    public function getProcessing()
    {
        return $this->specimen['processing'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

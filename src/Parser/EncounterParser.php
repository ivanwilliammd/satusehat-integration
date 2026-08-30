<?php

namespace Satusehat\Integration\Parser;

class EncounterParser
{
    private $encounter;

    public function __construct($encounter)
    {
        $this->encounter = $encounter;
    }

    public function getStatus()
    {
        return $this->encounter['status'] ?? null;
    }

    public function getLocations()
    {
        return $this->encounter['location'] ?? null;
    }

    public function getLocationReferences()
    {
        return array_map(function($location) {
            return $this->removePrefix($location['location']['reference'] ?? null, 'Location/');
        }, $this->encounter['location'] ?? []);
    }

    public function getLocationDisplays()
    {
        return array_map(function($location) {
            return $location['location']['display'] ?? null;
        }, $this->encounter['location'] ?? []);
    }

    public function getSubject()
    {
        return $this->encounter['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->encounter['subject']['reference'] ?? null, 'Patient/');
    }

    public function getSubjectDisplay()
    {
        return $this->encounter['subject']['display'] ?? null;
    }

    public function getParticipants()
    {
        return $this->encounter['participant'] ?? null;
    }

    public function getParticipantReferences()
    {
        return array_map(function($participant) {
            return $this->removePrefix($participant['individual']['reference'] ?? null, 'Practitioner/');
        }, $this->encounter['participant'] ?? []);
    }

    public function getParticipantDisplays()
    {
        return array_map(function($participant) {
            return $participant['individual']['display'] ?? null;
        }, $this->encounter['participant'] ?? []);
    }

    public function getDiagnosis()
    {
        return $this->encounter['diagnosis'] ?? null;
    }

    public function getDiagnosisReferences()
    {
        return array_map(function($diagnosis) {
            return $this->removePrefix($diagnosis['condition']['reference'] ?? null, 'Condition/');
        }, $this->encounter['diagnosis'] ?? []);
    }

    public function getDiagnosisDisplays()
    {
        return array_map(function($diagnosis) {
            return $diagnosis['condition']['display'] ?? null;
        }, $this->encounter['diagnosis'] ?? []);
    }

    public function getRegistrationIds()
    {
        return array_map(function($identifier) {
            return $identifier['value'] ?? null;
        }, $this->encounter['identifier'] ?? []);
    }

    public function getStatusHistory()
    {
        return $this->encounter['statusHistory'] ?? null;
    }

    public function getConsultationMethod()
    {
        return $this->encounter['class'] ?? null;
    }

    public function getServiceProvider()
    {
        return $this->encounter['serviceProvider'] ?? null;
    }

    public function getPeriod()
    {
        return $this->encounter['period'] ?? null;
    }

    public function getPeriodStart()
    {
        return $this->encounter['period']['start'] ?? null;
    }

    public function getPeriodEnd()
    {
        return $this->encounter['period']['end'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

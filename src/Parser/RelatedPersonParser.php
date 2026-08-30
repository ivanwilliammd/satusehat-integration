<?php

namespace Satusehat\Integration\Parser;

class RelatedPersonParser
{
    private $relatedPerson;

    public function __construct($relatedPerson)
    {
        $this->relatedPerson = $relatedPerson;
    }

    public function getIdentifiers()
    {
        return $this->relatedPerson['identifier'] ?? null;
    }

    public function getActive()
    {
        return $this->relatedPerson['active'] ?? null;
    }

    public function getPatientReference()
    {
        return $this->relatedPerson['patient']['reference'] ?? null;
    }

    public function getRelationships()
    {
        return $this->relatedPerson['relationship'] ?? null;
    }

    public function getNames()
    {
        return $this->relatedPerson['name'] ?? null;
    }

    public function getTelecoms()
    {
        return $this->relatedPerson['telecom'] ?? null;
    }

    public function getGender()
    {
        return $this->relatedPerson['gender'] ?? null;
    }

    public function getBirthDate()
    {
        return $this->relatedPerson['birthDate'] ?? null;
    }

    public function getAddresses()
    {
        return $this->relatedPerson['address'] ?? null;
    }

    public function getCommunications()
    {
        return $this->relatedPerson['communication'] ?? null;
    }
}

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

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getPatient()
    {
        return $this->data['patient'] ?? null;
    }

    public function getRelationship()
    {
        return $this->data['relationship'] ?? null;
    }

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getTelecom()
    {
        return $this->data['telecom'] ?? null;
    }

    public function getAddress()
    {
        return $this->data['address'] ?? null;
    }

    public function getCommunication()
    {
        return $this->data['communication'] ?? null;
    }

    public function getExtension()
    {
        return $this->data['extension'] ?? null;
    }
}

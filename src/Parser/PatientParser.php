<?php

namespace Satusehat\Integration\Parser;

class PatientParser
{
    private $patient;

    public function __construct($patient)
    {
        $this->patient = $patient;
    }

    public function getIdentifiers()
    {
        return $this->patient['identifier'] ?? null;
    }

    public function getName()
    {
        return $this->patient['name'] ?? null;
    }

    public function getTelecom()
    {
        return $this->patient['telecom'] ?? null;
    }

    public function getGender()
    {
        return $this->patient['gender'] ?? null;
    }

    public function getBirthDate()
    {
        return $this->patient['birthDate'] ?? null;
    }

    public function getDeceased()
    {
        return $this->patient['deceasedBoolean'] ?? null;
    }

    public function getAddress()
    {
        return $this->patient['address'] ?? null;
    }

    public function getMaritalStatus()
    {
        return $this->patient['maritalStatus'] ?? null;
    }

    public function getMultipleBirth()
    {
        return $this->patient['multipleBirthBoolean'] ?? $this->patient['multipleBirthInteger'] ?? null;
    }

    public function getEmergencyContact()
    {
        return $this->patient['contact'] ?? null;
    }

    public function getCommunication()
    {
        return $this->patient['communication'] ?? null;
    }

    public function getExtension()
    {
        return $this->patient['extension'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

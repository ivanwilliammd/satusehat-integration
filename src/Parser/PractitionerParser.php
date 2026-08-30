<?php

namespace Satusehat\Integration\Parser;

use Satusehat\Integration\OAuth2Client;

class PractitionerParser extends OAuth2Client
{
    private $practitioner;

    public function __construct($practitioner)
    {
        $this->practitioner = $practitioner;
    }

    public function getSSNik($nik)
    {
        [$statusCode, $res] = $this->get_by_nik('Practitioner', $nik);

        if ($statusCode != 200) {
            return null;
        }

        $this->practitioner = $res->entry ? $res->entry[0]->resource : null;

        return $this->practitioner;
    }

    public function getId()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->id;
    }

    public function getIdentifiers()
    {
        return $this->practitioner['identifier'] ?? null;
    }

    public function getActive()
    {
        return $this->practitioner['active'] ?? null;
    }

    public function getName()
    {
        return $this->practitioner['name'] ?? null;
    }

    public function getTelecom()
    {
        return $this->practitioner['telecom'] ?? null;
    }

    public function getAddress()
    {
        return $this->practitioner['address'] ?? null;
    }

    public function getAddressLine()
    {
        // If practitioner is not found, return null
        return $this->practitioner['address'][0]['line'][0] ?? null;
    }

    public function getCity()
    {
        // If practitioner is not found, return null
        return $this->practitioner['address'][0]['extension'][0]['extension'][1]['valueCode'] ?? null;
    }

    public function getVillage()
    {
        // If practitioner is not found, return null
        return $this->practitioner['address'][0]['extension'][0]['extension'][3]['valueCode'] ?? null;
    }

    public function getGender()
    {
        return $this->practitioner['gender'] ?? null;
    }

    public function getBirthDate()
    {
        return $this->practitioner['birthDate'] ?? null;
    }

    public function getPhoto()
    {
        return $this->practitioner['photo'] ?? null;
    }

    public function getQualifications()
    {
        return $this->practitioner['qualification'][0]['identifier'][0]['value'] ?? null;
    }

    public function getCommunication()
    {
        return $this->practitioner['communication'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

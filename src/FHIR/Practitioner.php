<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Practitioner extends OAuth2Client
{
    public $practitioner;

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

    public function getGender()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->gender;
    }

    public function getBirthDate()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->birthDate;
    }

    public function getName()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->name[0]->text;
    }

    public function getQualificationValue()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->qualification[0]->identifier[0]->value;
    }

    public function getAddressLine()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->address[0]->line[0];
    }

    public function getCity()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->address[0]->extension[0]->extension[1]->valueCode;
    }

    public function getVillage()
    {
        // If practitioner is not found, return null
        return ! $this->practitioner ? null : $this->practitioner->address[0]->extension[0]->extension[3]->valueCode;
    }
}

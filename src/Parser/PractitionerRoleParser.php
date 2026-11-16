<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 PractitionerRole.
 *
 * Dihasilkan dari PayloadBuilderPractitionerRole oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/practitionerrole.html
 */
class PractitionerRoleParser
{
    private array $data;

    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : [];
    }

    public function getRaw(): array
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getActive()
    {
        return $this->data['active'] ?? null;
    }

    public function getPractitioner()
    {
        return $this->data['practitioner'] ?? null;
    }

    public function getOrganization()
    {
        return $this->data['organization'] ?? null;
    }

    public function getCode()
    {
        return $this->data['code'] ?? null;
    }

    public function getSpecialty()
    {
        return $this->data['specialty'] ?? null;
    }

    public function getLocation()
    {
        return $this->data['location'] ?? null;
    }

    public function getHealthcareService()
    {
        return $this->data['healthcareService'] ?? null;
    }

    public function getTelecom()
    {
        return $this->data['telecom'] ?? null;
    }

    public function getAvailableTime()
    {
        return $this->data['availableTime'] ?? null;
    }

    public function getNotAvailable()
    {
        return $this->data['notAvailable'] ?? null;
    }

    public function getEndpoint()
    {
        return $this->data['endpoint'] ?? null;
    }

    public function getAvailabilityExceptions()
    {
        return $this->data['availabilityExceptions'] ?? null;
    }

    public function getExtension()
    {
        return $this->data['extension'] ?? null;
    }
}

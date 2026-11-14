<?php

namespace Satusehat\Integration\Parser;

/**
 * Immunization — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Immunization.json, copied unchanged to
 * tests/Fixtures/satusehat/Immunization.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ImmunizationParser
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function getRaw()
    {
        return $this->payload;
    }

    public function getResourceType()
    {
        return $this->payload['resourceType'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getVaccineCode()
    {
        return $this->payload['vaccineCode'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getOccurrenceDateTime()
    {
        return $this->payload['occurrenceDateTime'] ?? null;
    }

    public function getRecorded()
    {
        return $this->payload['recorded'] ?? null;
    }

    public function getReaction()
    {
        return $this->payload['reaction'] ?? null;
    }

    public function getPrimarySource()
    {
        return $this->payload['primarySource'] ?? null;
    }

    public function getLocation()
    {
        return $this->payload['location'] ?? null;
    }

    public function getLotNumber()
    {
        return $this->payload['lotNumber'] ?? null;
    }

    public function getExpirationDate()
    {
        return $this->payload['expirationDate'] ?? null;
    }

    public function getRoute()
    {
        return $this->payload['route'] ?? null;
    }

    public function getDoseQuantity()
    {
        return $this->payload['doseQuantity'] ?? null;
    }

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->payload['reasonCode'] ?? null;
    }

    public function getProtocolApplied()
    {
        return $this->payload['protocolApplied'] ?? null;
    }
}

<?php

namespace Satusehat\Integration\Parser;

/**
 * MedicationStatement — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/MedicationStatement.json, copied unchanged to
 * tests/Fixtures/satusehat/MedicationStatement.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class MedicationStatementParser
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

    public function getContained()
    {
        return $this->payload['contained'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getCategory()
    {
        return $this->payload['category'] ?? null;
    }

    public function getMedicationReference()
    {
        return $this->payload['medicationReference'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getDosage()
    {
        return $this->payload['dosage'] ?? null;
    }

    public function getEffectiveDateTime()
    {
        return $this->payload['effectiveDateTime'] ?? null;
    }

    public function getDateAsserted()
    {
        return $this->payload['dateAsserted'] ?? null;
    }

    public function getInformationSource()
    {
        return $this->payload['informationSource'] ?? null;
    }

    public function getContext()
    {
        return $this->payload['context'] ?? null;
    }
}

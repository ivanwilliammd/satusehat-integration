<?php

namespace Satusehat\Integration\Parser;

/**
 * MedicationAdministration — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/MedicationAdministration.json, copied unchanged to
 * tests/Fixtures/satusehat/MedicationAdministration.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class MedicationAdministrationParser
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

    public function getContext()
    {
        return $this->payload['context'] ?? null;
    }

    public function getEffectivePeriod()
    {
        return $this->payload['effectivePeriod'] ?? null;
    }

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->payload['reasonCode'] ?? null;
    }

    public function getRequest()
    {
        return $this->payload['request'] ?? null;
    }

    public function getDosage()
    {
        return $this->payload['dosage'] ?? null;
    }
}

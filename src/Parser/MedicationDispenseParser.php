<?php

namespace Satusehat\Integration\Parser;

/**
 * MedicationDispense — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/MedicationDispense.json, copied unchanged to
 * tests/Fixtures/satusehat/MedicationDispense.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class MedicationDispenseParser
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

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
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

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getLocation()
    {
        return $this->payload['location'] ?? null;
    }

    public function getAuthorizingPrescription()
    {
        return $this->payload['authorizingPrescription'] ?? null;
    }

    public function getQuantity()
    {
        return $this->payload['quantity'] ?? null;
    }

    public function getDaysSupply()
    {
        return $this->payload['daysSupply'] ?? null;
    }

    public function getWhenPrepared()
    {
        return $this->payload['whenPrepared'] ?? null;
    }

    public function getWhenHandedOver()
    {
        return $this->payload['whenHandedOver'] ?? null;
    }

    public function getDosageInstruction()
    {
        return $this->payload['dosageInstruction'] ?? null;
    }
}

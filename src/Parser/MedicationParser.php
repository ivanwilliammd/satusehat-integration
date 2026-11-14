<?php

namespace Satusehat\Integration\Parser;

/**
 * Medication — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Medication.json, copied unchanged to
 * tests/Fixtures/satusehat/Medication.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class MedicationParser
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

    public function getMeta()
    {
        return $this->payload['meta'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
    }

    public function getCode()
    {
        return $this->payload['code'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getManufacturer()
    {
        return $this->payload['manufacturer'] ?? null;
    }

    public function getForm()
    {
        return $this->payload['form'] ?? null;
    }

    public function getIngredient()
    {
        return $this->payload['ingredient'] ?? null;
    }

    public function getBatch()
    {
        return $this->payload['batch'] ?? null;
    }

    public function getExtension()
    {
        return $this->payload['extension'] ?? null;
    }
}

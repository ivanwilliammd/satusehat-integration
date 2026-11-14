<?php

namespace Satusehat\Integration\Parser;

/**
 * NutritionOrder — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/NutritionOrder.json, copied unchanged to
 * tests/Fixtures/satusehat/NutritionOrder.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class NutritionOrderParser
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

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getIntent()
    {
        return $this->payload['intent'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getDateTime()
    {
        return $this->payload['dateTime'] ?? null;
    }

    public function getOralDiet()
    {
        return $this->payload['oralDiet'] ?? null;
    }
}

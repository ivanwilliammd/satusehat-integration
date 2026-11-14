<?php

namespace Satusehat\Integration\Parser;

/**
 * FamilyMemberHistory — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/FamilyMemberHistory.json, copied unchanged to
 * tests/Fixtures/satusehat/FamilyMemberHistory.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class FamilyMemberHistoryParser
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

    public function getRelationship()
    {
        return $this->payload['relationship'] ?? null;
    }

    public function getDeceasedBoolean()
    {
        return $this->payload['deceasedBoolean'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getDate()
    {
        return $this->payload['date'] ?? null;
    }

    public function getCondition()
    {
        return $this->payload['condition'] ?? null;
    }
}

<?php

namespace Satusehat\Integration\Parser;

/**
 * Condition — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Condition.json, copied unchanged to
 * tests/Fixtures/satusehat/Condition.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ConditionParser
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

    public function getClinicalStatus()
    {
        return $this->payload['clinicalStatus'] ?? null;
    }

    public function getVerificationStatus()
    {
        return $this->payload['verificationStatus'] ?? null;
    }

    public function getCategory()
    {
        return $this->payload['category'] ?? null;
    }

    public function getCode()
    {
        return $this->payload['code'] ?? null;
    }

    public function getStage()
    {
        return $this->payload['stage'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getRecordedDate()
    {
        return $this->payload['recordedDate'] ?? null;
    }
}

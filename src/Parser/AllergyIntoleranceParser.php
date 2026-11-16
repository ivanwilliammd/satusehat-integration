<?php

namespace Satusehat\Integration\Parser;

/**
 * AllergyIntolerance — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/AllergyIntolerance.json, copied unchanged to
 * tests/Fixtures/satusehat/AllergyIntolerance.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class AllergyIntoleranceParser
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

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getRecordedDate()
    {
        return $this->payload['recordedDate'] ?? null;
    }

    public function getRecorder()
    {
        return $this->payload['recorder'] ?? null;
    }

    public function getType()
    {
        return $this->data['type'] ?? null;
    }

    public function getCriticality()
    {
        return $this->data['criticality'] ?? null;
    }

    public function getOnsetDateTime()
    {
        return $this->data['onsetDateTime'] ?? null;
    }

    public function getAsserter()
    {
        return $this->data['asserter'] ?? null;
    }

    public function getLastOccurrence()
    {
        return $this->data['lastOccurrence'] ?? null;
    }

    public function getNote()
    {
        return $this->data['note'] ?? null;
    }

    public function getReaction()
    {
        return $this->data['reaction'] ?? null;
    }
}

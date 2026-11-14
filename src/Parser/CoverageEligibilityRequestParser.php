<?php

namespace Satusehat\Integration\Parser;

/**
 * CoverageEligibilityRequest — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/CoverageEligibilityRequest.json, copied unchanged to
 * tests/Fixtures/satusehat/CoverageEligibilityRequest.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CoverageEligibilityRequestParser
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

    public function getPriority()
    {
        return $this->payload['priority'] ?? null;
    }

    public function getPurpose()
    {
        return $this->payload['purpose'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getServicedDate()
    {
        return $this->payload['servicedDate'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getEnterer()
    {
        return $this->payload['enterer'] ?? null;
    }

    public function getProvider()
    {
        return $this->payload['provider'] ?? null;
    }

    public function getInsurer()
    {
        return $this->payload['insurer'] ?? null;
    }

    public function getFacility()
    {
        return $this->payload['facility'] ?? null;
    }

    public function getInsurance()
    {
        return $this->payload['insurance'] ?? null;
    }

    public function getSupportingInfo()
    {
        return $this->payload['supportingInfo'] ?? null;
    }

    public function getContained()
    {
        return $this->payload['contained'] ?? null;
    }
}

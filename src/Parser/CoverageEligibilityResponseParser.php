<?php

namespace Satusehat\Integration\Parser;

/**
 * CoverageEligibilityResponse — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/CoverageEligibilityResponse.json, copied unchanged to
 * tests/Fixtures/satusehat/CoverageEligibilityResponse.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CoverageEligibilityResponseParser
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

    public function getPurpose()
    {
        return $this->payload['purpose'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getRequestor()
    {
        return $this->payload['requestor'] ?? null;
    }

    public function getRequest()
    {
        return $this->payload['request'] ?? null;
    }

    public function getOutcome()
    {
        return $this->payload['outcome'] ?? null;
    }

    public function getDisposition()
    {
        return $this->payload['disposition'] ?? null;
    }

    public function getInsurer()
    {
        return $this->payload['insurer'] ?? null;
    }

    public function getInsurance()
    {
        return $this->payload['insurance'] ?? null;
    }

    public function getExtension()
    {
        return $this->payload['extension'] ?? null;
    }
}

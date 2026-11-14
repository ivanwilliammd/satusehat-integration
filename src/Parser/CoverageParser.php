<?php

namespace Satusehat\Integration\Parser;

/**
 * Coverage — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Coverage.json, copied unchanged to
 * tests/Fixtures/satusehat/Coverage.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CoverageParser
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

    public function getType()
    {
        return $this->payload['type'] ?? null;
    }

    public function getSubscriberId()
    {
        return $this->payload['subscriberId'] ?? null;
    }

    public function getBeneficiary()
    {
        return $this->payload['beneficiary'] ?? null;
    }

    public function getPayor()
    {
        return $this->payload['payor'] ?? null;
    }

    public function getClass()
    {
        return $this->payload['class'] ?? null;
    }

    public function getExtension()
    {
        return $this->payload['extension'] ?? null;
    }
}

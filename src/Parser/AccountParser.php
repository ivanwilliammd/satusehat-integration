<?php

namespace Satusehat\Integration\Parser;

/**
 * Account — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Account.json, copied unchanged to
 * tests/Fixtures/satusehat/Account.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class AccountParser
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

    public function getType()
    {
        return $this->payload['type'] ?? null;
    }

    public function getName()
    {
        return $this->payload['name'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getServicePeriod()
    {
        return $this->payload['servicePeriod'] ?? null;
    }

    public function getOwner()
    {
        return $this->payload['owner'] ?? null;
    }

    public function getGuarantor()
    {
        return $this->payload['guarantor'] ?? null;
    }

    public function getDescription()
    {
        return $this->payload['description'] ?? null;
    }
}

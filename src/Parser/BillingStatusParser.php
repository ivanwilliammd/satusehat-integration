<?php

namespace Satusehat\Integration\Parser;

/**
 * BillingStatus — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/BillingStatus.json, copied unchanged to
 * tests/Fixtures/satusehat/BillingStatus.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class BillingStatusParser
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

    public function getId()
    {
        return $this->payload['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getInsurer()
    {
        return $this->payload['insurer'] ?? null;
    }

    public function getRecipient()
    {
        return $this->payload['recipient'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getRequest()
    {
        return $this->payload['request'] ?? null;
    }
}

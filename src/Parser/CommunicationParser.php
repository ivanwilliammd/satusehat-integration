<?php

namespace Satusehat\Integration\Parser;

/**
 * Communication — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Communication.json, copied unchanged to
 * tests/Fixtures/satusehat/Communication.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CommunicationParser
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

    public function getBasedOn()
    {
        return $this->payload['basedOn'] ?? null;
    }

    public function getPayload()
    {
        return $this->payload['payload'] ?? null;
    }

    public function getAbout()
    {
        return $this->payload['about'] ?? null;
    }

    public function getInResponseTo()
    {
        return $this->payload['inResponseTo'] ?? null;
    }

    public function getSender()
    {
        return $this->payload['sender'] ?? null;
    }

    public function getRecipient()
    {
        return $this->payload['recipient'] ?? null;
    }
}

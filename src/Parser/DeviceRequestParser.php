<?php

namespace Satusehat\Integration\Parser;

/**
 * DeviceRequest — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/DeviceRequest.json, copied unchanged to
 * tests/Fixtures/satusehat/DeviceRequest.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class DeviceRequestParser
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

    public function getPriority()
    {
        return $this->payload['priority'] ?? null;
    }

    public function getCodeReference()
    {
        return $this->payload['codeReference'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getAuthoredOn()
    {
        return $this->payload['authoredOn'] ?? null;
    }

    public function getRequester()
    {
        return $this->payload['requester'] ?? null;
    }

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->payload['reasonCode'] ?? null;
    }
}

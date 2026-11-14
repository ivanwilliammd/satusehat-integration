<?php

namespace Satusehat\Integration\Parser;

/**
 * DeviceUseStatement — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/DeviceUseStatement.json, copied unchanged to
 * tests/Fixtures/satusehat/DeviceUseStatement.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class DeviceUseStatementParser
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

    public function getDevice()
    {
        return $this->payload['device'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getTimingPeriod()
    {
        return $this->payload['timingPeriod'] ?? null;
    }

    public function getRecordedOn()
    {
        return $this->payload['recordedOn'] ?? null;
    }

    public function getSource()
    {
        return $this->payload['source'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->payload['reasonCode'] ?? null;
    }

    public function getNote()
    {
        return $this->payload['note'] ?? null;
    }
}

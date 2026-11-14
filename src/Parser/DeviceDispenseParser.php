<?php

namespace Satusehat\Integration\Parser;

/**
 * DeviceDispense — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/DeviceDispense.json, copied unchanged to
 * tests/Fixtures/satusehat/DeviceDispense.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class DeviceDispenseParser
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

    public function getBasedOn()
    {
        return $this->payload['basedOn'] ?? null;
    }

    public function getDevice()
    {
        return $this->payload['device'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getReceiver()
    {
        return $this->payload['receiver'] ?? null;
    }

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getWhenPrepared()
    {
        return $this->payload['whenPrepared'] ?? null;
    }

    public function getWhenHandedOver()
    {
        return $this->payload['whenHandedOver'] ?? null;
    }

    public function getDestination()
    {
        return $this->payload['destination'] ?? null;
    }

    public function getNote()
    {
        return $this->payload['note'] ?? null;
    }
}

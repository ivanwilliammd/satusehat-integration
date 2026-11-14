<?php

namespace Satusehat\Integration\Parser;

/**
 * SupplyDelivery — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/SupplyDelivery.json, copied unchanged to
 * tests/Fixtures/satusehat/SupplyDelivery.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class SupplyDeliveryParser
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

    public function getBasedOn()
    {
        return $this->payload['basedOn'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getSuppliedItem()
    {
        return $this->payload['suppliedItem'] ?? null;
    }

    public function getOccurrenceDateTime()
    {
        return $this->payload['occurrenceDateTime'] ?? null;
    }

    public function getSupplier()
    {
        return $this->payload['supplier'] ?? null;
    }

    public function getDestination()
    {
        return $this->payload['destination'] ?? null;
    }

    public function getReceiver()
    {
        return $this->payload['receiver'] ?? null;
    }
}

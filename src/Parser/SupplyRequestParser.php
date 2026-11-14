<?php

namespace Satusehat\Integration\Parser;

/**
 * SupplyRequest — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/SupplyRequest.json, copied unchanged to
 * tests/Fixtures/satusehat/SupplyRequest.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class SupplyRequestParser
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

    public function getItemReference()
    {
        return $this->payload['itemReference'] ?? null;
    }

    public function getQuantity()
    {
        return $this->payload['quantity'] ?? null;
    }

    public function getAuthoredOn()
    {
        return $this->payload['authoredOn'] ?? null;
    }

    public function getRequester()
    {
        return $this->payload['requester'] ?? null;
    }

    public function getSupplier()
    {
        return $this->payload['supplier'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->payload['reasonCode'] ?? null;
    }
}

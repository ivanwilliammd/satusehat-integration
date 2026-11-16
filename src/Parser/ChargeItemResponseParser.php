<?php

namespace Satusehat\Integration\Parser;

/**
 * ChargeItemResponse — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/ChargeItemResponse.json, copied unchanged to
 * tests/Fixtures/satusehat/ChargeItemResponse.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ChargeItemResponseParser
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

    public function getChargeItem()
    {
        return $this->payload['chargeItem'] ?? null;
    }

    public function getCoverage()
    {
        return $this->payload['coverage'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getPayor()
    {
        return $this->payload['payor'] ?? null;
    }

    public function getQuantity()
    {
        return $this->payload['quantity'] ?? null;
    }

    public function getUnitPriceOverride()
    {
        return $this->payload['unitPriceOverride'] ?? null;
    }

    public function getFactorOverride()
    {
        return $this->payload['factorOverride'] ?? null;
    }

    public function getOverrideReason()
    {
        return $this->payload['overrideReason'] ?? null;
    }

    public function getTotal()
    {
        return $this->payload['total'] ?? null;
    }

    public function getExcess()
    {
        return $this->payload['excess'] ?? null;
    }

    public function getPartOf()
    {
        return $this->data['partOf'] ?? null;
    }

    public function getRequest()
    {
        return $this->data['request'] ?? null;
    }

    public function getOutcome()
    {
        return $this->data['outcome'] ?? null;
    }

    public function getDescription()
    {
        return $this->data['description'] ?? null;
    }

    public function getCreated()
    {
        return $this->data['created'] ?? null;
    }

    public function getRequestor()
    {
        return $this->data['requestor'] ?? null;
    }

    public function getOutcomeInfo()
    {
        return $this->data['outcomeInfo'] ?? null;
    }

    public function getError()
    {
        return $this->data['error'] ?? null;
    }

    public function getCostCenterChargeItemResponse()
    {
        return $this->data['costCenterChargeItemResponse'] ?? null;
    }

    public function getResponseDate()
    {
        return $this->data['responseDate'] ?? null;
    }
}

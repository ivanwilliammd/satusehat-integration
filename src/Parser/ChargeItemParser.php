<?php

namespace Satusehat\Integration\Parser;

/**
 * ChargeItem — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/ChargeItem.json, copied unchanged to
 * tests/Fixtures/satusehat/ChargeItem.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ChargeItemParser
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

    public function getCode()
    {
        return $this->payload['code'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getContext()
    {
        return $this->payload['context'] ?? null;
    }

    public function getOccurrencePeriod()
    {
        return $this->payload['occurrencePeriod'] ?? null;
    }

    public function getPerformer()
    {
        return $this->payload['performer'] ?? null;
    }

    public function getQuantity()
    {
        return $this->payload['quantity'] ?? null;
    }

    public function getAccount()
    {
        return $this->payload['account'] ?? null;
    }

    public function getExtension()
    {
        return $this->payload['extension'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getPartOf()
    {
        return $this->data['partOf'] ?? null;
    }

    public function getFocus()
    {
        return $this->data['focus'] ?? null;
    }

    public function getPerformedDateTime()
    {
        return $this->data['performedDateTime'] ?? null;
    }

    public function getPerformingOrganization()
    {
        return $this->data['performingOrganization'] ?? null;
    }

    public function getRequestingOrganization()
    {
        return $this->data['requestingOrganization'] ?? null;
    }

    public function getBodysite()
    {
        return $this->data['bodysite'] ?? null;
    }

    public function getProductCodeableConcept()
    {
        return $this->data['productCodeableConcept'] ?? null;
    }

    public function getPriceOverride()
    {
        return $this->data['priceOverride'] ?? null;
    }

    public function getOverrideReason()
    {
        return $this->data['overrideReason'] ?? null;
    }

    public function getEnterer()
    {
        return $this->data['enterer'] ?? null;
    }

    public function getEnteredDate()
    {
        return $this->data['enteredDate'] ?? null;
    }

    public function getReason()
    {
        return $this->data['reason'] ?? null;
    }

    public function getService()
    {
        return $this->data['service'] ?? null;
    }
}

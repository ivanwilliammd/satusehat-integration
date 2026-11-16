<?php

namespace Satusehat\Integration\Parser;

/**
 * ClaimResponse — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/ClaimResponse.json, copied unchanged to
 * tests/Fixtures/satusehat/ClaimResponse.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ClaimResponseParser
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

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
    }

    public function getType()
    {
        return $this->payload['type'] ?? null;
    }

    public function getUse()
    {
        return $this->payload['use'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getInsurer()
    {
        return $this->payload['insurer'] ?? null;
    }

    public function getRequestor()
    {
        return $this->payload['requestor'] ?? null;
    }

    public function getRequest()
    {
        return $this->payload['request'] ?? null;
    }

    public function getOutcome()
    {
        return $this->payload['outcome'] ?? null;
    }

    public function getPayeeType()
    {
        return $this->payload['payeeType'] ?? null;
    }

    public function getAdjudication()
    {
        return $this->payload['adjudication'] ?? null;
    }

    public function getItem()
    {
        return $this->payload['item'] ?? null;
    }

    public function getTotal()
    {
        return $this->payload['total'] ?? null;
    }

    public function getPayment()
    {
        return $this->payload['payment'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getSubType()
    {
        return $this->data['subType'] ?? null;
    }

    public function getDisposition()
    {
        return $this->data['disposition'] ?? null;
    }

    public function getPreAuthRef()
    {
        return $this->data['preAuthRef'] ?? null;
    }

    public function getPreAuthPeriod()
    {
        return $this->data['preAuthPeriod'] ?? null;
    }

    public function getAddItem()
    {
        return $this->data['addItem'] ?? null;
    }

    public function getError()
    {
        return $this->data['error'] ?? null;
    }

    public function getCoverage()
    {
        return $this->data['coverage'] ?? null;
    }

    public function getInsurance()
    {
        return $this->data['insurance'] ?? null;
    }
}

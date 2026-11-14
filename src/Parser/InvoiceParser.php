<?php

namespace Satusehat\Integration\Parser;

/**
 * Invoice — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Invoice.json, copied unchanged to
 * tests/Fixtures/satusehat/Invoice.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class InvoiceParser
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

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getDate()
    {
        return $this->payload['date'] ?? null;
    }

    public function getIssuer()
    {
        return $this->payload['issuer'] ?? null;
    }

    public function getAccount()
    {
        return $this->payload['account'] ?? null;
    }

    public function getLineItem()
    {
        return $this->payload['lineItem'] ?? null;
    }

    public function getTotalNet()
    {
        return $this->payload['totalNet'] ?? null;
    }
}

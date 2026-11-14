<?php

namespace Satusehat\Integration\Parser;

/**
 * PaymentReconciliation — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/PaymentReconciliation.json, copied unchanged to
 * tests/Fixtures/satusehat/PaymentReconciliation.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class PaymentReconciliationParser
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

    public function getPeriod()
    {
        return $this->payload['period'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getPaymentIssuer()
    {
        return $this->payload['paymentIssuer'] ?? null;
    }

    public function getRequestor()
    {
        return $this->payload['requestor'] ?? null;
    }

    public function getOutcome()
    {
        return $this->payload['outcome'] ?? null;
    }

    public function getDisposition()
    {
        return $this->payload['disposition'] ?? null;
    }

    public function getPaymentDate()
    {
        return $this->payload['paymentDate'] ?? null;
    }

    public function getPaymentAmount()
    {
        return $this->payload['paymentAmount'] ?? null;
    }

    public function getDetail()
    {
        return $this->payload['detail'] ?? null;
    }

    public function getProcessNote()
    {
        return $this->payload['processNote'] ?? null;
    }
}

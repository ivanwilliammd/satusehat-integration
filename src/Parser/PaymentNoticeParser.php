<?php

namespace Satusehat\Integration\Parser;

/**
 * PaymentNotice — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/PaymentNotice.json, copied unchanged to
 * tests/Fixtures/satusehat/PaymentNotice.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class PaymentNoticeParser
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

    public function getRequest()
    {
        return $this->payload['request'] ?? null;
    }

    public function getResponse()
    {
        return $this->payload['response'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getPaymentDate()
    {
        return $this->payload['paymentDate'] ?? null;
    }

    public function getProvider()
    {
        return $this->payload['provider'] ?? null;
    }

    public function getPayee()
    {
        return $this->payload['payee'] ?? null;
    }

    public function getRecipient()
    {
        return $this->payload['recipient'] ?? null;
    }

    public function getAmount()
    {
        return $this->payload['amount'] ?? null;
    }

    public function getPayment()
    {
        return $this->payload['payment'] ?? null;
    }

    public function getPaymentStatus()
    {
        return $this->payload['paymentStatus'] ?? null;
    }
}

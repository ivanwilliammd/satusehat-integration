<?php

namespace Satusehat\Integration\Parser;

/**
 * Claim — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Claim.json, copied unchanged to
 * tests/Fixtures/satusehat/Claim.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ClaimParser
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

    public function getBillablePeriod()
    {
        return $this->payload['billablePeriod'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getEnterer()
    {
        return $this->payload['enterer'] ?? null;
    }

    public function getInsurer()
    {
        return $this->payload['insurer'] ?? null;
    }

    public function getProvider()
    {
        return $this->payload['provider'] ?? null;
    }

    public function getPriority()
    {
        return $this->payload['priority'] ?? null;
    }

    public function getPayee()
    {
        return $this->payload['payee'] ?? null;
    }

    public function getFacility()
    {
        return $this->payload['facility'] ?? null;
    }

    public function getSupportingInfo()
    {
        return $this->payload['supportingInfo'] ?? null;
    }

    public function getDiagnosis()
    {
        return $this->payload['diagnosis'] ?? null;
    }

    public function getProcedure()
    {
        return $this->payload['procedure'] ?? null;
    }

    public function getInsurance()
    {
        return $this->payload['insurance'] ?? null;
    }

    public function getTotal()
    {
        return $this->payload['total'] ?? null;
    }

    public function getExtension()
    {
        return $this->payload['extension'] ?? null;
    }
}

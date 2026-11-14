<?php

namespace Satusehat\Integration\Parser;

/**
 * VisionPrescription — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/VisionPrescription.json, copied unchanged to
 * tests/Fixtures/satusehat/VisionPrescription.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class VisionPrescriptionParser
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

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getDateWritten()
    {
        return $this->payload['dateWritten'] ?? null;
    }

    public function getPrescriber()
    {
        return $this->payload['prescriber'] ?? null;
    }

    public function getLensSpecification()
    {
        return $this->payload['lensSpecification'] ?? null;
    }
}

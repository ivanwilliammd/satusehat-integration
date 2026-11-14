<?php

namespace Satusehat\Integration\Parser;

/**
 * EpisodeOfCare — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/EpisodeOfCare.json, copied unchanged to
 * tests/Fixtures/satusehat/EpisodeOfCare.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class EpisodeOfCareParser
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

    public function getStatusHistory()
    {
        return $this->payload['statusHistory'] ?? null;
    }

    public function getType()
    {
        return $this->payload['type'] ?? null;
    }

    public function getDiagnosis()
    {
        return $this->payload['diagnosis'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getManagingOrganization()
    {
        return $this->payload['managingOrganization'] ?? null;
    }

    public function getPeriod()
    {
        return $this->payload['period'] ?? null;
    }

    public function getCareManager()
    {
        return $this->payload['careManager'] ?? null;
    }
}

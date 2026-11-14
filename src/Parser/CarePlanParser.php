<?php

namespace Satusehat\Integration\Parser;

/**
 * CarePlan — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/CarePlan.json, copied unchanged to
 * tests/Fixtures/satusehat/CarePlan.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CarePlanParser
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

    public function getTitle()
    {
        return $this->payload['title'] ?? null;
    }

    public function getDescription()
    {
        return $this->payload['description'] ?? null;
    }

    public function getCategory()
    {
        return $this->payload['category'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getIntent()
    {
        return $this->payload['intent'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getPeriod()
    {
        return $this->payload['period'] ?? null;
    }

    public function getCreated()
    {
        return $this->payload['created'] ?? null;
    }

    public function getAuthor()
    {
        return $this->payload['author'] ?? null;
    }

    public function getActivity()
    {
        return $this->payload['activity'] ?? null;
    }
}

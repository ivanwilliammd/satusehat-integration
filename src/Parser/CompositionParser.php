<?php

namespace Satusehat\Integration\Parser;

/**
 * Composition — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Composition.json, copied unchanged to
 * tests/Fixtures/satusehat/Composition.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class CompositionParser
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

    public function getAttester()
    {
        return $this->payload['attester'] ?? null;
    }

    public function getType()
    {
        return $this->payload['type'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getDate()
    {
        return $this->payload['date'] ?? null;
    }

    public function getAuthor()
    {
        return $this->payload['author'] ?? null;
    }

    public function getTitle()
    {
        return $this->payload['title'] ?? null;
    }

    public function getSection()
    {
        return $this->payload['section'] ?? null;
    }
}

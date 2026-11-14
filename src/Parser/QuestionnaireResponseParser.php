<?php

namespace Satusehat\Integration\Parser;

/**
 * QuestionnaireResponse — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/QuestionnaireResponse.json, copied unchanged to
 * tests/Fixtures/satusehat/QuestionnaireResponse.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class QuestionnaireResponseParser
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

    public function getQuestionnaire()
    {
        return $this->payload['questionnaire'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getAuthored()
    {
        return $this->payload['authored'] ?? null;
    }

    public function getAuthor()
    {
        return $this->payload['author'] ?? null;
    }

    public function getSource()
    {
        return $this->payload['source'] ?? null;
    }

    public function getItem()
    {
        return $this->payload['item'] ?? null;
    }
}

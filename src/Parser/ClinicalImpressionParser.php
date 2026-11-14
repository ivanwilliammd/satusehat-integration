<?php

namespace Satusehat\Integration\Parser;

/**
 * ClinicalImpression — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/ClinicalImpression.json, copied unchanged to
 * tests/Fixtures/satusehat/ClinicalImpression.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ClinicalImpressionParser
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

    public function getCode()
    {
        return $this->payload['code'] ?? null;
    }

    public function getDescription()
    {
        return $this->payload['description'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getEffectiveDateTime()
    {
        return $this->payload['effectiveDateTime'] ?? null;
    }

    public function getDate()
    {
        return $this->payload['date'] ?? null;
    }

    public function getAssessor()
    {
        return $this->payload['assessor'] ?? null;
    }

    public function getProblem()
    {
        return $this->payload['problem'] ?? null;
    }

    public function getInvestigation()
    {
        return $this->payload['investigation'] ?? null;
    }

    public function getSummary()
    {
        return $this->payload['summary'] ?? null;
    }

    public function getFinding()
    {
        return $this->payload['finding'] ?? null;
    }

    public function getPrognosisCodeableConcept()
    {
        return $this->payload['prognosisCodeableConcept'] ?? null;
    }
}

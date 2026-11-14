<?php

namespace Satusehat\Integration\Parser;

/**
 * ImagingStudy — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/ImagingStudy.json, copied unchanged to
 * tests/Fixtures/satusehat/ImagingStudy.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class ImagingStudyParser
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

    public function getModality()
    {
        return $this->payload['modality'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getStarted()
    {
        return $this->payload['started'] ?? null;
    }

    public function getBasedOn()
    {
        return $this->payload['basedOn'] ?? null;
    }

    public function getNumberOfSeries()
    {
        return $this->payload['numberOfSeries'] ?? null;
    }

    public function getNumberOfInstances()
    {
        return $this->payload['numberOfInstances'] ?? null;
    }

    public function getDescription()
    {
        return $this->payload['description'] ?? null;
    }

    public function getSeries()
    {
        return $this->payload['series'] ?? null;
    }
}

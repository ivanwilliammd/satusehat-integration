<?php

namespace Satusehat\Integration\Parser;

/**
 * FamilyMemberHistory — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/FamilyMemberHistory.json, copied unchanged to
 * tests/Fixtures/satusehat/FamilyMemberHistory.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class FamilyMemberHistoryParser
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

    public function getRelationship()
    {
        return $this->payload['relationship'] ?? null;
    }

    public function getDeceasedBoolean()
    {
        return $this->payload['deceasedBoolean'] ?? null;
    }

    public function getPatient()
    {
        return $this->payload['patient'] ?? null;
    }

    public function getDate()
    {
        return $this->payload['date'] ?? null;
    }

    public function getCondition()
    {
        return $this->payload['condition'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getInstantiatesCanonical()
    {
        return $this->data['instantiatesCanonical'] ?? null;
    }

    public function getDataAbsentReason()
    {
        return $this->data['dataAbsentReason'] ?? null;
    }

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getSex()
    {
        return $this->data['sex'] ?? null;
    }

    public function getBornPeriod()
    {
        return $this->data['bornPeriod'] ?? null;
    }

    public function getBornDate()
    {
        return $this->data['bornDate'] ?? null;
    }

    public function getBornString()
    {
        return $this->data['bornString'] ?? null;
    }

    public function getAgeAge()
    {
        return $this->data['ageAge'] ?? null;
    }

    public function getAgeUnit()
    {
        return $this->data['ageUnit'] ?? null;
    }

    public function getAgeRange()
    {
        return $this->data['ageRange'] ?? null;
    }

    public function getAgeString()
    {
        return $this->data['ageString'] ?? null;
    }

    public function getDeceasedAge()
    {
        return $this->data['deceasedAge'] ?? null;
    }

    public function getDeceasedAgeUnit()
    {
        return $this->data['deceasedAgeUnit'] ?? null;
    }

    public function getDeceasedRange()
    {
        return $this->data['deceasedRange'] ?? null;
    }

    public function getDeceasedDate()
    {
        return $this->data['deceasedDate'] ?? null;
    }

    public function getDeceasedString()
    {
        return $this->data['deceasedString'] ?? null;
    }

    public function getDeceasedCodeableConcept()
    {
        return $this->data['deceasedCodeableConcept'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->data['reasonCode'] ?? null;
    }

    public function getReasonReference()
    {
        return $this->data['reasonReference'] ?? null;
    }

    public function getNote()
    {
        return $this->data['note'] ?? null;
    }
}

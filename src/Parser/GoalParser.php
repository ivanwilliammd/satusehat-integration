<?php

namespace Satusehat\Integration\Parser;

/**
 * Goal — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/Goal.json, copied unchanged to
 * tests/Fixtures/satusehat/Goal.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class GoalParser
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

    public function getLifecycleStatus()
    {
        return $this->payload['lifecycleStatus'] ?? null;
    }

    public function getAchievementStatus()
    {
        return $this->payload['achievementStatus'] ?? null;
    }

    public function getCategory()
    {
        return $this->payload['category'] ?? null;
    }

    public function getDescription()
    {
        return $this->payload['description'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getTarget()
    {
        return $this->payload['target'] ?? null;
    }

    public function getStatusDate()
    {
        return $this->payload['statusDate'] ?? null;
    }

    public function getExpressedBy()
    {
        return $this->payload['expressedBy'] ?? null;
    }

    public function getAddresses()
    {
        return $this->payload['addresses'] ?? null;
    }

    public function getOutcomeCode()
    {
        return $this->payload['outcomeCode'] ?? null;
    }
}

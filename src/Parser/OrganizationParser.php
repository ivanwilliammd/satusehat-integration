<?php

namespace Satusehat\Integration\Parser;

class OrganizationParser
{
    private $organization;

    public function __construct($organization)
    {
        $this->organization = $organization;
    }

    public function getIdentifiers()
    {
        return $this->organization['identifier'] ?? null;
    }

    public function getName()
    {
        return $this->organization['name'] ?? null;
    }

    public function getOperationalStatus()
    {
        return $this->organization['extension'] ?? null;
    }

    public function getPartOf()
    {
        return $this->organization['partOf'] ?? null;
    }

    public function getPartOfReference()
    {
        return $this->removePrefix($this->organization['partOf']['reference'] ?? null, 'Organization/');
    }

    public function getType()
    {
        return $this->organization['type'] ?? null;
    }

    public function getTelecom()
    {
        return $this->organization['telecom'] ?? null;
    }

    public function getAddress()
    {
        return $this->organization['address'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

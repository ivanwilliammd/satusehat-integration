<?php

namespace Satusehat\Integration\Parser;

class LocationParser
{
    private $location;

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function getIdentifiers()
    {
        return $this->location['identifier'] ?? null;
    }

    public function getName()
    {
        return $this->location['name'] ?? null;
    }

    public function getStatus()
    {
        return $this->location['status'] ?? null;
    }

    public function getOperationalStatus()
    {
        return $this->location['operationalStatus'] ?? null;
    }

    public function getTelecom()
    {
        return $this->location['telecom'] ?? null;
    }

    public function getAddress()
    {
        return $this->location['address'] ?? null;
    }

    public function getPhysicalType()
    {
        return $this->location['physicalType'] ?? null;
    }

    public function getPosition()
    {
        return $this->location['position'] ?? null;
    }

    public function getManagingOrganization()
    {
        return $this->location['managingOrganization'] ?? null;
    }

    public function getManagingOrganizationReference()
    {
        return $this->removePrefix($this->location['managingOrganization']['reference'] ?? null, 'Organization/');
    }

    public function getPartOf()
    {
        return $this->location['partOf'] ?? null;
    }

    public function getPartOfReference()
    {
        return $this->removePrefix($this->location['partOf']['reference'] ?? null, 'Location/');
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

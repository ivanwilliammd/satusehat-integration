<?php

namespace Satusehat\Integration\Parser;

class DeviceParser
{
    private $device;

    public function __construct($device)
    {
        $this->device = $device;
    }

    public function getIdentifiers()
    {
        return $this->device['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->device['status'] ?? null;
    }

    public function getManufacturer()
    {
        return $this->device['manufacturer'] ?? null;
    }

    public function getDeviceNames()
    {
        return $this->device['deviceName'] ?? null;
    }

    public function getType()
    {
        return $this->device['type'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getDeviceName()
    {
        return $this->data['deviceName'] ?? null;
    }

    public function getPatient()
    {
        return $this->data['patient'] ?? null;
    }

    public function getOwner()
    {
        return $this->data['owner'] ?? null;
    }

    public function getLocation()
    {
        return $this->data['location'] ?? null;
    }

    public function getSerialNumber()
    {
        return $this->data['serialNumber'] ?? null;
    }

    public function getNote()
    {
        return $this->data['note'] ?? null;
    }
}

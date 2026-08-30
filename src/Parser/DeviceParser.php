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
}

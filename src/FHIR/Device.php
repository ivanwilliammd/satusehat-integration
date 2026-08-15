<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Device extends OAuth2Client
{
    public array $device = ['resourceType' => 'Device'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->device['identifier'][] = $identifier;
    }

    public function setStatus($status)
    {
        $this->device['status'] = $status;
    }

    public function setManufacturer($manufacturer)
    {
        $this->device['manufacturer'] = $manufacturer;
    }

    public function addDeviceName($name, $type)
    {
        $deviceName = [
            'name' => $name,
            'type' => $type,
        ];

        $this->device['deviceName'][] = $deviceName;
    }

    public function setType($code, $display)
    {
        $this->device['type'] = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setPatient($reference, $display = null)
    {
        $this->device['patient'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->device['patient']['display'] = $display;
        }
    }

    public function setOwner($reference, $display = null)
    {
        $this->device['owner'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->device['owner']['display'] = $display;
        }
    }

    public function setLocation($reference, $display = null)
    {
        $this->device['location'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->device['location']['display'] = $display;
        }
    }

    public function setSerialNumber($value)
    {
        $this->device['serialNumber'] = $value;
    }

    public function addNote($text)
    {
        $this->device['note'][] = [
            'text' => $text,
        ];
    }

    public function json()
    {
        if (! array_key_exists('status', $this->device)) {
            throw new FHIRMissingProperty('Device.status is required');
        }

        if (! array_key_exists('manufacturer', $this->device)) {
            throw new FHIRMissingProperty('Device.manufacturer is required');
        }

        if (! array_key_exists('type', $this->device)) {
            throw new FHIRMissingProperty('Device.type is required');
        }

        return json_encode($this->device, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Device', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->device['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Device', $id, $payload);

        return [$statusCode, $res];
    }
}

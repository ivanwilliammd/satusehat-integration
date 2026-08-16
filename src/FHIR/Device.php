<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Builder\PayloadBuilderDevice;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

/**
 * Device FHIR R4 Resource
 * @link https://www.hl7.org/fhir/device.html
 *
 * Uses PayloadBuilderDevice for clean typed building.
 * Backward compatible: still extends OAuth2Client for old SSRequest pattern.
 */
class Device extends OAuth2Client
{
    public array $device = ['resourceType' => 'Device'];

    public function addIdentifier($system, $value)
    {
        $this->device['identifier'][] = [
            'system' => $system,
            'value' => $value,
        ];
        return $this;
    }

    public function setStatus($status)
    {
        $this->device['status'] = $status;
        return $this;
    }

    public function setManufacturer($manufacturer)
    {
        $this->device['manufacturer'] = $manufacturer;
        return $this;
    }

    public function addDeviceName($name, $type = 'user-friendly-name')
    {
        $this->device['deviceName'][] = [
            'name' => $name,
            'type' => $type,
        ];
        return $this;
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
        return $this;
    }

    public function setPatient($reference, $display = null)
    {
        $this->device['patient'] = ['reference' => $reference];
        if ($display !== null) {
            $this->device['patient']['display'] = $display;
        }
        return $this;
    }

    public function setOwner($reference, $display = null)
    {
        $this->device['owner'] = ['reference' => $reference];
        if ($display !== null) {
            $this->device['owner']['display'] = $display;
        }
        return $this;
    }

    public function setLocation($reference, $display = null)
    {
        $this->device['location'] = ['reference' => $reference];
        if ($display !== null) {
            $this->device['location']['display'] = $display;
        }
        return $this;
    }

    public function setSerialNumber($value)
    {
        $this->device['serialNumber'] = $value;
        return $this;
    }

    public function addNote($text)
    {
        $this->device['note'][] = ['text' => $text];
        return $this;
    }

    /**
     * Build using PayloadBuilderDevice (Phase 1 pattern).
     * Returns the builder instance for chaining.
     */
    public static function build(): PayloadBuilderDevice
    {
        return new PayloadBuilderDevice();
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
        [$statusCode, $res] = $this->ss_post('Device', $this->json());
        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->device['id'] = $id;
        [$statusCode, $res] = $this->ss_put('Device', $id, $this->json());
        return [$statusCode, $res];
    }
}

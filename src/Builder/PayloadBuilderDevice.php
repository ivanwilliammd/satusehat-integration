<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * Device FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/device.html
 */
class PayloadBuilderDevice extends Builder
{
    protected string $resourceType = 'Device';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->set('manufacturer', $manufacturer);
        return $this;
    }

    /**
     * @param string $name Device name
     * @param string $type DeviceName.type: "user-friendly-name" | "manufacturer-name" | "model-name" | "other"
     */
    public function addDeviceName(string $name, string $type = 'user-friendly-name'): self
    {
        $this->push('deviceName', [
            'name' => $name,
            'type' => $type,
        ]);
        return $this;
    }

    public function setType(CodeableConcept $type): self
    {
        $this->set('type', $type->toArray());
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setOwner(Reference $owner): self
    {
        $this->set('owner', $owner->toArray());
        return $this;
    }

    public function setLocation(Reference $location): self
    {
        $this->set('location', $location->toArray());
        return $this;
    }

    public function setSerialNumber(string $value): self
    {
        $this->set('serialNumber', $value);
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }
}

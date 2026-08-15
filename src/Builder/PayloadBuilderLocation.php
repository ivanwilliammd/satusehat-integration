<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * Location FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/location.html
 */
class PayloadBuilderLocation extends Builder
{
    protected string $resourceType = 'Location';

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

    public function setOperationalStatus(CodeableConcept $operationalStatus): self
    {
        $this->set('operationalStatus', $operationalStatus->toArray());
        return $this;
    }

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function addAlias(string $alias): self
    {
        $this->push('alias', $alias);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setType(CodeableConcept $type): self
    {
        $this->set('type', $type->toArray());
        return $this;
    }

    public function addTelecom(ContactPoint $telecom): self
    {
        $this->push('telecom', $telecom->toArray());
        return $this;
    }

    public function addAddress(Address $address): self
    {
        $this->push('address', $address->toArray());
        return $this;
    }

    public function setPhysicalType(CodeableConcept $physicalType): self
    {
        $this->set('physicalType', $physicalType->toArray());
        return $this;
    }

    public function setPosition(
        ?float $latitude = null,
        ?float $longitude = null,
        ?float $altitude = null
    ): self {
        $position = [];

        if ($latitude !== null) {
            $position['latitude'] = $latitude;
        }

        if ($longitude !== null) {
            $position['longitude'] = $longitude;
        }

        if ($altitude !== null) {
            $position['altitude'] = $altitude;
        }

        $this->set('position', $position);
        return $this;
    }

    public function setManagingOrganization(Reference $managingOrganization): self
    {
        $this->set('managingOrganization', $managingOrganization->toArray());
        return $this;
    }

    public function setPartOf(Reference $partOf): self
    {
        $this->set('partOf', $partOf->toArray());
        return $this;
    }

    public function addEndpoint(Reference $endpoint): self
    {
        $this->push('endpoint', $endpoint->toArray());
        return $this;
    }

    public function addExtension(string $url, mixed $value, ?string $valueType = null): self
    {
        $extension = ['url' => $url];

        if ($valueType !== null) {
            $extension['value' . ucfirst($valueType)] = $value;
        } else {
            $extension['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('extension', $extension);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * Organization FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/organization.html
 */
class PayloadBuilderOrganization extends Builder
{
    protected string $resourceType = 'Organization';

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

    public function setActive(bool $active): self
    {
        $this->set('active', $active);
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

    public function setPartOf(Reference $partOf): self
    {
        $this->set('partOf', $partOf->toArray());
        return $this;
    }

    public function addContact(
        ContactPoint $telecom,
        ?string $purpose = null,
        ?string $name = null,
        ?Address $address = null
    ): self {
        $contact = ['telecom' => [$telecom->toArray()]];

        if ($purpose !== null) {
            $contact['purpose'] = ['text' => $purpose];
        }

        if ($name !== null) {
            $contact['name'] = ['text' => $name];
        }

        if ($address !== null) {
            $contact['address'] = $address->toArray();
        }

        $this->push('contact', $contact);
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

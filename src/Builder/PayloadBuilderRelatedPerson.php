<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * RelatedPerson FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/relatedperson.html
 */
class PayloadBuilderRelatedPerson extends Builder
{
    protected string $resourceType = 'RelatedPerson';

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

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function addRelationship(CodeableConcept $relationship): self
    {
        $this->push('relationship', $relationship->toArray());
        return $this;
    }

    public function addName(HumanName $name): self
    {
        $this->push('name', $name->toArray());
        return $this;
    }

    public function addTelecom(ContactPoint $telecom): self
    {
        $this->push('telecom', $telecom->toArray());
        return $this;
    }

    public function setGender(string $gender): self
    {
        $this->set('gender', $gender);
        return $this;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->set('birthDate', $birthDate);
        return $this;
    }

    public function addAddress(Address $address): self
    {
        $this->push('address', $address->toArray());
        return $this;
    }

    public function addCommunication(CodeableConcept $language, bool $preferred = true): self
    {
        $this->push('communication', [
            'language' => $language->toArray(),
            'preferred' => $preferred,
        ]);
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

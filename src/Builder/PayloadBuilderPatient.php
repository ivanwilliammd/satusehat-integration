<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * Patient FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/patient.html
 */
class PayloadBuilderPatient extends Builder
{
    protected string $resourceType = 'Patient';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setMetaProfile(string $profile): self
    {
        $this->push('meta/profile', $profile);
        return $this;
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

    public function addName(HumanName $name): self
    {
        $this->push('name', $name->toArray());
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

    public function setDeceasedBoolean(bool $deceased): self
    {
        $this->set('deceasedBoolean', $deceased);
        return $this;
    }

    public function setDeceasedDateTime(string $dateTime): self
    {
        $this->set('deceasedDateTime', $dateTime);
        return $this;
    }

    public function setMultipleBirthBoolean(bool $multipleBirth): self
    {
        $this->set('multipleBirthBoolean', $multipleBirth);
        return $this;
    }

    public function setMultipleBirthInteger(int $multipleBirth): self
    {
        $this->set('multipleBirthInteger', $multipleBirth);
        return $this;
    }

    public function addAddress(Address $address): self
    {
        $this->push('address', $address->toArray());
        return $this;
    }

    public function addTelecom(ContactPoint $telecom): self
    {
        $this->push('telecom', $telecom->toArray());
        return $this;
    }

    public function setMaritalStatus(CodeableConcept $maritalStatus): self
    {
        $this->set('maritalStatus', $maritalStatus->toArray());
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

    public function addContact(
        CodeableConcept $relationship,
        HumanName $name,
        ContactPoint $telecom,
        ?Address $address = null,
        ?Reference $organization = null
    ): self {
        $contact = [
            'relationship' => [$relationship->toArray()],
            'name' => $name->toArray(),
            'telecom' => [$telecom->toArray()],
        ];

        if ($address !== null) {
            $contact['address'] = $address->toArray();
        }

        if ($organization !== null) {
            $contact['organization'] = $organization->toArray();
        }

        $this->push('contact', $contact);
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

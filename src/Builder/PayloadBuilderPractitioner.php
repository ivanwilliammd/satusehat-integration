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
 * Practitioner FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/practitioner.html
 */
class PayloadBuilderPractitioner extends Builder
{
    protected string $resourceType = 'Practitioner';

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

    public function addAddress(Address $address): self
    {
        $this->push('address', $address->toArray());
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

    public function addPhoto(string $url, ?string $contentType = null): self
    {
        $photo = ['url' => $url];

        if ($contentType !== null) {
            $photo['contentType'] = $contentType;
        }

        $this->push('photo', $photo);
        return $this;
    }

    public function addQualification(
        Identifier $identifier,
        CodeableConcept $code,
        ?string $periodStart = null,
        ?Reference $issuer = null
    ): self {
        $qualification = [
            'identifier' => [$identifier->toArray()],
            'code' => $code->toArray(),
        ];

        if ($periodStart !== null) {
            $qualification['period'] = ['start' => $periodStart];
        }

        if ($issuer !== null) {
            $qualification['issuer'] = $issuer->toArray();
        }

        $this->push('qualification', $qualification);
        return $this;
    }

    public function addCommunication(CodeableConcept $language, ?bool $preferred = null): self
    {
        $communication = ['language' => $language->toArray()];

        if ($preferred !== null) {
            $communication['preferred'] = $preferred;
        }

        $this->push('communication', $communication);
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

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

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

    public function addIdentifier(Identifier|string $identifier, ?string $value = null): self
    {
        if ($identifier instanceof Identifier) {
            $this->push('identifier', $identifier->toArray());
        } else {
            $this->push('identifier', ['system' => $identifier, 'value' => $value]);
        }
        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->set('active', $active);
        return $this;
    }

    public function setPatient(Reference|string $patient, ?string $display = null): self
    {
        if ($patient instanceof Reference) {
            $this->set('patient', $patient->toArray());
        } else {
            if (strpos($patient, '/') === false) {
                $patient = 'Patient/' . $patient;
            }
            $this->set('patient', array_filter([
                'reference' => $patient,
                'display' => $display,
            ], fn($v) => $v !== null));
        }
        return $this;
    }

    public function addRelationship(CodeableConcept|string $relationship, ?string $display = null): self
    {
        if ($relationship instanceof CodeableConcept) {
            $this->push('relationship', $relationship->toArray());
        } else {
            $this->push('relationship', [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                        'code' => $relationship,
                        'display' => $display,
                    ],
                ],
            ]);
        }
        return $this;
    }

    public function addName(HumanName|string $name, ?string $text = null): self
    {
        if ($name instanceof HumanName) {
            $this->push('name', $name->toArray());
        } else {
            $this->push('name', [
                'use' => 'official',
                'text' => $name,
            ]);
        }
        return $this;
    }

    public function addTelecom(ContactPoint|string $system, ?string $value = null, string $use = 'home'): self
    {
        if ($system instanceof ContactPoint) {
            $this->push('telecom', $system->toArray());
        } else {
            $this->push('telecom', [
                'system' => $system,
                'value' => $value,
                'use' => $use,
            ]);
        }
        return $this;
    }

    public function addAddress(Address|array $address): self
    {
        if ($address instanceof Address) {
            $this->push('address', $address->toArray());
        } else {
            $this->push('address', $address);
        }
        return $this;
    }

    public function addCommunication(CodeableConcept|string $language, ?bool $preferred = true): self
    {
        $comm = [];
        if ($language instanceof CodeableConcept) {
            $comm['language'] = $language->toArray();
        } else {
            $comm['language'] = [
                'coding' => [
                    [
                        'system' => 'urn:ietf:bcp:47',
                        'code' => $language,
                    ],
                ],
            ];
        }
        $comm['preferred'] = $preferred ?? true;
        $this->push('communication', $comm);
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

    public function addExtension(string $url, mixed $value): self
    {
        $extension = ['url' => $url];
        if (is_bool($value)) {
            $extension['valueBoolean'] = $value;
        } elseif (is_string($value)) {
            $extension['valueString'] = $value;
        } elseif (is_int($value)) {
            $extension['valueInteger'] = $value;
        } elseif (is_array($value)) {
            $extension = array_merge($extension, $value);
        }
        $this->push('extension', $extension);
        return $this;
    }
}

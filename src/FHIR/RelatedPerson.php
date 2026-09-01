<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class RelatedPerson extends OAuth2Client
{
    public array $relatedPerson = ['resourceType' => 'RelatedPerson'];

    public function relatedPerson(): array
    {
        return $this->relatedPerson;
    }

    public function addIdentifier($system, $value, $use = null): self
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        if ($use !== null) {
            $identifier['use'] = $use;
        }

        $this->relatedPerson['identifier'][] = $identifier;
        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->relatedPerson['active'] = $active;
        return $this;
    }

    public function setPatient(string $reference, ?string $display = null): self
    {
        if (strpos($reference, '/') === false) {
            $reference = 'Patient/' . $reference;
        }
        $this->relatedPerson['patient'] = array_filter([
            'reference' => $reference,
            'display' => $display,
        ], fn($v) => $v !== null);
        return $this;
    }

    public function addRelationship(string $code, string $display, string $system = 'http://terminology.hl7.org/CodeSystem/v3-RoleCode', ?string $text = null): self
    {
        $relationship = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];

        if ($text !== null) {
            $relationship['text'] = $text;
        }

        $this->relatedPerson['relationship'][] = $relationship;
        return $this;
    }

    public function addName(string $use, string $text, ?string $family = null, array $given = []): self
    {
        $name = [
            'use' => $use,
            'text' => $text,
        ];

        if ($family !== null) {
            $name['family'] = $family;
        }

        if (!empty($given)) {
            $name['given'] = $given;
        }

        $this->relatedPerson['name'][] = $name;
        return $this;
    }

    public function addTelecom(string $system, string $value, string $use = 'home'): self
    {
        $this->relatedPerson['telecom'][] = [
            'system' => $system,
            'value' => $value,
            'use' => $use,
        ];
        return $this;
    }

    public function setGender(string $gender): self
    {
        $this->relatedPerson['gender'] = $gender;
        return $this;
    }

    public function setBirthDate(string $birthDate): self
    {
        $this->relatedPerson['birthDate'] = $birthDate;
        return $this;
    }

    public function json(): array
    {
        return $this->relatedPerson;
    }
}

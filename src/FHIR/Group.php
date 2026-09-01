<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Group extends OAuth2Client
{
    public array $group = ['resourceType' => 'Group'];

    public function group(): array
    {
        return $this->group;
    }

    public function addIdentifier($system, $value, $use = null, $type = null, $period = null, $assigner = null): self
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        if ($use !== null) {
            $identifier['use'] = $use;
        }

        if ($type !== null) {
            $identifier['type'] = $type;
        }

        if ($period !== null) {
            $identifier['period'] = $period;
        }

        if ($assigner !== null) {
            $identifier['assigner'] = $assigner;
        }

        $this->group['identifier'][] = $identifier;
        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->group['active'] = $active;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->group['type'] = $type;
        return $this;
    }

    public function setActual(bool $actual): self
    {
        $this->group['actual'] = $actual;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->group['name'] = $name;
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->group['quantity'] = $quantity;
        return $this;
    }

    public function addMember(string $reference, ?string $display = null): self
    {
        if (strpos($reference, '/') === false) {
            $reference = 'Patient/' . $reference;
        }
        $member = [
            'entity' => [
                'reference' => $reference,
            ],
        ];
        if ($display !== null) {
            $member['entity']['display'] = $display;
        }
        $this->group['member'][] = $member;
        return $this;
    }

    public function json(): array
    {
        return $this->group;
    }
}

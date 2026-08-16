<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * Group FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/group.html
 */
class PayloadBuilderGroup extends Builder
{
    protected string $resourceType = 'Group';

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

    public function setType(string $type): self
    {
        $this->set('type', $type);
        return $this;
    }

    public function setActual(bool $actual): self
    {
        $this->set('actual', $actual);
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
        return $this;
    }

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->set('quantity', $quantity);
        return $this;
    }

    public function setManagingEntity(Reference $managingEntity): self
    {
        $this->set('managingEntity', $managingEntity->toArray());
        return $this;
    }

    public function addMember(Reference $entity, ?array $period = null, ?bool $inactive = null): self
    {
        $member = [
            'entity' => $entity->toArray(),
        ];

        if ($period !== null) {
            $member['period'] = $period;
        }

        if ($inactive !== null) {
            $member['inactive'] = $inactive;
        }

        $this->push('member', $member);
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

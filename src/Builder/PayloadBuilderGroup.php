<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class PayloadBuilderGroup extends Builder
{
    protected string $resourceType = 'Group';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setMetaProfile(string $profile): self
    {
        $this->data['meta/profile'] = [$profile];
        return $this;
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

    public function setCode(CodeableConcept|array $code): self
    {
        if ($code instanceof CodeableConcept) {
            $this->set('code', $code->toArray());
        } else {
            $this->set('code', $code);
        }
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

    public function setManagingEntity(Reference|array $managingEntity): self
    {
        if ($managingEntity instanceof Reference) {
            $this->set('managingEntity', $managingEntity->toArray());
        } else {
            $this->set('managingEntity', $managingEntity);
        }
        return $this;
    }

    public function addMember(Reference|string $reference, mixed $displayOrPeriod = null, mixed $periodOrInactive = null, ?bool $inactive = null): self
    {
        $member = [];
        if ($reference instanceof Reference) {
            $member['entity'] = $reference->toArray();
        } else {
            if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
                $reference = 'Patient/' . $reference;
            }
            $member['entity'] = [
                'reference' => $reference,
            ];
            if (is_string($displayOrPeriod)) {
                $member['entity']['display'] = $displayOrPeriod;
            }
        }

        // Handle variations of parameters in tests:
        // addMember($ref, $period) -> $displayOrPeriod is array
        // addMember($ref, null, $inactive) -> $periodOrInactive is bool
        // addMember($ref, $display, $period) -> $displayOrPeriod is string, $periodOrInactive is array
        // addMember($ref, null, $period, $inactive) -> $periodOrInactive is array, $inactive is bool

        $period = null;
        if (is_array($displayOrPeriod)) {
            $period = $displayOrPeriod;
        } elseif (is_array($periodOrInactive)) {
            $period = $periodOrInactive;
        }

        if (is_bool($displayOrPeriod)) {
            $inactive = $displayOrPeriod;
        } elseif (is_bool($periodOrInactive)) {
            $inactive = $periodOrInactive;
        }

        if ($period !== null) {
            $member['period'] = $period;
        }

        if ($inactive !== null) {
            $member['inactive'] = $inactive;
        }

        $this->push('member', $member);
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

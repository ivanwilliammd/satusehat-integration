<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Coverage FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/coverage.html
 */
class PayloadBuilderCoverage extends Builder
{
    protected string $resourceType = 'Coverage';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', ['system' => $system, 'value' => $value]);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->set('type', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setPolicyHolder(string $reference): self
    {
        $this->set('policyHolder', ['reference' => $reference]);
        return $this;
    }

    public function setSubscriber(string $reference): self
    {
        $this->set('subscriber', ['reference' => $reference]);
        return $this;
    }

    public function setSubscriberId(string $value): self
    {
        $this->set('subscriberId', $value);
        return $this;
    }

    public function setBeneficiary(string $reference): self
    {
        $this->set('beneficiary', ['reference' => $reference]);
        return $this;
    }

    public function setDependent(string $value): self
    {
        $this->set('dependent', $value);
        return $this;
    }

    public function setRelationship(string $system, string $code): self
    {
        $this->set('relationship', [
            'coding' => [['system' => $system, 'code' => $code]],
        ]);
        return $this;
    }

    public function addPayor(string $reference, string $display = ''): self
    {
        $payor = ['reference' => $reference];
        if ($display) {
            $payor['display'] = $display;
        }
        $this->push('payor', $payor);
        return $this;
    }

    public function setClass(string $typeSystem, string $typeCode, string $value, string $name = ''): self
    {
        $class = [
            'type' => ['coding' => [['system' => $typeSystem, 'code' => $typeCode]]],
            'value' => $value,
        ];
        if ($name) {
            $class['name'] = $name;
        }
        $this->push('class', $class);
        return $this;
    }

    public function setOrder(int $order): self
    {
        $this->set('order', $order);
        return $this;
    }

    public function setNetwork(string $network): self
    {
        $this->set('network', $network);
        return $this;
    }

    public function setCostToBeneficiary(float $value, string $code = 'IDR'): self
    {
        $this->push('costToBeneficiary', [
            'value' => $value,
            'currency' => $code,
        ]);
        return $this;
    }

    public function setPeriod(string $start, string $end = ''): self
    {
        $this->set('period/start', $start);
        if ($end) {
            $this->set('period/end', $end);
        }
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

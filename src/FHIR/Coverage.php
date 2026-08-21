<?php

namespace Satusehat\Integration\FHIR;

/**
 * Coverage FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/coverage.html
 */
class Coverage
{
    public array $data = ['resourceType' => 'Coverage'];

    public function setId(string $id): self
    {
        $this->data['id'] = $id;
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->data['identifier'][] = ['system' => $system, 'value' => $value];
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->data['type'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setPolicyHolder(string $reference): self
    {
        $this->data['policyHolder'] = ['reference' => $reference];
        return $this;
    }

    public function setSubscriber(string $reference): self
    {
        $this->data['subscriber'] = ['reference' => $reference];
        return $this;
    }

    public function setSubscriberId(string $value): self
    {
        $this->data['subscriberId'] = $value;
        return $this;
    }

    public function setBeneficiary(string $reference): self
    {
        $this->data['beneficiary'] = ['reference' => $reference];
        return $this;
    }

    public function setDependent(string $value): self
    {
        $this->data['dependent'] = $value;
        return $this;
    }

    public function setRelationship(string $system, string $code): self
    {
        $this->data['relationship'] = [
            'coding' => [['system' => $system, 'code' => $code]],
        ];
        return $this;
    }

    public function addPayor(string $reference, string $display = ''): self
    {
        $payor = ['reference' => $reference];
        if ($display) {
            $payor['display'] = $display;
        }
        $this->data['payor'][] = $payor;
        return $this;
    }

    public function setClass(string $system, string $value, string $name = ''): self
    {
        $class = ['type' => ['coding' => [['system' => $system]]], 'value' => $value];
        if ($name) {
            $class['name'] = $name;
        }
        $this->data['class'][] = $class;
        return $this;
    }

    public function setOrder(int $order): self
    {
        $this->data['order'] = $order;
        return $this;
    }

    public function setNetwork(string $network): self
    {
        $this->data['network'] = $network;
        return $this;
    }

    public function setCostToBeneficiary(float $value, string $system = 'urn:iso:std:iso:4217', string $code = 'IDR'): self
    {
        $this->data['costToBeneficiary'][] = [
            'value' => $value,
            'currency' => $code,
        ];
        return $this;
    }

    public function setExpirationDate(string $date): self
    {
        $this->data['period']['end'] = $date;
        return $this;
    }

    public function setStartDate(string $date): self
    {
        $this->data['period']['start'] = $date;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

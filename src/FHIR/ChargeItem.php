<?php

namespace Satusehat\Integration\FHIR;

/**
 * ChargeItem FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/chargeitem.html
 */
class ChargeItem
{
    public array $data = ['resourceType' => 'ChargeItem'];

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

    public function setPartOf(string $reference): self
    {
        $this->data['partOf'][] = ['reference' => $reference];
        return $this;
    }

    public function setCode(string $system, string $code, string $display = ''): self
    {
        $this->data['code'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setSubject(string $reference): self
    {
        $this->data['subject'] = ['reference' => $reference];
        return $this;
    }

    public function setFocus(string $reference): self
    {
        $this->data['focus'] = ['reference' => $reference];
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->data['context'] = ['reference' => $reference];
        return $this;
    }

    public function setPerformedDate(string $dateTime): self
    {
        $this->data['performedDateTime'] = $dateTime;
        return $this;
    }

    public function setPerformerActor(string $reference, string $functionSystem = '', string $functionCode = ''): self
    {
        $performer = ['actor' => ['reference' => $reference]];
        if ($functionSystem && $functionCode) {
            $performer['function'] = [
                'coding' => [['system' => $functionSystem, 'code' => $functionCode]],
            ];
        }
        $this->data['performer'][] = $performer;
        return $this;
    }

    public function setCostCenter(string $reference): self
    {
        $this->data['performer'][] = ['actor' => ['reference' => $reference]];
        return $this;
    }

    public function setOrganization(string $reference): self
    {
        $this->data['performingOrganization'] = ['reference' => $reference];
        return $this;
    }

    public function setRequestingOrganization(string $reference): self
    {
        $this->data['requestingOrganization'] = ['reference' => $reference];
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->data['quantity'] = $quantity;
        return $this;
    }

    public function setBodysite(string $system, string $code, string $display = ''): self
    {
        $this->data['bodysite'][] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setProductCode(string $system, string $code, string $display = ''): self
    {
        $this->data['productCodeableConcept'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setPriceOverride(float $value, string $code = 'IDR'): self
    {
        $this->data['priceOverride'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function setOverrideReason(string $overrideReason): self
    {
        $this->data['overrideReason'] = $overrideReason;
        return $this;
    }

    public function setEnterer(string $reference): self
    {
        $this->data['enterer'] = ['reference' => $reference];
        return $this;
    }

    public function setEnteredDate(string $dateTime): self
    {
        $this->data['enteredDate'] = $dateTime;
        return $this;
    }

    public function setReason(string $system, string $code, string $display = ''): self
    {
        $this->data['reason'][] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setService(string $reference): self
    {
        $this->data['service'][] = ['reference' => $reference];
        return $this;
    }

    public function setAccount(string $reference): self
    {
        $this->data['account'][] = ['reference' => $reference];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

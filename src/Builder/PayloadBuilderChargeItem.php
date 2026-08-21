<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ChargeItem FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/chargeitem.html
 */
class PayloadBuilderChargeItem extends Builder
{
    protected string $resourceType = 'ChargeItem';

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

    public function setPartOf(string $reference): self
    {
        $this->push('partOf', ['reference' => $reference]);
        return $this;
    }

    public function setCode(string $system, string $code, string $display = ''): self
    {
        $this->set('code', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setSubject(string $reference): self
    {
        $this->set('subject', ['reference' => $reference]);
        return $this;
    }

    public function setFocus(string $reference): self
    {
        $this->set('focus', ['reference' => $reference]);
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->set('context', ['reference' => $reference]);
        return $this;
    }

    public function setPerformedDate(string $dateTime): self
    {
        $this->set('performedDateTime', $dateTime);
        return $this;
    }

    public function setPerformerActor(string $reference, ?string $functionSystem = null, ?string $functionCode = null): self
    {
        $performer = ['actor' => ['reference' => $reference]];
        if ($functionSystem && $functionCode) {
            $performer['function'] = [
                'coding' => [['system' => $functionSystem, 'code' => $functionCode]],
            ];
        }
        $this->push('performer', $performer);
        return $this;
    }

    public function setPerformerCostCenter(string $reference): self
    {
        $this->push('performer', ['actor' => ['reference' => $reference]]);
        return $this;
    }

    public function setOrganization(string $reference): self
    {
        $this->set('performingOrganization', ['reference' => $reference]);
        return $this;
    }

    public function setRequestingOrganization(string $reference): self
    {
        $this->set('requestingOrganization', ['reference' => $reference]);
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->set('quantity', $quantity);
        return $this;
    }

    public function setBodysite(string $system, string $code, string $display = ''): self
    {
        $this->push('bodysite', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setProductCode(string $system, string $code, string $display = ''): self
    {
        $this->set('productCodeableConcept', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setPriceOverride(float $value, string $code = 'IDR'): self
    {
        $this->set('priceOverride', ['value' => $value, 'currency' => $code]);
        return $this;
    }

    public function setOverrideReason(string $overrideReason): self
    {
        $this->set('overrideReason', $overrideReason);
        return $this;
    }

    public function setEnterer(string $reference): self
    {
        $this->set('enterer', ['reference' => $reference]);
        return $this;
    }

    public function setEnteredDate(string $dateTime): self
    {
        $this->set('enteredDate', $dateTime);
        return $this;
    }

    public function setReason(string $system, string $code, string $display = ''): self
    {
        $this->push('reason', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setService(string $reference): self
    {
        $this->push('service', ['reference' => $reference]);
        return $this;
    }

    public function setAccount(string $reference): self
    {
        $this->push('account', ['reference' => $reference]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

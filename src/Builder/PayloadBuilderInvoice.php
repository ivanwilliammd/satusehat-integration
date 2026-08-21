<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Invoice FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/invoice.html
 */
class PayloadBuilderInvoice extends Builder
{
    protected string $resourceType = 'Invoice';

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

    public function setCancelledReason(string $reason): self
    {
        $this->set('cancelledReason', $reason);
        return $this;
    }

    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->set('type', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setDate(string $dateTime): self
    {
        $this->set('date', $dateTime);
        return $this;
    }

    public function setParticipant(string $roleSystem, string $roleCode, string $reference, string $display = ''): self
    {
        $participant = [
            'role' => ['coding' => [['system' => $roleSystem, 'code' => $roleCode]]],
            'actor' => ['reference' => $reference],
        ];
        if ($display) {
            $participant['actor']['display'] = $display;
        }
        $this->push('participant', $participant);
        return $this;
    }

    public function setIssuer(string $reference): self
    {
        $this->set('issuer', ['reference' => $reference]);
        return $this;
    }

    public function setCustomer(string $reference): self
    {
        $this->set('subject', ['reference' => $reference]);
        return $this;
    }

    public function setAccount(string $reference): self
    {
        $this->set('account', ['reference' => $reference]);
        return $this;
    }

    public function addLineItem(
        int $sequence,
        string $serviceSystem,
        string $serviceCode,
        string $serviceDisplay,
        ?float $quantity = null,
        ?float $unitPrice = null,
        ?float $priceFactor = null
    ): self {
        $lineItem = [
            'sequence' => $sequence,
            'service' => [
                'coding' => [['system' => $serviceSystem, 'code' => $serviceCode, 'display' => $serviceDisplay]],
            ],
        ];
        if ($quantity !== null) {
            $lineItem['quantity'] = ['value' => $quantity];
        }
        if ($unitPrice !== null) {
            $lineItem['priceComponent'][] = [
                'type' => 'base',
                'amount' => ['value' => $unitPrice],
            ];
        }
        if ($priceFactor !== null) {
            $lineItem['priceComponent'][] = [
                'type' => 'factor',
                'factor' => $priceFactor,
            ];
        }
        $this->push('lineItem', $lineItem);
        return $this;
    }

    public function addLineItemPriceComponent(int $itemIdx, string $type, float $amount, ?string $code = null): self
    {
        $component = ['type' => $type, 'amount' => ['value' => $amount]];
        if ($code) {
            $component['code'] = $code;
        }
        $this->push("lineItem/{$itemIdx}/priceComponent", $component);
        return $this;
    }

    public function setTotalNet(float $value, string $code = 'IDR'): self
    {
        $this->set('totalNet/value', $value);
        $this->set('totalNet/currency', $code);
        return $this;
    }

    public function setTotalGross(float $value, string $code = 'IDR'): self
    {
        $this->set('totalGross/value', $value);
        $this->set('totalGross/currency', $code);
        return $this;
    }

    public function setTotalVAT(float $value, string $code = 'IDR'): self
    {
        $this->set('totalVAT/value', $value);
        $this->set('totalVAT/currency', $code);
        return $this;
    }

    public function setPaymentTerms(string $text): self
    {
        $this->set('paymentTerms', $text);
        return $this;
    }

    public function addNote(string $text): self
    {
        $this->push('note', ['text' => $text]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

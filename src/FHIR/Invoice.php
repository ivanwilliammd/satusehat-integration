<?php

namespace Satusehat\Integration\FHIR;

/**
 * Invoice FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/invoice.html
 */
class Invoice
{
    public array $data = ['resourceType' => 'Invoice'];

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

    public function setCancelledReason(string $reason): self
    {
        $this->data['cancelledReason'] = $reason;
        return $this;
    }

    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->data['type'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setDate(string $dateTime): self
    {
        $this->data['date'] = $dateTime;
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
        $this->data['participant'][] = $participant;
        return $this;
    }

    public function setParticipantActor(string $reference, string $display = ''): self
    {
        $participant = ['actor' => ['reference' => $reference]];
        if ($display) {
            $participant['actor']['display'] = $display;
        }
        $this->data['participant'][] = $participant;
        return $this;
    }

    public function setIssuer(string $reference): self
    {
        $this->data['issuer'] = ['reference' => $reference];
        return $this;
    }

    public function setCustomer(string $reference): self
    {
        $this->data['subject'] = ['reference' => $reference];
        return $this;
    }

    public function setAccount(string $reference): self
    {
        $this->data['account'] = ['reference' => $reference];
        return $this;
    }

    public function setLineItem(
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
        $this->data['lineItem'][] = $lineItem;
        return $this;
    }

    public function addLineItemPriceComponent(int $itemIdx, string $type, float $amount, ?string $code = null): self
    {
        $component = ['type' => $type, 'amount' => ['value' => $amount]];
        if ($code) {
            $component['code'] = $code;
        }
        $itemCount = count($this->data['lineItem']) - 1;
        if ($itemCount >= 0) {
            $this->data['lineItem'][$itemCount]['priceComponent'][] = $component;
        }
        return $this;
    }

    public function setTotalPriceComponent(int $itemIdx, string $type, float $amount): self
    {
        $itemCount = count($this->data['lineItem']) - 1;
        if ($itemCount >= 0) {
            $this->data['lineItem'][$itemCount]['priceComponent'][] = [
                'type' => $type,
                'amount' => ['value' => $amount],
            ];
        }
        return $this;
    }

    public function setTotalNet(float $value, string $code = 'IDR'): self
    {
        $this->data['totalNet'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function setTotalGross(float $value, string $code = 'IDR'): self
    {
        $this->data['totalGross'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function setTotalVAT(float $value, string $code = 'IDR'): self
    {
        $this->data['totalVAT'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function setPaymentTerms(string $text): self
    {
        $this->data['paymentTerms'] = $text;
        return $this;
    }

    public function addPaymentTermsNote(string $text): self
    {
        $this->data['note'][] = ['text' => $text];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

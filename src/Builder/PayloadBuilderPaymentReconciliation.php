<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * PaymentReconciliation FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/paymentreconciliation.html
 */
class PayloadBuilderPaymentReconciliation extends Builder
{
    protected string $resourceType = 'PaymentReconciliation';

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

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
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

    public function setRequest(string $reference): self
    {
        $this->set('request', ['reference' => $reference]);
        return $this;
    }

    public function setRequestProvider(string $reference): self
    {
        $this->set('requestProvider', ['reference' => $reference]);
        return $this;
    }

    public function setOutcome(string $outcomeSystem, string $outcomeCode, string $outcomeDisplay = ''): self
    {
        $this->set('outcome', [
            'coding' => [['system' => $outcomeSystem, 'code' => $outcomeCode, 'display' => $outcomeDisplay]],
        ]);
        return $this;
    }

    public function setDisposition(string $disposition): self
    {
        $this->set('disposition', $disposition);
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->set('insurer', ['reference' => $reference]);
        return $this;
    }

    public function setRequestMatchDate(string $date): self
    {
        $this->set('requestMatchDate', $date);
        return $this;
    }

    public function setOutcomeCode(string $system, string $code, string $display = ''): self
    {
        $this->push('outcomeCode', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function addRequestor(string $reference, string $display = ''): self
    {
        $requestor = ['reference' => $reference];
        if ($display) {
            $requestor['display'] = $display;
        }
        $this->push('requestor', $requestor);
        return $this;
    }

    public function addProcessNote(string $text, ?string $type = null): self
    {
        $note = ['text' => $text];
        if ($type) {
            $note['type'] = $type;
        }
        $this->push('processNote', $note);
        return $this;
    }

    public function setPaymentDate(string $date): self
    {
        $this->set('paymentDate', $date);
        return $this;
    }

    public function setPaymentAmount(float $value, string $code = 'IDR'): self
    {
        $this->set('paymentAmount/value', $value);
        $this->set('paymentAmount/currency', $code);
        return $this;
    }

    public function setPaymentIdentifier(string $system, string $value): self
    {
        $this->set('paymentIdentifier', ['system' => $system, 'value' => $value]);
        return $this;
    }

    public function addDetail(
        string $typeSystem,
        string $typeCode,
        ?float $amount = null,
        ?string $requestReference = null
    ): self {
        $detail = [
            'type' => ['coding' => [['system' => $typeSystem, 'code' => $typeCode]]],
        ];
        if ($amount !== null) {
            $detail['amount'] = ['value' => $amount];
        }
        if ($requestReference) {
            $detail['request'] = ['reference' => $requestReference];
        }
        $this->push('detail', $detail);
        return $this;
    }

    public function setTotalAmount(float $value, string $code = 'IDR'): self
    {
        $this->set('totalAmount/value', $value);
        $this->set('totalAmount/currency', $code);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

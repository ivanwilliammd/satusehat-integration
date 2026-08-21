<?php

namespace Satusehat\Integration\FHIR;

/**
 * PaymentReconciliation FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/paymentreconciliation.html
 */
class PaymentReconciliation
{
    public array $data = ['resourceType' => 'PaymentReconciliation'];

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

    public function setCreated(string $dateTime): self
    {
        $this->data['created'] = $dateTime;
        return $this;
    }

    public function setPeriod(string $start, string $end = ''): self
    {
        $this->data['period'] = ['start' => $start];
        if ($end) {
            $this->data['period']['end'] = $end;
        }
        return $this;
    }

    public function setRequest(string $reference): self
    {
        $this->data['request'] = ['reference' => $reference];
        return $this;
    }

    public function setRequestProvider(string $reference): self
    {
        $this->data['requestProvider'] = ['reference' => $reference];
        return $this;
    }

    public function setOutcome(string $outcomeSystem, string $outcomeCode, string $outcomeDisplay = ''): self
    {
        $this->data['outcome'] = [
            'coding' => [['system' => $outcomeSystem, 'code' => $outcomeCode, 'display' => $outcomeDisplay]],
        ];
        return $this;
    }

    public function setDisposition(string $disposition): self
    {
        $this->data['disposition'] = $disposition;
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->data['insurer'] = ['reference' => $reference];
        return $this;
    }

    public function setRequestMatchDate(string $date): self
    {
        $this->data['requestMatchDate'] = $date;
        return $this;
    }

    public function setOutcomeCode(string $system, string $code, string $display = ''): self
    {
        $this->data['outcomeCode'][] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function addRequestor(string $reference, string $display = ''): self
    {
        $requestor = ['reference' => $reference];
        if ($display) {
            $requestor['display'] = $display;
        }
        $this->data['requestor'][] = $requestor;
        return $this;
    }

    public function addProcessNote(string $text, ?string $type = null): self
    {
        $note = ['text' => $text];
        if ($type) {
            $note['type'] = $type;
        }
        $this->data['processNote'][] = $note;
        return $this;
    }

    public function setPaymentDate(string $date): self
    {
        $this->data['paymentDate'] = $date;
        return $this;
    }

    public function setPaymentAmount(float $value, string $code = 'IDR'): self
    {
        $this->data['paymentAmount'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function setPaymentIdentifier(string $system, string $value): self
    {
        $this->data['paymentIdentifier'] = ['system' => $system, 'value' => $value];
        return $this;
    }

    public function addDetail(
        string $typeSystem,
        string $typeCode,
        ?float $amount = null,
        ?string $reference = null
    ): self {
        $detail = [
            'type' => ['coding' => [['system' => $typeSystem, 'code' => $typeCode]]],
        ];
        if ($amount !== null) {
            $detail['amount'] = ['value' => $amount];
        }
        if ($reference) {
            $detail['request'] = ['reference' => $reference];
        }
        $this->data['detail'][] = $detail;
        return $this;
    }

    public function setTotalAmount(float $value, string $code = 'IDR'): self
    {
        $this->data['totalAmount'] = ['value' => $value, 'currency' => $code];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

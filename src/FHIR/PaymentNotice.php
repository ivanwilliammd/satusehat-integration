<?php

namespace Satusehat\Integration\FHIR;

/**
 * PaymentNotice FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/paymentnotice.html
 */
class PaymentNotice
{
    public array $data = ['resourceType' => 'PaymentNotice'];

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

    public function setRequest(string $reference): self
    {
        $this->data['request'] = ['reference' => $reference];
        return $this;
    }

    public function setResponse(string $reference): self
    {
        $this->data['response'] = ['reference' => $reference];
        return $this;
    }

    public function setStatusDate(string $date): self
    {
        $this->data['statusDate'] = $date;
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->data['created'] = $dateTime;
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->data['provider'] = ['reference' => $reference];
        return $this;
    }

    public function setPayee(string $reference): self
    {
        $this->data['payee'] = ['reference' => $reference];
        return $this;
    }

    public function setPayment(float $value, string $code = 'IDR'): self
    {
        $this->data['payment'] = ['amount' => ['value' => $value, 'currency' => $code]];
        return $this;
    }

    public function setPaymentDate(string $date): self
    {
        $this->data['paymentDate'] = $date;
        return $this;
    }

    public function setPaymentStatus(string $system, string $code, string $display = ''): self
    {
        $this->data['paymentStatus'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->data['insurer'] = ['reference' => $reference];
        return $this;
    }

    public function setPaymentReconciliation(string $reference): self
    {
        $this->data['paymentReconciliation'] = ['reference' => $reference];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

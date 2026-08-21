<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * PaymentNotice FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/paymentnotice.html
 */
class PayloadBuilderPaymentNotice extends Builder
{
    protected string $resourceType = 'PaymentNotice';

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

    public function setRequest(string $reference): self
    {
        $this->set('request', ['reference' => $reference]);
        return $this;
    }

    public function setResponse(string $reference): self
    {
        $this->set('response', ['reference' => $reference]);
        return $this;
    }

    public function setStatusDate(string $date): self
    {
        $this->set('statusDate', $date);
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->set('provider', ['reference' => $reference]);
        return $this;
    }

    public function setPayee(string $reference): self
    {
        $this->set('payee', ['reference' => $reference]);
        return $this;
    }

    public function setPayment(float $value, string $code = 'IDR'): self
    {
        $this->set('payment/amount/value', $value);
        $this->set('payment/amount/currency', $code);
        return $this;
    }

    public function setPaymentDate(string $date): self
    {
        $this->set('paymentDate', $date);
        return $this;
    }

    public function setPaymentStatus(string $system, string $code, string $display = ''): self
    {
        $this->set('paymentStatus', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->set('insurer', ['reference' => $reference]);
        return $this;
    }

    public function setPaymentReconciliation(string $reference): self
    {
        $this->set('paymentReconciliation', ['reference' => $reference]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

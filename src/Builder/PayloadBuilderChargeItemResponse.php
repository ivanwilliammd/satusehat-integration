<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ChargeItemResponse FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/chargeitemresponse.html
 */
class PayloadBuilderChargeItemResponse extends Builder
{
    protected string $resourceType = 'ChargeItemResponse';

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

    public function setChargeItem(string $reference): self
    {
        $this->set('chargeItem', ['reference' => $reference]);
        return $this;
    }

    public function setPartOf(string $reference): self
    {
        $this->push('partOf', ['reference' => $reference]);
        return $this;
    }

    public function setRequest(string $reference): self
    {
        $this->set('request', ['reference' => $reference]);
        return $this;
    }

    public function setOutcome(string $outcomeSystem, string $outcomeCode, string $outcomeDisplay = ''): self
    {
        $this->set('outcome', [
            'coding' => [['system' => $outcomeSystem, 'code' => $outcomeCode, 'display' => $outcomeDisplay]],
        ]);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
        return $this;
    }

    public function setRequestor(string $reference): self
    {
        $this->set('requestor', ['reference' => $reference]);
        return $this;
    }

    public function addOutcomeInfo(string $system, string $code, string $display = ''): self
    {
        $this->push('outcomeInfo', [
            'code' => [
                'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
            ],
        ]);
        return $this;
    }

    public function addError(string $system, string $code, string $display = ''): self
    {
        $this->push('error', [
            'code' => [
                'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
            ],
        ]);
        return $this;
    }

    public function addCostCenterChargeItemResponse(string $reference, string $display = ''): self
    {
        $response = ['costCenter' => ['reference' => $reference]];
        if ($display) {
            $response['costCenter']['display'] = $display;
        }
        $this->push('costCenterChargeItemResponse', $response);
        return $this;
    }

    public function setResponseDate(string $date): self
    {
        $this->set('responseDate', $date);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

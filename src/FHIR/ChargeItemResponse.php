<?php

namespace Satusehat\Integration\FHIR;

/**
 * ChargeItemResponse FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/chargeitemresponse.html
 */
class ChargeItemResponse
{
    public array $data = ['resourceType' => 'ChargeItemResponse'];

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

    public function setChargeItem(string $reference): self
    {
        $this->data['chargeItem'] = ['reference' => $reference];
        return $this;
    }

    public function setPartOf(string $reference): self
    {
        $this->data['partOf'][] = ['reference' => $reference];
        return $this;
    }

    public function setRequest(string $reference): self
    {
        $this->data['request'] = ['reference' => $reference];
        return $this;
    }

    public function setOutcome(string $outcomeSystem, string $outcomeCode, string $outcomeDisplay = ''): self
    {
        $this->data['outcome'] = [
            'coding' => [['system' => $outcomeSystem, 'code' => $outcomeCode, 'display' => $outcomeDisplay]],
        ];
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->data['description'] = $description;
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->data['created'] = $dateTime;
        return $this;
    }

    public function setRequestor(string $reference): self
    {
        $this->data['requestor'] = ['reference' => $reference];
        return $this;
    }

    public function addOutcomeInfo(string $system, string $code, string $display = ''): self
    {
        $this->data['outcomeInfo'][] = [
            'code' => [
                'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
            ],
        ];
        return $this;
    }

    public function addError(string $system, string $code, string $display = ''): self
    {
        $this->data['error'][] = [
            'code' => [
                'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
            ],
        ];
        return $this;
    }

    public function addCostCenterChargeItemResponse(string $reference, string $display = ''): self
    {
        $response = ['costCenter' => ['reference' => $reference]];
        if ($display) {
            $response['costCenter']['display'] = $display;
        }
        $this->data['costCenterChargeItemResponse'][] = $response;
        return $this;
    }

    public function setResponseDate(string $date): self
    {
        $this->data['responseDate'] = $date;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

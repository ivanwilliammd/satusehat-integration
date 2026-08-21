<?php

namespace Satusehat\Integration\FHIR;

/**
 * CoverageEligibilityRequest FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/coverageeligibilityrequest.html
 */
class CoverageEligibilityRequest
{
    public array $data = ['resourceType' => 'CoverageEligibilityRequest'];

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

    public function setPriority(string $system, string $code, string $display = ''): self
    {
        $this->data['priority'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setPurpose(array $purpose): self
    {
        $this->data['purpose'] = $purpose;
        return $this;
    }

    public function setPatient(string $reference, string $display = ''): self
    {
        $this->data['patient'] = ['reference' => $reference];
        if ($display) {
            $this->data['patient']['display'] = $display;
        }
        return $this;
    }

    public function setServicedDate(string $date): self
    {
        $this->data['servicedDate'] = $date;
        return $this;
    }

    public function setServicedPeriod(string $start, string $end): self
    {
        $this->data['servicedPeriod'] = ['start' => $start, 'end' => $end];
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->data['created'] = $dateTime;
        return $this;
    }

    public function setEnterer(string $reference): self
    {
        $this->data['enterer'] = ['reference' => $reference];
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->data['provider'] = ['reference' => $reference];
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->data['insurer'] = ['reference' => $reference];
        return $this;
    }

    public function addCoverage(string $reference, string $preAuthRef = ''): self
    {
        $coverage = ['reference' => $reference];
        if ($preAuthRef) {
            $coverage['preAuthRef'] = [$preAuthRef];
        }
        $this->data['coverage'][] = $coverage;
        return $this;
    }

    public function addItem(
        string $productOrServiceSystem,
        string $productOrServiceCode,
        string $productOrServiceDisplay,
        ?string $categorySystem = null,
        ?string $categoryCode = null
    ): self {
        $item = [
            'productOrService' => [
                'coding' => [
                    ['system' => $productOrServiceSystem, 'code' => $productOrServiceCode, 'display' => $productOrServiceDisplay],
                ],
            ],
        ];
        if ($categorySystem && $categoryCode) {
            $item['category'] = [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
            ];
        }
        $this->data['item'][] = $item;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

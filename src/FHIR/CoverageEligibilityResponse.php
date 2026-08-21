<?php

namespace Satusehat\Integration\FHIR;

/**
 * CoverageEligibilityResponse FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/coverageeligibilityresponse.html
 */
class CoverageEligibilityResponse
{
    public array $data = ['resourceType' => 'CoverageEligibilityResponse'];

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

    public function setOutcome(string $outcome): self
    {
        $this->data['outcome'] = $outcome;
        return $this;
    }

    public function setDisposition(string $disposition): self
    {
        $this->data['disposition'] = $disposition;
        return $this;
    }

    public function addInsurance(
        string $coverageReference,
        ?string $benefitBalanceName = null,
        ?string $benefitBalanceTypeSystem = null,
        ?string $benefitBalanceTypeCode = null,
        ?string $benefitBalanceTypeDisplay = null
    ): self {
        $insurance = ['coverage' => ['reference' => $coverageReference]];
        if ($benefitBalanceName) {
            $item = ['name' => $benefitBalanceName];
            if ($benefitBalanceTypeSystem && $benefitBalanceTypeCode) {
                $item['type'] = [
                    'coding' => [['system' => $benefitBalanceTypeSystem, 'code' => $benefitBalanceTypeCode, 'display' => $benefitBalanceTypeDisplay ?? '']],
                ];
            }
            $insurance['benefitBalance'][] = $item;
        }
        $this->data['insurance'][] = $insurance;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

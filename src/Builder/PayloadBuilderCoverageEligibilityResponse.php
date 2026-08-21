<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * CoverageEligibilityResponse FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/coverageeligibilityresponse.html
 */
class PayloadBuilderCoverageEligibilityResponse extends Builder
{
    protected string $resourceType = 'CoverageEligibilityResponse';

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

    public function setPurpose(array $purpose): self
    {
        $this->set('purpose', $purpose);
        return $this;
    }

    public function setPatient(string $reference, string $display = ''): self
    {
        $patient = ['reference' => $reference];
        if ($display) {
            $patient['display'] = $display;
        }
        $this->set('patient', $patient);
        return $this;
    }

    public function setServicedDate(string $date): self
    {
        $this->set('servicedDate', $date);
        return $this;
    }

    public function setServicedPeriod(string $start, string $end = ''): self
    {
        $this->set('servicedPeriod/start', $start);
        if ($end) {
            $this->set('servicedPeriod/end', $end);
        }
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
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

    public function setInsurer(string $reference): self
    {
        $this->set('insurer', ['reference' => $reference]);
        return $this;
    }

    public function addCoverage(string $reference, string $preAuthRef = ''): self
    {
        $coverage = ['reference' => $reference];
        if ($preAuthRef) {
            $coverage['preAuthRef'] = [$preAuthRef];
        }
        $this->push('coverage', $coverage);
        return $this;
    }

    public function setOutcome(string $outcome): self
    {
        $this->set('outcome', $outcome);
        return $this;
    }

    public function setDisposition(string $disposition): self
    {
        $this->set('disposition', $disposition);
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
        $this->push('insurance', $insurance);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

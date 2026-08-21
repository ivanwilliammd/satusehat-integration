<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * CoverageEligibilityRequest FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/coverageeligibilityrequest.html
 */
class PayloadBuilderCoverageEligibilityRequest extends Builder
{
    protected string $resourceType = 'CoverageEligibilityRequest';

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

    public function setPriority(string $system, string $code, string $display = ''): self
    {
        $this->set('priority', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
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

    public function setEnterer(string $reference): self
    {
        $this->set('enterer', ['reference' => $reference]);
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->set('provider', ['reference' => $reference]);
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
        $this->push('item', $item);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

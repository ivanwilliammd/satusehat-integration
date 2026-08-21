<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Claim FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/claim.html
 */
class PayloadBuilderClaim extends Builder
{
    protected string $resourceType = 'Claim';

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

    public function setSubType(string $system, string $code, string $display = ''): self
    {
        $this->set('subType', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setUse(string $use): self
    {
        $this->set('use', $use);
        return $this;
    }

    public function setPriority(string $system, string $code, string $display = ''): self
    {
        $this->set('priority', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
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

    public function setBillablePeriod(string $start, string $end = ''): self
    {
        $this->set('billablePeriod/start', $start);
        if ($end) {
            $this->set('billablePeriod/end', $end);
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

    public function setInsurer(string $reference): self
    {
        $this->set('insurer', ['reference' => $reference]);
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->set('provider', ['reference' => $reference]);
        return $this;
    }

    public function setPriorityPayor(string $reference): self
    {
        $this->push('priorityPayor', ['reference' => $reference]);
        return $this;
    }

    public function setPrescriber(string $reference): self
    {
        $this->set('prescriber', ['reference' => $reference]);
        return $this;
    }

    public function setFacility(string $reference, string $display = ''): self
    {
        $facility = ['reference' => $reference];
        if ($display) {
            $facility['display'] = $display;
        }
        $this->set('facility', $facility);
        return $this;
    }

    public function addRelated(
        ?string $referenceSystem = null,
        ?string $referenceCode = null,
        ?string $referenceDisplay = null,
        ?string $relationshipSystem = null,
        ?string $relationshipCode = null
    ): self {
        $related = [];
        if ($referenceSystem && $referenceCode) {
            $related['reference'] = [
                'system' => $referenceSystem,
                'code' => $referenceCode,
                'display' => $referenceDisplay ?? '',
            ];
        }
        if ($relationshipSystem && $relationshipCode) {
            $related['relationship'] = [
                'coding' => [['system' => $relationshipSystem, 'code' => $relationshipCode]],
            ];
        }
        $this->push('related', $related);
        return $this;
    }

    public function addCoverage(
        string $reference,
        ?bool $focal = null,
        ?string $prioritySystem = null,
        ?string $priorityCode = null
    ): self {
        $coverage = ['reference' => $reference];
        if ($focal !== null) {
            $coverage['focal'] = (bool) $focal;
        }
        if ($prioritySystem && $priorityCode) {
            $coverage['priority'] = [
                'coding' => [['system' => $prioritySystem, 'code' => $priorityCode]],
            ];
        }
        $this->push('coverage', $coverage);
        return $this;
    }

    public function addItem(
        int $sequence,
        string $productOrServiceSystem,
        string $productOrServiceCode,
        string $productOrServiceDisplay,
        ?int $careTeamSequence = null,
        ?int $diagnosisSequence = null,
        ?int $procedureSequence = null,
        ?int $informationSequence = null
    ): self {
        $item = [
            'sequence' => $sequence,
            'productOrService' => [
                'coding' => [['system' => $productOrServiceSystem, 'code' => $productOrServiceCode, 'display' => $productOrServiceDisplay]],
            ],
        ];
        if ($careTeamSequence !== null) {
            $item['careTeamSequence'][] = $careTeamSequence;
        }
        if ($diagnosisSequence !== null) {
            $item['diagnosisSequence'][] = $diagnosisSequence;
        }
        if ($procedureSequence !== null) {
            $item['procedureSequence'][] = $procedureSequence;
        }
        if ($informationSequence !== null) {
            $item['informationSequence'][] = $informationSequence;
        }
        $this->push('item', $item);
        return $this;
    }

    public function addItemServicedPeriod(int $itemIdx, string $start, string $end = ''): self
    {
        $this->set("item/{$itemIdx}/servicedPeriod/start", $start);
        if ($end) {
            $this->set("item/{$itemIdx}/servicedPeriod/end", $end);
        }
        return $this;
    }

    public function addItemLocation(int $itemIdx, string $system, string $code, string $display = ''): self
    {
        $this->push("item/{$itemIdx}/location", [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function addItemQuantity(int $itemIdx, float $value, string $code = 'IDR'): self
    {
        $this->set("item/{$itemIdx}/quantity/value", $value);
        $this->set("item/{$itemIdx}/quantity/currency", $code);
        return $this;
    }

    public function addItemUnitPrice(int $itemIdx, float $value, string $code = 'IDR'): self
    {
        $this->set("item/{$itemIdx}/unitPrice/value", $value);
        $this->set("item/{$itemIdx}/unitPrice/currency", $code);
        return $this;
    }

    public function addCareTeam(
        int $sequence,
        string $providerReference,
        ?string $roleSystem = null,
        ?string $roleCode = null,
        ?string $roleDisplay = null
    ): self {
        $careTeam = ['sequence' => $sequence, 'provider' => ['reference' => $providerReference]];
        if ($roleSystem && $roleCode) {
            $careTeam['role'] = [
                'coding' => [['system' => $roleSystem, 'code' => $roleCode, 'display' => $roleDisplay ?? '']],
            ];
        }
        $this->push('careTeam', $careTeam);
        return $this;
    }

    public function addDiagnosis(
        int $sequence,
        string $system,
        string $code,
        ?string $typeSystem = null,
        ?string $typeCode = null
    ): self {
        $diagnosis = [
            'sequence' => $sequence,
            'diagnosisCodeableConcept' => [
                'coding' => [['system' => $system, 'code' => $code]],
            ],
        ];
        if ($typeSystem && $typeCode) {
            $diagnosis['type'] = [
                'coding' => [['system' => $typeSystem, 'code' => $typeCode]],
            ];
        }
        $this->push('diagnosis', $diagnosis);
        return $this;
    }

    public function addProcedure(
        int $sequence,
        string $system,
        string $code,
        ?string $date = null,
        ?string $typeSystem = null,
        ?string $typeCode = null
    ): self {
        $procedure = [
            'sequence' => $sequence,
            'procedureCodeableConcept' => [
                'coding' => [['system' => $system, 'code' => $code]],
            ],
        ];
        if ($date) {
            $procedure['date'] = $date;
        }
        if ($typeSystem && $typeCode) {
            $procedure['type'] = [
                'coding' => [['system' => $typeSystem, 'code' => $typeCode]],
            ];
        }
        $this->push('procedure', $procedure);
        return $this;
    }

    public function addInsuranceAccount(string $reference): self
    {
        $this->push('insurance/account', ['reference' => $reference]);
        return $this;
    }

    public function setTotal(float $value, string $code = 'IDR'): self
    {
        $this->set('total', ['value' => $value, 'currency' => $code]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

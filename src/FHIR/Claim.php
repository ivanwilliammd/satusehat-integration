<?php

namespace Satusehat\Integration\FHIR;

/**
 * Claim FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/claim.html
 */
class Claim
{
    public array $data = ['resourceType' => 'Claim'];

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

    public function setSubType(string $system, string $code, string $display = ''): self
    {
        $this->data['subType'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setUse(string $use): self
    {
        $this->data['use'] = $use;
        return $this;
    }

    public function setPriority(string $system, string $code, string $display = ''): self
    {
        $this->data['priority'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
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

    public function setBillablePeriod(string $start, string $end = ''): self
    {
        $this->data['billablePeriod'] = ['start' => $start];
        if ($end) {
            $this->data['billablePeriod']['end'] = $end;
        }
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

    public function setInsurer(string $reference): self
    {
        $this->data['insurer'] = ['reference' => $reference];
        return $this;
    }

    public function setProvider(string $reference): self
    {
        $this->data['provider'] = ['reference' => $reference];
        return $this;
    }

    public function setPriorityPayor(string $reference): self
    {
        $this->data['priorityPayor'][] = ['reference' => $reference];
        return $this;
    }

    public function setPrescriber(string $reference): self
    {
        $this->data['prescriber'] = ['reference' => $reference];
        return $this;
    }

    public function setOrigin(string $reference): self
    {
        $this->data['origin'] = ['reference' => $reference];
        return $this;
    }

    public function setDestination(string $reference): self
    {
        $this->data['destination'] = ['reference' => $reference];
        return $this;
    }

    public function setFacility(string $reference, string $display = ''): self
    {
        $this->data['facility'] = ['reference' => $reference];
        if ($display) {
            $this->data['facility']['display'] = $display;
        }
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
        $this->data['related'][] = $related;
        return $this;
    }

    public function addCoverage(
        string $reference,
        ?string $focal = null,
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
        $this->data['coverage'][] = $coverage;
        return $this;
    }

    public function addItem(
        int $sequence,
        ?int $careTeamSequence = null,
        ?int $diagnosisSequence = null,
        ?int $procedureSequence = null,
        ?int $informationSequence = null
    ): self {
        $item = ['sequence' => $sequence];
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
        $this->data['item'][] = $item;
        return $this;
    }

    public function addItemCareTeam(int $sequence, string $roleSystem, string $roleCode): self
    {
        $itemIdx = count($this->data['item']) - 1;
        $this->data['item'][$itemIdx]['careTeamLinkId'][] = $sequence;
        $this->data['careTeam'][] = [
            'sequence' => $sequence,
            'provider' => ['reference' => ''],
            'role' => [
                'coding' => [['system' => $roleSystem, 'code' => $roleCode]],
            ],
        ];
        return $this;
    }

    public function addItemDiagnosis(int $sequence, string $system, string $code): self
    {
        $itemIdx = count($this->data['item']) - 1;
        $this->data['item'][$itemIdx]['diagnosisLinkId'][] = $sequence;
        $this->data['diagnosis'][] = [
            'sequence' => $sequence,
            'diagnosisCodeableConcept' => [
                'coding' => [['system' => $system, 'code' => $code]],
            ],
        ];
        return $this;
    }

    public function addItemProcedure(int $sequence, string $system, string $code, string $date = ''): self
    {
        $itemIdx = count($this->data['item']) - 1;
        $this->data['item'][$itemIdx]['procedureLinkId'][] = $sequence;
        $procedure = [
            'sequence' => $sequence,
            'procedureCodeableConcept' => [
                'coding' => [['system' => $system, 'code' => $code]],
            ],
        ];
        if ($date) {
            $procedure['date'] = $date;
        }
        $this->data['procedure'][] = $procedure;
        return $this;
    }

    public function addItemDetail(
        int $detailSequence,
        string $productOrServiceSystem,
        string $productOrServiceCode,
        string $productOrServiceDisplay
    ): self {
        $itemIdx = count($this->data['item']) - 1;
        $this->data['item'][$itemIdx]['detailLinkId'][] = $detailSequence;
        $this->data['item'][$itemIdx]['detail'][] = [
            'sequence' => $detailSequence,
            'productOrService' => [
                'coding' => [['system' => $productOrServiceSystem, 'code' => $productOrServiceCode, 'display' => $productOrServiceDisplay]],
            ],
        ];
        return $this;
    }

    public function addItemDetailSubDetail(
        int $subDetailSequence,
        string $productOrServiceSystem,
        string $productOrServiceCode,
        string $productOrServiceDisplay
    ): self {
        $itemIdx = count($this->data['item']) - 1;
        $detailIdx = count($this->data['item'][$itemIdx]['detail']) - 1;
        $this->data['item'][$itemIdx]['detail'][$detailIdx]['subDetailLinkId'][] = $subDetailSequence;
        $this->data['item'][$itemIdx]['detail'][$detailIdx]['subDetail'][] = [
            'sequence' => $subDetailSequence,
            'productOrService' => [
                'coding' => [['system' => $productOrServiceSystem, 'code' => $productOrServiceCode, 'display' => $productOrServiceDisplay]],
            ],
        ];
        return $this;
    }

    public function setTotal(float $value, string $system = 'urn:iso:std:iso:4217', string $code = 'IDR'): self
    {
        $this->data['total'] = [
            'value' => $value,
            'currency' => $code,
        ];
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

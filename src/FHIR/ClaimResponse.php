<?php

namespace Satusehat\Integration\FHIR;

/**
 * ClaimResponse FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/claimresponse.html
 */
class ClaimResponse
{
    public array $data = ['resourceType' => 'ClaimResponse'];

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

    public function setPatient(string $reference): self
    {
        $this->data['patient'] = ['reference' => $reference];
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->data['created'] = $dateTime;
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->data['insurer'] = ['reference' => $reference];
        return $this;
    }

    public function setRequestor(string $reference): self
    {
        $this->data['requestor'] = ['reference' => $reference];
        return $this;
    }

    public function setRequest(string $reference): self
    {
        $this->data['request'] = ['reference' => $reference];
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

    public function setPreAuthRef(string $preAuthRef): self
    {
        $this->data['preAuthRef'] = $preAuthRef;
        return $this;
    }

    public function setPreAuthPeriod(string $start, string $end = ''): self
    {
        $this->data['preAuthPeriod'] = ['start' => $start];
        if ($end) {
            $this->data['preAuthPeriod']['end'] = $end;
        }
        return $this;
    }

    public function setPayeeType(string $system, string $code, string $display = ''): self
    {
        $this->data['payeeType'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function addItem(
        int $itemSequence,
        ?int $detailSequence = null,
        ?int $subDetailSequence = null
    ): self {
        $item = ['itemSequence' => $itemSequence];
        if ($detailSequence !== null) {
            $item['detailSequence'] = $detailSequence;
        }
        if ($subDetailSequence !== null) {
            $item['subDetailSequence'] = $subDetailSequence;
        }
        $this->data['item'][] = $item;
        return $this;
    }

    public function addItemAdjudication(
        int $itemSequence,
        string $categorySystem,
        string $categoryCode,
        ?string $categoryDisplay = null,
        ?float $value = null,
        ?string $reasonSystem = null,
        ?string $reasonCode = null
    ): self {
        $adjudication = [
            'itemSequence' => $itemSequence,
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode, 'display' => $categoryDisplay ?? '']],
            ],
        ];
        if ($value !== null) {
            $adjudication['value'] = $value;
        }
        if ($reasonSystem && $reasonCode) {
            $adjudication['reason'] = [
                'coding' => [['system' => $reasonSystem, 'code' => $reasonCode]],
            ];
        }
        $this->data['item'][] = $adjudication;
        return $this;
    }

    public function addAddItem(
        int $itemSequence,
        string $productOrServiceSystem,
        string $productOrServiceCode,
        ?string $productOrServiceDisplay = null
    ): self {
        $addItem = [
            'itemSequence' => [$itemSequence],
            'productOrService' => [
                'coding' => [['system' => $productOrServiceSystem, 'code' => $productOrServiceCode, 'display' => $productOrServiceDisplay ?? '']],
            ],
        ];
        $this->data['addItem'][] = $addItem;
        return $this;
    }

    public function addAddItemAdjudication(
        int $sequenceLinkId,
        string $categorySystem,
        string $categoryCode,
        ?float $value = null
    ): self {
        $itemIdx = count($this->data['addItem']) - 1;
        $adjudication = [
            'itemSequence' => $sequenceLinkId,
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
            ],
        ];
        if ($value !== null) {
            $adjudication['value'] = $value;
        }
        $this->data['addItem'][$itemIdx]['adjudication'][] = $adjudication;
        return $this;
    }

    public function addError(int $itemSequence, string $codeSystem, string $code, string $display = ''): self
    {
        $this->data['error'][] = [
            'itemSequence' => $itemSequence,
            'code' => [
                'coding' => [['system' => $codeSystem, 'code' => $code, 'display' => $display]],
            ],
        ];
        return $this;
    }

    public function addCoverage(
        int $sequence,
        string $reference,
        ?bool $focal = null,
        ?string $prioritySystem = null,
        ?string $priorityCode = null
    ): self {
        $coverage = ['sequence' => $sequence, 'reference' => $reference];
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

    public function setTotal(float $value, string $categorySystem, string $categoryCode): self
    {
        $this->data['total'][] = [
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
            ],
            'value' => $value,
        ];
        return $this;
    }

    public function setPayment(float $value, string $typeSystem, string $typeCode, string $date, ?string $reference = null): self
    {
        $this->data['payment'] = [
            'type' => ['coding' => [['system' => $typeSystem, 'code' => $typeCode]]],
            'date' => $date,
            'amount' => ['value' => $value],
        ];
        if ($reference) {
            $this->data['payment']['reference'] = $reference;
        }
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

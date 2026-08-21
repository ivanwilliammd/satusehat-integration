<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ClaimResponse FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/claimresponse.html
 */
class PayloadBuilderClaimResponse extends Builder
{
    protected string $resourceType = 'ClaimResponse';

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

    public function setPatient(string $reference): self
    {
        $this->set('patient', ['reference' => $reference]);
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
        return $this;
    }

    public function setInsurer(string $reference): self
    {
        $this->set('insurer', ['reference' => $reference]);
        return $this;
    }

    public function setRequestor(string $reference): self
    {
        $this->set('requestor', ['reference' => $reference]);
        return $this;
    }

    public function setRequest(string $reference): self
    {
        $this->set('request', ['reference' => $reference]);
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

    public function setPreAuthRef(string $preAuthRef): self
    {
        $this->set('preAuthRef', $preAuthRef);
        return $this;
    }

    public function setPreAuthPeriod(string $start, string $end = ''): self
    {
        $this->set('preAuthPeriod/start', $start);
        if ($end) {
            $this->set('preAuthPeriod/end', $end);
        }
        return $this;
    }

    public function setPayeeType(string $system, string $code, string $display = ''): self
    {
        $this->set('payeeType', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
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
        $this->push('item', $item);
        return $this;
    }

    public function addItemAdjudication(
        int $itemSequence,
        string $categorySystem,
        string $categoryCode,
        ?float $value = null,
        ?string $reasonSystem = null,
        ?string $reasonCode = null
    ): self {
        $adjudication = [
            'itemSequence' => $itemSequence,
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
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
        $this->push('item', $adjudication);
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
        $this->push('addItem', $addItem);
        return $this;
    }

    public function addAddItemAdjudication(
        int $sequenceLinkId,
        string $categorySystem,
        string $categoryCode,
        ?float $value = null
    ): self {
        $itemIdx = count($this->data['addItem'] ?? []) - 1;
        $adjudication = [
            'itemSequence' => $sequenceLinkId,
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
            ],
        ];
        if ($value !== null) {
            $adjudication['value'] = $value;
        }
        $this->push("addItem/{$itemIdx}/adjudication", $adjudication);
        return $this;
    }

    public function addError(int $itemSequence, string $codeSystem, string $code, string $display = ''): self
    {
        $this->push('error', [
            'itemSequence' => $itemSequence,
            'code' => [
                'coding' => [['system' => $codeSystem, 'code' => $code, 'display' => $display]],
            ],
        ]);
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
        $this->push('coverage', $coverage);
        return $this;
    }

    public function setTotal(float $value, string $categorySystem, string $categoryCode): self
    {
        $this->push('total', [
            'category' => [
                'coding' => [['system' => $categorySystem, 'code' => $categoryCode]],
            ],
            'value' => $value,
        ]);
        return $this;
    }

    public function setPayment(float $value, string $typeSystem, string $typeCode, string $date, ?string $reference = null): self
    {
        $this->set('payment/type', ['coding' => [['system' => $typeSystem, 'code' => $typeCode]]]);
        $this->set('payment/date', $date);
        $this->set('payment/amount/value', $value);
        if ($reference) {
            $this->set('payment/reference', $reference);
        }
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

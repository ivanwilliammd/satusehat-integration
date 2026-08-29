<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ImmunizationEvaluation FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/immunizationevaluation.html
 */
class PayloadBuilderImmunizationEvaluation extends Builder
{
    protected string $resourceType = 'ImmunizationEvaluation';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value, ?string $use = null, ?string $typeCode = null, ?string $typeDisplay = null): self
    {
        $ident = ['system' => $system, 'value' => $value];
        if ($use !== null) $ident['use'] = $use;
        if ($typeCode !== null) {
            $ident['type'] = [
                'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => $typeCode, 'display' => $typeDisplay ?? $typeCode]]
            ];
        }
        $this->add('identifier', $ident);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setCode(string $system, string $code, string $display): self
    {
        $this->set('code', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]]
        ]);
        return $this;
    }

    public function setSubject(string $reference, ?string $display = null): self
    {
        $subject = ['reference' => $reference];
        if ($display !== null) $subject['display'] = $display;
        $this->set('subject', $subject);
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->set('encounter', ['reference' => $reference]);
        return $this;
    }
}
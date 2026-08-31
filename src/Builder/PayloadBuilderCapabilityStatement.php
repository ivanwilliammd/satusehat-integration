<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * CapabilityStatement FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/capabilitystatement.html
 */
class PayloadBuilderCapabilityStatement extends Builder
{
    protected string $resourceType = 'CapabilityStatement';

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

    public function setDate(string $value): self
    {
        $this->set('date', $value);
        return $this;
    }

    public function setKind(string $system, string $code, string $display = ''): self
    {
        $this->set('kind', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setFhirVersion(string $system, string $code, string $display = ''): self
    {
        $this->set('fhirVersion', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setFormat(string $system, string $code, string $display = ''): self
    {
        $this->set('format', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setRest(string $system, string $code, string $display = ''): self
    {
        $this->set('rest', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }
}

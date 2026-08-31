<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * SpecimenDefinition FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/specimendefinition.html
 */
class PayloadBuilderSpecimenDefinition extends Builder
{
    protected string $resourceType = 'SpecimenDefinition';

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

    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->set('type', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setCollection(string $value): self
    {
        $this->set('collection', $value);
        return $this;
    }

    public function setPreparation(string $system, string $code, string $display = ''): self
    {
        $this->set('preparation', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }
}

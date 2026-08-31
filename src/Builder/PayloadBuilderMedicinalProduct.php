<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * MedicinalProduct FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/medicinalproduct.html
 */
class PayloadBuilderMedicinalProduct extends Builder
{
    protected string $resourceType = 'MedicinalProduct';

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

    public function setName(string $value): self
    {
        $this->set('name', $value);
        return $this;
    }

    public function setManufacturer(string $reference, ?string $display = null): self
    {
        $ref = ['reference' => $reference];
        if ($display !== null) $ref['display'] = $display;
        $this->set('manufacturer', $ref);
        return $this;
    }

    public function setProductClass(string $system, string $code, string $display = ''): self
    {
        $this->set('productClass', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }
}

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
        $this->push('identifier', $ident);
        return $this;
    }

    /**
     * @deprecated menulis `status` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    /**
     * Jenis spesimen yang diharapkan.
     *
     * R4 tidak punya `SpecimenDefinition.type` di tingkat atas: jenis ini melekat pada
     * `typeTested[].type`. Builder ini menulis ke sana, membuat `typeTested[0]` bila belum ada.
     */
    public function setType(string $system, string $code, string $display = ''): self
    {
        $this->data['typeTested'] = $this->data['typeTested'] ?? [[]];
        $this->data['typeTested'][0]['type'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function setCollection(string $value): self
    {
        $this->set('collection', $value);
        return $this;
    }

    /**
     * Cara penyiapan spesimen.
     *
     * R4 menempatkannya di `typeTested[].container.preparation`, bukan di tingkat atas.
     */
    public function setPreparation(string $system, string $code, string $display = ''): self
    {
        $this->data['typeTested'] = $this->data['typeTested'] ?? [[]];
        $this->data['typeTested'][0]['container']['preparation'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }
}

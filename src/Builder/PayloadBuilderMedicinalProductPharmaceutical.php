<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * MedicinalProductPharmaceutical FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/medicinalproductpharmaceutical.html
 */
class PayloadBuilderMedicinalProductPharmaceutical extends Builder
{
    protected string $resourceType = 'MedicinalProductPharmaceutical';

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
     * @deprecated `route` tidak ada di FHIR R4 maupun R5; jangan diandalkan
     */
    public function setRoute(string $system, string $code, string $display = ''): self
    {
        $this->set('route', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    /**
     * @deprecated `doseForm` tidak ada di FHIR R4 maupun R5; jangan diandalkan
     */
    public function setDoseForm(string $system, string $code, string $display = ''): self
    {
        $this->set('doseForm', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }

    public function setIngredient(string $system, string $code, string $display = ''): self
    {
        $this->set('ingredient', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
        return $this;
    }
}

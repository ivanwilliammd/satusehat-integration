<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Ratio;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\Terminology\MedicationTerminology;

/**
 * Medication FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medication.html
 */
class PayloadBuilderMedication extends Builder
{
    protected string $resourceType = 'Medication';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
        $this->data['meta']['profile'][] = 'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication';
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setManufacturer(Reference $manufacturer): self
    {
        $this->set('manufacturer', $manufacturer->toArray());
        return $this;
    }

    public function setForm(CodeableConcept $form): self
    {
        $this->set('form', $form->toArray());
        return $this;
    }

    public function addIngredient(
        CodeableConcept $itemCodeableConcept,
        bool $isActive,
        ?Quantity $strength = null
    ): self {
        $ingredient = [
            'itemCodeableConcept' => $itemCodeableConcept->toArray(),
            'isActive' => $isActive,
        ];

        if ($strength !== null) {
            $ingredient['strength'] = $strength->toArray();
        }

        $this->push('ingredient', $ingredient);
        return $this;
    }

    public function setBatch(string $lotNumber, string $expirationDate): self
    {
        $this->set('batch', [
            'lotNumber' => $lotNumber,
            'expirationDate' => $expirationDate,
        ]);
        return $this;
    }

    public function addMedicationType(string $code, string $display): self
    {
        $medicationTypeOption = [
            'NC' => 'Non-compound',
            'SD' => 'Gives of such doses',
            'EP' => 'Divide into equal parts',
        ];

        $this->push('extension', [
            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
            'valueCodeableConcept' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                        'code' => $code,
                        'display' => $medicationTypeOption[$display] ?? $display,
                    ],
                ],
            ],
        ]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

use Satusehat\Integration\Terminology\MedicationTerminology;

class Medication extends OAuth2Client
{
    # Declare required terminology
    public $medication_form;
    public $drug_form;

    public function __construct()
    {
        $medication_terminology = new MedicationTerminology();
        $this->medication_form = $medication_terminology->medication_form;
        $this->drug_form = $medication_terminology->drug_form;
    }

    public array $medication = [
        'resourceType' => 'Medication',
        'meta' => [
            'profile' => [
                'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication',
            ],
        ],
    ];

    public function addIdentifier($medication_id)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/medication/'.$this->organization_id;
        $identifier['value'] = $medication_id;
        $identifier['use'] = 'official';

        $this->medication['identifier'][] = $identifier;
    }

    public function setCode($code = null, $display = null)
    {
        $coding['system'] = 'http://sys-ids.kemkes.go.id/kfa';
        $coding['code'] = $code;

        if ($display) {
            $coding['display'] = $display;
        }

        $this->medication['code']['coding'][] = $coding;
    }

    public function setStatus($status = 'active')
    {
        $this->medication['status'] = $status;
    }

    public function setManufacturer($manufacturer = null)
    {
        $this->medication['manufacturer']['reference'] = 'Organization/'.($manufacturer ? $manufacturer : $this->organization_id);
    }

    public function setForm($code = null)
    {
        # Check display, if not exist, throw FHIRException
        if (! array_key_exists($code, $this->medication_form)) {
            throw new FHIRException("Medication form code not found");
        }

        $this->medication['form']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/medication-form-codes',
            'code' => $code,
            'display' => $this->medication_form[$code],
        ];
    }

    public function setAmount($numerator = null, $numerator_unit = null, $denominator = 1, $denominator_unit = null)
    {
        # If numerator is null, throw FHIRException, similarly with numerator_unit
        if (! $numerator || ! is_numeric($numerator)) {
            throw new FHIRException("Numerator is required in numeric format");
        }

        if (! $numerator_unit) {
            throw new FHIRException("Numerator unit is required (see http://unitsofmeasure.org)");
        }

        if (! $denominator_unit) {
            throw new FHIRException("Denominator unit is required (see http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm)");
        }

        $amount['numerator']['value'] = $numerator;
        $amount['numerator']['system'] = 'http://unitsofmeasure.org';
        $amount['numerator']['code'] = $numerator_unit;

        $amount['denominator']['value'] = $denominator;
        $amount['denominator']['system'] = $this->drug_form[$denominator_unit]['system'];
        $amount['denominator']['code'] = $denominator_unit;
    }

    public function addIngredient($itemCode = null, $itemDisplay = null, $numerator = null, $numerator_unit = null, $denominator = 1, $denominator_unit = null)
    {
        # If itemCode is null, throw FHIRException, similarly with itemDisplay
        if (! $itemCode) {
            throw new FHIRException("Item code is required");
        }

        if (! $itemDisplay) {
            throw new FHIRException("Item display is required");
        }

        $ingredient['item']['coding'][] = [
            'system' => 'http://sys-ids.kemkes.go.id/kfa',
            'code' => $itemCode,
            'display' => $itemDisplay,
        ];

        $ingredient['isActive'] = true;

        $ingredient['strength']['numerator']['value'] = $numerator;
        $ingredient['strength']['numerator']['system'] = 'http://unitsofmeasure.org';
        $ingredient['strength']['numerator']['code'] = $numerator_unit;

        $ingredient['strength']['denominator']['value'] = $denominator;
        $ingredient['strength']['denominator']['system'] = $this->drug_form[$denominator_unit]['system'];
        $ingredient['strength']['denominator']['code'] = $denominator_unit;

        $this->medication['ingredient'][] = $ingredient;
    }

    public function setBatch($lotNumber = null, $expirationDate = null)
    {
        # If lotNumber is null, throw FHIRException, similarly with expirationDate
        if (! $lotNumber) {
            throw new FHIRException("Lot number is required");
        }

        if (! $expirationDate) {
            throw new FHIRException("Expiration date is required");
        }

        $batch['lotNumber'] = $lotNumber;
        $batch['expirationDate'] = $expirationDate;

        $this->medication['batch'] = $batch;
    }

    public function setMedicationType($code = 'NC')
    {
        $medicationTypeOption = array(
            'NC' => 'Non-compound',
            'SD' => 'Gives of such doses',
            'EP' => 'Divide into equal parts'
        );

        $medicationType['url'] = 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType';
        $medicationType['valueCodeableConcept']['coding'][] = [
            'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
            'code' => $code,
            'display' => $medicationTypeOption[$code],
        ];

        $this->medication['extension'][] = $medicationType;
    }

    public function json()
    {
        return json_encode($this->medication, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Medication', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->medication['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Medication', $id, $payload);

        return [$statusCode, $res];
    }

}

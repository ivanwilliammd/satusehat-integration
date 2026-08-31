<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Terminology\MedicationTerminology;

class MedicationStatement extends OAuth2Client
{
    public array $medication_statement = [
        'resourceType' => 'MedicationStatement',
    ];

    public function __construct()
    {
        parent::__construct();

        $medication_terminology = new MedicationTerminology;

        $this->medication_form = $medication_terminology->medication_form;
        $this->medication_statement_category = $medication_terminology->medication_statement_category;
        $this->drug_therapy_status = $medication_terminology->drug_therapy_status;
        $this->time_interval = $medication_terminology->time_interval;
    }

    public $medication_form;
    public $medication_statement_category;
    public $drug_therapy_status;
    public $time_interval;

    public function setStatus($status = 'completed')
    {
        $status = strtolower($status);
        if (! in_array($status, ['active', 'completed', 'entered-in-error', 'intended', 'stopped', 'on-hold', 'unknown', 'not-taken'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->medication_statement['status'] = $status;
        return $this;
    }

    public function setStatusReason($status_code = null)
    {
        $this->medication_statement['statusReason'][] = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $status_code,
                    'display' => $this->drug_therapy_status[$status_code] ?? null,
                ],
            ],
        ];
        return $this;
    }

    public function setMedicationReference($reference, $display)
    {
        $this->medication_statement['medicationReference']['reference'] = $reference;
        $this->medication_statement['medicationReference']['display'] = $display;
        return $this;
    }

    public function setSubject($subjectId, $name)
    {
        $this->medication_statement['subject']['reference'] = 'Patient/'.$subjectId;
        $this->medication_statement['subject']['display'] = $name;
        return $this;
    }

    public function setContext($contextId, $display = null)
    {
        $this->medication_statement['context']['reference'] = 'Encounter/'.$contextId;
        $this->medication_statement['context']['display'] = $display ?? 'Kunjungan '.$contextId;
        return $this;
    }

    public function setDateAsserted($dateAsserted = null)
    {
        $this->medication_statement['dateAsserted'] = $dateAsserted
            ? date('Y-m-d\TH:i:sP', strtotime($dateAsserted))
            : date('Y-m-d\TH:i:sP');
        return $this;
    }

    public function setEffectiveDateTime($effectiveDateTime = null)
    {
        $this->medication_statement['effectiveDateTime'] = $effectiveDateTime
            ? date('Y-m-d\TH:i:sP', strtotime($effectiveDateTime))
            : date('Y-m-d\TH:i:sP');
        return $this;
    }

    public function setInformationSource($sourceId, $name)
    {
        $this->medication_statement['informationSource']['reference'] = 'Patient/'.$sourceId;
        $this->medication_statement['informationSource']['display'] = $name;
        return $this;
    }

    public function addDosageInstruction($text, $frequency, $period, $periodUnit)
    {
        $dosage_instruction['text'] = $text;
        $dosage_instruction['timing']['repeat'] = [
            'frequency' => $frequency,
            'period' => $period,
            'periodUnit' => $periodUnit,
        ];

        $this->medication_statement['dosage'][] = $dosage_instruction;
        return $this;
    }

    public function addContained(Medication $medication)
    {
        $this->medication_statement['contained'][] = json_decode($medication->json(), true);
        return $this;
    }

    public function json(): string
    {
        if (! isset($this->medication_statement['status'])) {
            $this->setStatus();
        }

        if (! isset($this->medication_statement['subject'])) {
            throw new FHIRMissingProperty('Subject is required');
        }

        if (! isset($this->medication_statement['medicationReference'])) {
            throw new FHIRMissingProperty('Medication is required');
        }

        return json_encode($this->medication_statement, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post(): array
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('MedicationStatement', $payload);

        return [$statusCode, $res];
    }

    public function put($id): array
    {
        $this->medication_statement['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('MedicationStatement', $id, $payload);

        return [$statusCode, $res];
    }
}

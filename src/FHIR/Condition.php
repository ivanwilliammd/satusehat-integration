<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Terminology\Icd10;
use Satusehat\Integration\Exception\FHIR\FHIRException;

class Condition extends OAuth2Client
{
    public array $condition = ['resourceType' => 'Condition'];

    public function addClinicalStatus($clinical_status = 'active')
    {
        switch ($clinical_status) {
            case 'active':
                $code = 'active';
                $display = 'Active';
                break;
            case 'recurrence':
                $code = 'recurrence';
                $display = 'Recurrence';
                break;
            case 'inactive':
                $code = 'inactive';
                $display = 'Inactive';
                break;
            case 'remission':
                $code = 'remission';
                $display = 'Remission';
                break;
            case 'resolved':
                $code = 'resolved';
                $display = 'Resolved';
                break;
            default:
                $code = 'active';
                $display = 'Active';
        }

        $this->condition['clinicalStatus']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
            'code' => $code,
            'display' => $display,
        ];
    }

    public function addCategory($category = 'diagnosis')
    {
        $category = strtolower($category);
        switch ($category) {
            case 'diagnosis':
                $code = 'encounter-diagnosis';
                $display = 'Encounter Diagnosis';
                break;
            case 'keluhan':
                $code = 'problem-list-item';
                $display = 'Problem List Item';
                break;
            default:
                $code = 'encounter-diagnosis';
                $display = 'Encounter Diagnosis';
        }

        $this->condition['category'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addCode($code = null, $display = null)
    {
        // Look in database if display is null
        $code_check = Icd10::where('icd10_code', $code)->first();

        // Handling if incomplete code / display
        if (!$code_check) {
            throw new FHIRException('Kode ICD10 tidak ditemukan');
        }

        $display = $display ? $display : $code_check->icd10_en;

        $this->condition['code']['coding'][] = [
            'system' => 'http://hl7.org/fhir/sid/icd-10',
            'code' => strtoupper($code),
            'display' => $display,
        ];
    }

    public function setSubject($subjectId, $name)
    {
        $this->condition['subject']['reference'] = 'Patient/' . $subjectId;
        $this->condition['subject']['display'] = $name;
    }

    public function setEncounter($encounterId, $display = null, $bundle = false)
    {
        $this->condition['encounter']['reference'] = ($bundle ? 'urn:uuid:' : 'Encounter/') . $encounterId;
        $this->condition['encounter']['display'] = $display ? $display : 'Kunjungan ' . $encounterId;
    }

    public function setOnsetDateTime($onset_date_time = null)
    {
        $this->condition['onsetDateTime'] = $onset_date_time ?
            date("Y-m-d\TH:i:sP", strtotime($onset_date_time)) :
            date("Y-m-d\TH:i:sP");
    }

    public function setRecordedDate($recorded_date = null)
    {
        $this->condition['recordedDate'] = $recorded_date ?
            date("Y-m-d\TH:i:sP", strtotime($recorded_date)) :
            date("Y-m-d\TH:i:sP");
    }

    public function json()
    {
        // Add default clinical status
        if (!array_key_exists('clinicalStatus', $this->condition)) {
            $this->addClinicalStatus();
        }

        // Add default category
        if (!array_key_exists('category', $this->condition)) {
            $this->addCategory();
        }

        // Add default OnsetDateTime
        if (!array_key_exists('onsetDateTime', $this->condition)) {
            $this->setOnsetDateTime();
        }

        // Add default RecordedDate
        if (!array_key_exists('recordedDate', $this->condition)) {
            $this->setRecordedDate();
        }

        // Subject is required
        if (!array_key_exists('subject', $this->condition)) {
            return 'Please use condition->setSubject($subjectId, $name) to pass the data';
        }

        // Encounter is required
        if (!array_key_exists('encounter', $this->condition)) {
            return 'Please use condition->setEncounter($encounterId) to pass the data';
        }

        // ICD-10 is required
        if (!array_key_exists('code', $this->condition)) {
            return 'Please use condition->addCode($code, $display) to pass the data';
        }

        return json_encode($this->condition, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Condition', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Condition', $id, $payload);

        return [$statusCode, $res];
    }
}

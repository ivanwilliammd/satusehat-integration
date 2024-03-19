<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Models\Icd10;
use Satusehat\Integration\OAuth2ClientTenant;

class Encounter extends OAuth2ClientTenant
{
    public $encounter = ['resourceType' => 'Encounter'];

    public function addRegistrationId($registration_id)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/encounter/' . $this->organization_id;
        $identifier['value'] = $registration_id;

        $this->encounter['identifier'][] = $identifier;
    }

    private function statusHistoryValidate($arr, $status)
    {
        
        $lookingAt = $arr['statusHistory'];
        foreach ($lookingAt as $data) {
            if ($data['status'] === $status) {
                return true; 
            }
        }
        return false; 
    }

    public function setArrived($timestamp)
    {
        if (!isset($this->encounter['statusHistory'])) {
            $this->encounter['statusHistory'] = [];
        }

        $validate = $this->statusHistoryValidate($this->encounter, 'arrived');

        if ($validate) {
            return;
        }

        $statusHistory_arrived =  [
            'status' => 'arrived',
            'period' => [
                'start' => date("Y-m-d\TH:i:sP", strtotime($timestamp)),
            ],
        ];

        $this->encounter['status'] = 'arrived';
        $this->encounter['period']['start'] = $statusHistory_arrived['period']['start'];
        $this->encounter['statusHistory'][] = $statusHistory_arrived;
    }

    public function setInProgress($timestamp_start, $timestamp_end)
    {

        if (!isset($this->encounter['statusHistory'])) {
            return 'Please use $this->setArrived first';
        }

        $validate = $this->statusHistoryValidate($this->encounter, 'in-progress');

        if ($validate) {
            return;
        }

        $atomTimestamp = [
            'start' => date("Y-m-d\TH:i:sP", strtotime($timestamp_start)),
            'end' => date("Y-m-d\TH:i:sP", strtotime($timestamp_end)),
        ];

        $statusHistory_inprogress = [
            'status' => 'in-progress',
            'period' => [
                'start' => $atomTimestamp['start'],
                'end' => $atomTimestamp['end']
            ]
        ];


        $this->encounter['status'] = 'in-progress';
        $this->encounter['period']['end'] = $atomTimestamp['end'];
        $this->encounter['statusHistory'][0]['period']['end'] = $atomTimestamp['start'];
        $this->encounter['statusHistory'][] = $statusHistory_inprogress;
    }

    public function setFinished($timestamp)
    {

        if (!isset($this->encounter['statusHistory'])) {
            return 'Please use $this->setArrived first';
        }

        $validate = $this->statusHistoryValidate($this->encounter, 'finished');

        if ($validate) {
            return;
        }

        $date = date("Y-m-d\TH:i:sP", strtotime($timestamp));
            
        $statusHistory_finished = [
            'status' => 'finished',
            'period' => [
                'start' => $date,
                'end' => $date,
            ]
        ];
        
        $this->encounter['status'] = 'finished';
        $this->encounter['period']['end'] = $date;
        $this->encounter['statusHistory'][] = $statusHistory_finished;
    }

    public function setConsultationMethod($consultation_method)
    {
        switch ($consultation_method) {
            case 'RAJAL':
                $class_code = 'AMB';
                $class_display = 'ambulatory';
                break;
            case 'IGD':
                $class_code = 'EMER';
                $class_display = 'emergency';
                break;
            case 'RANAP':
                $class_code = 'IMP';
                $class_display = 'inpatient encounter';
                break;
            case 'HOMECARE':
                $class_code = 'HH';
                $class_display = 'home health';
                break;
            case 'TELEKONSULTASI':
                $class_code = 'TELE';
                $class_display = 'teleconsultation';
                break;
            default:
                return 'consultation_method is invalid (Choose RAJAL / IGD / RANAP/ HOMECARE / TELEKONSULTASI)';
        }

        $class['code'] = $class_code;
        $class['display'] = $class_display;
        $class['system'] = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';

        $this->encounter['class'] = $class;
    }

    public function setSubject($subjectId, $name)
    {
        $this->encounter['subject']['reference'] = 'Patient/' . $subjectId;
        $this->encounter['subject']['display'] = $name;
    }

    public function addParticipant($participantId, $name, $type = 'ATND', $display = 'attender')
    {
        $participant['individual']['reference'] = 'Practitioner/' . $participantId;
        $participant['individual']['display'] = $name;
        $participant['type'][]['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
            'code' => $type,
            'display' => $display,
        ];

        $this->encounter['participant'][] = $participant;
    }

    public function addLocation($locationId, $name)
    {
        $location['location']['reference'] = 'Location/' . $locationId;
        $location['location']['display'] = $name;

        $this->encounter['location'][] = $location;
    }

    public function setServiceProvider()
    {
        $this->encounter['serviceProvider']['reference'] = 'Organization/' . $this->organization_id;
    }

    public function addDiagnosis($id, $code, $display = null)
    {
        // Look in database if display is null
        $code_check = Icd10::where('icd10_code', $code)->first();

        // Handling if incomplete code / display
        if (!$code_check) {
            return 'Kode ICD-10 invalid';
        }

        $display = $display ? $display : $code_check->icd10_en;

        // Create Encounter.diagnosis content
        $diagnosis['condition']['reference'] = 'Condition/' . $id;
        $diagnosis['condition']['display'] = 'Condition/' . $display;
        $diagnosis['use']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
            'code' => 'DD',
            'display' => 'Discharge diagnosis',
        ];

        // Determine ranking
        if (!array_key_exists('diagnosis', $this->encounter)) {
            $rank = 1;
        } else {
            $rank = count($this->encounter['diagnosis']) + 1;
        }
        $diagnosis['rank'] = $rank;

        $this->encounter['diagnosis'][] = $diagnosis;
    }

    public function json()
    {
        // Status is required
        if (!array_key_exists('status', $this->encounter)) {
            return 'Please use encounter->statusHistory([timestamp array]) to add the status';
        }

        // Class is required
        if (!array_key_exists('class', $this->encounter)) {
            return 'Please use encounter->setConsultationMethod($method) to pass the data';
        }

        // Subject is required
        if (!array_key_exists('subject', $this->encounter)) {
            return 'Please use encounter->setSubject($subjectId, $name) to pass the data';
        }

        // Participant is required
        if (!array_key_exists('participant', $this->encounter)) {
            return 'Please use encounter->addParticipant($participantId, $name) to pass the data';
        }

        // Location is required
        if (!array_key_exists('location', $this->encounter)) {
            return 'Please use encounter->addLocation($locationId, $name) to pass the data';
        }

        // Add default ServiceProvider
        if (!array_key_exists('serviceProvider', $this->encounter)) {
            $this->setServiceProvider();
        }

        return json_encode($this->encounter, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = json_decode($this->json());
        [$statusCode, $res] = $this->ss_post('Encounter', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = json_decode($this->json());
        [$statusCode, $res] = $this->ss_put('Encounter', $id, $payload);

        return [$statusCode, $res];
    }
}

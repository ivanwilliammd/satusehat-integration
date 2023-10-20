<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Encounter extends OAuth2Client
{
    public $encounter = ['resourceType' => 'Encounter'];

    public function addRegistrationId($registration_id)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id;
        $identifier['value'] = $registration_id;

        $this->encounter['identifier'][] = $identifier;
    }

    public function addStatusHistory($timestamp)
    {
        // Arrived
        if (array_key_exists('arrived', $timestamp)) {
            $this->encounter['status'] = 'arrived';
            $this->encounter['period']['start'] = $timestamp['arrived'];

            $this->encounter['period']['start'] = $timestamp['arrived'];
            $statusHistory_arrived['status'] = 'arrived';
            $statusHistory_arrived['period']['start'] = $timestamp['arrived'];
        } else {
            return 'arrived is required';
        }

        // In-progress
        if (array_key_exists('in-progress', $timestamp)) {
            $this->encounter['status'] = 'in-progress';

            $statusHistory_inprogress['status'] = 'in-progress';
            $statusHistory_inprogress['period']['start'] = $timestamp['inprogress'];

            $statusHistory_arrived['period']['end'] = $timestamp['inprogress'];
        }

        // Finished
        if (array_key_exists('finished', $timestamp)) {
            $this->encounter['status'] = 'finished';
            $this->encounter['period']['end'] = $timestamp['finished'];

            $statusHistory_finished['status'] = 'finished';
            $statusHistory_finished['period']['start'] = $timestamp['finished'];
            $statusHistory_finished['period']['end'] = $timestamp['finished'];

            $statusHistory_inprogress['period']['end'] = $timestamp['finished'];
        }
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
        $this->encounter['subject']['reference'] = 'Patient/'.$subjectId;
        $this->encounter['subject']['display'] = $name;
    }

    public function addParticipant($participantId, $name, $type = 'ATND', $display = 'attender')
    {
        $participant['individual']['reference'] = 'Practitioner/'.$participantId;
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
        $location['location']['reference'] = 'Location/'.$locationId;
        $location['location']['display'] = $name;

        $this->encounter['location'][] = $location;
    }

    public function setServiceProvider()
    {
        $this->encounter['serviceProvider']['reference'] = 'Organization/'.$this->organization_id;
    }

    public function addDiagnosis($id, $code, $display = null)
    {

        // Look in database if display is null
        $display = $display ? $display : Icd10::where('icd10_code', $code)->first()->icd10_en;

        // Handling if incomplete code / display
        if (! $code && ! $display) {
            return 'Kode ICD-10 invalid';
        }

        // Create Encounter.diagnosis content
        $diagnosis['condition']['reference'] = 'Condition/'.$id;
        $diagnosis['condition']['display'] = 'Condition/'.$display;
        $diagnosis['use']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
            'code' => 'DD',
            'display' => 'Discharge diagnosis',
        ];

        // Determine ranking
        if (! array_key_exists('diagnosis', $this->encounter)) {
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
        if (! array_key_exists('status', $this->encounter)) {
            return 'Please use encounter->statusHistory([timestamp array]) to add the status';
        }

        // Class is required
        if (! array_key_exists('class', $this->encounter)) {
            return 'Please use encounter->setConsultationMethod($method) to pass the data';
        }

        // Subject is required
        if (! array_key_exists('subject', $this->encounter)) {
            return 'Please use encounter->setSubject($subjectId, $name) to pass the data';
        }

        // Participant is required
        if (! array_key_exists('participant', $this->encounter)) {
            return 'Please use encounter->addParticipant($participantId, $name) to pass the data';
        }

        // Location is required
        if (! array_key_exists('location', $this->encounter)) {
            return 'Please use encounter->addLocation($locationId, $name) to pass the data';
        }

        // Add default ServiceProvider
        if (! array_key_exists('serviceProvider', $this->encounter)) {
            $this->setServiceProvider();
        }

        return $this->encounter;
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Encounter', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Encounter', $id, $payload);

        return [$statusCode, $res];
    }
}

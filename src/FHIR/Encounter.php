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
            $statusHistory_arrived['status'] = 'arrived';
            $statusHistory_arrived['period']['start'] = $timestamp['arrived'];
        } else {
            return 'arrived is required';
        }

        // In-progress
        if (array_key_exists('inprogress', $timestamp)) {
            $this->encounter['status'] = 'inprogress';

            $statusHistory_inprogress['status'] = 'in-progress';
            $statusHistory_inprogress['period']['start'] = $timestamp['inprogress'];

            $statusHistory_arrived['period']['end'] = $timestamp['inprogress'];
        } else {
            return 'inprogress is required';
        }

        // Finished
        if (array_key_exists('finished', $timestamp)) {
            $this->encounter['status'] = 'finished';

            $statusHistory_finished['status'] = 'finished';
            $statusHistory_finished['period']['start'] = $timestamp['finished'];
            $statusHistory_finished['period']['end'] = $timestamp['finished'];

            $statusHistory_inprogress['period']['end'] = $timestamp['finished'];
        } else {
            return 'finished is required';
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
            case 'TELECONSULTATION':
                $class_code = 'TELE';
                $class_display = 'teleconsultation';
                break;
            default:
                return 'consultation_method is invalid (Choose RAJAL / IGD / RANAP/ HOMECARE / TELECONSULTATION)';
        }

        $class['code'] = $class_code;
        $class['display'] = $class_display;
        $class['system'] = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';

        $this->encounter['class'] = $class;
    }

    public function json()
    {
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

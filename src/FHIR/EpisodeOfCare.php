<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class EpisodeOfCare extends OAuth2Client
{
    public array $episodeOfCare = ['resourceType' => 'EpisodeOfCare'];

    public function addIdentifier($system, $value)
    {
        $this->episodeOfCare['identifier'][] = [
            'system' => $system,
            'value' => $value,
        ];
    }

    public function setStatus($status = 'active')
    {
        $validStatuses = ['planned', 'waitlist', 'active', 'onhold', 'finished', 'cancelled', 'entered-in-error'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->episodeOfCare['status'] = $status;
    }

    public function addType($system, $code, $display, $text = null)
    {
        $coding = [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ];

        $type = ['coding' => [$coding]];
        if ($text !== null) {
            $type['text'] = $text;
        }

        $this->episodeOfCare['type'][] = $type;
    }

    public function setPatient($reference, $display = null)
    {
        $this->episodeOfCare['patient'] = ['reference' => $reference];
        if ($display !== null) {
            $this->episodeOfCare['patient']['display'] = $display;
        }
    }

    public function setManagingOrganization($reference, $display = null)
    {
        $this->episodeOfCare['managingOrganization'] = ['reference' => $reference];
        if ($display !== null) {
            $this->episodeOfCare['managingOrganization']['display'] = $display;
        }
    }

    public function setPeriod($start, $end = null)
    {
        $this->episodeOfCare['period'] = ['start' => $start];
        if ($end !== null) {
            $this->episodeOfCare['period']['end'] = $end;
        }
    }

    public function addReferralRequest($reference)
    {
        $this->episodeOfCare['referralRequest'][] = ['reference' => $reference];
    }

    public function setCareManager($reference, $display = null)
    {
        $this->episodeOfCare['careManager'] = ['reference' => $reference];
        if ($display !== null) {
            $this->episodeOfCare['careManager']['display'] = $display;
        }
    }

    public function addTeam($reference)
    {
        $this->episodeOfCare['team'][] = ['reference' => $reference];
    }

    public function addAccount($reference)
    {
        $this->episodeOfCare['account'][] = ['reference' => $reference];
    }

    public function addDiagnosis($condition, $role = null, $rank = null)
    {
        $diagnosis = ['condition' => ['reference' => $condition]];

        if ($role !== null) {
            $diagnosis['role'] = $role;
        }

        if ($rank !== null) {
            $diagnosis['rank'] = $rank;
        }

        $this->episodeOfCare['diagnosis'][] = $diagnosis;
    }

    public function json()
    {
        if (! array_key_exists('status', $this->episodeOfCare)) {
            $this->setStatus();
        }

        if (! array_key_exists('patient', $this->episodeOfCare)) {
            throw new FHIRMissingProperty('EpisodeOfCare.patient is required');
        }

        return json_encode($this->episodeOfCare, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('EpisodeOfCare', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->episodeOfCare['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('EpisodeOfCare', $id, $payload);

        return [$statusCode, $res];
    }
}

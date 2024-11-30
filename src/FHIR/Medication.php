<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

class Medication extends OAuth2Client
{
    public array $medication = [
        'resourceType' => 'Medication',
    ];

    public function addIdentifier($identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/medication/'.$this->organization_id;
        $identifier['value'] = $identifier;
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

    public function setForm($form = null)
    {
        $this->medication['form']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/medication-form-codes',
            'code' => $form,
        ];
    }
}

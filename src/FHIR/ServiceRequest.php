<?php

namespace Satusehat\Integration\FHIR;


use Satusehat\Integration\Terminology\ProcedureTerminology;
use Satusehat\Integration\Terminology\ServiceRequestTerminology;
use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

class ServiceRequest extends OAuth2Client
{
    public $service_request = [
        "resourceType" => "ServiceRequest",
    ];

    /**
     * Terminology
     */
    public $request_status;
    public $request_intent;

    public function __construct()
    {
        parent::__construct();

        $terminology = new ServiceRequestTerminology();

        $this->request_status = $terminology->service_request_status;
        $this->request_intent = $terminology->service_request_intent;
        
    }

    public function identifier($organization_ihs_number)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/servicerequest/' . $organization_ihs_number;
        $identifier['use'] = 'official';
        $identifier['value'] = $organization_ihs_number;

        $this->service_request['identifier'][] = $identifier;
    }

    public function setStatus($status)
    {
        $this->service_request['status'] = $status;
    }

    public function setIntent($intent)
    {
        $this->service_request['intent'] = $intent;
    }

    public function setCode($code, $description)
    {
        $service_request_code['coding'][] = $code;
        $this->service_request['code'] = $service_request_code;
        $this->service_request['code']['text'] = $description;
    }

    public function setSubject($patient_ihs_number)
    {
        $subject = [
            "reference" => "Patient/" . $patient_ihs_number,
        ];

        $this->service_request['subject'] = $subject;
    }

    public function setPerformer($practitioner_id, $name)
    {
        $performer = [
            "reference" => "Practitioner/" . $practitioner_id,
            "display" => $name,
        ];

        $this->service_request['performer'][] = $performer;
    }

    public function setRequester($practitioner_id, $name)
    {
        $requester = [
            "reference" => "Practitioner/" . $practitioner_id,
            "display" => $name,
        ];

        $this->service_request['requester'] = $requester;
    }

    public function setEncounter($id_resource_encounter, $display = null)
    {
        $encounter = [
            "reference" => "Encounter/" . $id_resource_encounter,
            "display" => $display ? $display : $id_resource_encounter,
        ];

        $this->service_request['encounter'] = $encounter;
    }

    public function setOccurrence($time)
    {
        $this->service_request['occurrenceDateTime'] = $time;
    }

    public function json()
    {
        if(!isset($this->service_request['status'])) return new FHIRException("Please call \$this->setStatus() to your ServiceRequest instance.");
        if(!isset($this->service_request['code'])) return new FHIRException("Please call \$this->setCode() to your ServiceRequest instance.");
        if(!isset($this->service_request['encounter'])) return new FHIRException("Please call \$this->setEncounter() to your ServiceRequest instance.");
        if(!isset($this->service_request['subject'])) return new FHIRException("Please call \$this->setSubject() to your ServiceRequest instance.");
        if(!isset($this->service_request['occurrenceDateTime'])) return new FHIRException("Please call \$this->setOccurrence() to your ServiceRequest instance.");
        if(!isset($this->service_request['performer'])) return new FHIRException("Please call \$this->setPerformer() to your ServiceRequest instance.");
        if(!isset($this->service_request['requester'])) return new FHIRException("Please call \$this->setRequester() to your ServiceRequest instance.");
        return json_encode($this->service_request, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post("ServiceRequest", $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->service_request['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put("ServiceRequest", $id, $payload);
        return [$statusCode, $res];
    }
}
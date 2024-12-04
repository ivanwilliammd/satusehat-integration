<?php

namespace Satusehat\Integration\Terminology;


class ServiceRequestTerminology
{
    /**
     * https://hl7.org/fhir/R4/valueset-request-status.html
     */
    public array $service_request_status = array(
        "draft" => "draft",
        "active" => "active",
        "on-hold" => "on-hold",
        "revoked" => "revoked",
        "completed" => "completed",
        "entered-in-error" => "entered-in-error",
        "unknown" => "unknown",
    );

    /**
     * https://hl7.org/fhir/R4/valueset-request-intent.html
     */
    public array $service_request_intent = array(
        'proposal' => 'proposal',
        'plan' => 'plan',
        'directive' => 'directive',
        'order' => 'order',
        'original-order' => 'original-order',
        'reflex-order' => 'reflex-order',
        'filler-order' => 'filler-order',
        'instance-order' => 'instance-order',
        'option' => 'option',
    );
    
}
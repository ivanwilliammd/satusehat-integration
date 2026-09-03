<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Terminology\Loinc;

class ServiceRequest extends OAuth2Client
{
    public array $serviceRequest = ['resourceType' => 'ServiceRequest'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->serviceRequest['identifier'][] = $identifier;
    }

    public function setRequisition($system, $value)
    {
        $this->serviceRequest['requisition'] = [
            'system' => $system,
            'value' => $value,
        ];
    }

    public function setStatus($status = 'active')
    {
        $validStatuses = ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->serviceRequest['status'] = $status;
    }

    public function setIntent($intent = 'original-order')
    {
        $validIntents = ['proposal', 'plan', 'directive', 'order', 'original-order', 'reflex-order', 'filler-order', 'instance-order', 'option'];
        if (! in_array($intent, $validIntents)) {
            throw new FHIRInvalidPropertyValue('Invalid intent');
        }
        $this->serviceRequest['intent'] = $intent;
    }

    public function addCategory($category)
    {
        $categories = [
            'laboratory' => ['code' => '108252007', 'display' => 'Laboratory procedure'],
            'radiology' => ['code' => '363679005', 'display' => 'Imaging'],
            'counselling' => ['code' => '409063005', 'display' => 'Counselling'],
            'education' => ['code' => '409073007', 'display' => 'Education'],
            'surgical' => ['code' => '387713003', 'display' => 'Surgical procedure'],
        ];

        if (! array_key_exists($category, $categories)) {
            throw new FHIRInvalidPropertyValue('Invalid category');
        }

        $this->serviceRequest['category'][] = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $categories[$category]['code'],
                    'display' => $categories[$category]['display'],
                ],
            ],
        ];
    }

    public function setPriority($priority)
    {
        $this->serviceRequest['priority'] = $priority;
    }

    public function setDoNotPerform($doNotPerform)
    {
        $this->serviceRequest['doNotPerform'] = $doNotPerform;
    }

    public function setCode($code = null, $display = null)
    {
        // Look in database if display is null
        $code_check = Loinc::where('LOINC_NUM', $code)->first();

        // Handling if incomplete code / display
        if (! $code_check) {
            throw new FHIRInvalidPropertyValue('Kode LOINC tidak ditemukan');
        }

        $display = $display ? $display : $code_check->LONG_COMMON_NAME;

        $this->serviceRequest['code']['coding'][] = [
            'system' => 'http://loinc.org',
            'code' => strtoupper($code),
            'display' => $display,
        ];
    }

    public function setQuantityQuantity($value, $unit, $system = 'http://unitsofmeasure.org')
    {
        $this->serviceRequest['quantityQuantity'] = [
            'value' => $value,
            'unit' => $unit,
            'system' => $system,
        ];
    }

    public function setSubject($reference, $display)
    {
        $this->serviceRequest['subject'] = [
            'reference' => $reference,
            'display' => $display,
        ];
    }

    public function setEncounter($reference, $display = null)
    {
        $this->serviceRequest['encounter'] = [
            'reference' => 'Encounter/' . $reference,
        ];

        if ($display !== null) {
            $this->serviceRequest['encounter']['display'] = $display;
        }
    }

    public function setOccurrenceDateTime($dateTime)
    {
        $this->serviceRequest['occurrenceDateTime'] = $dateTime;
    }

    public function setAuthoredOn($dateTime)
    {
        $this->serviceRequest['authoredOn'] = $dateTime;
    }

    public function setRequester($reference, $display)
    {
        $this->serviceRequest['requester'] = [
            'reference' => $reference,
            'display' => $display,
        ];
    }

    public function addPerformer($reference, $display)
    {
        $performer = [
            'reference' => $reference,
            'display' => $display,
        ];

        $this->serviceRequest['performer'][] = $performer;
    }

    public function addReasonCode($code = null, $display = null, $text = null)
    {
        $reasonCode = [];

        if ($code !== null && $display !== null) {
            $reasonCode['coding'] = [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $code,
                    'display' => $display,
                ],
            ];
        }

        if ($text !== null) {
            $reasonCode['text'] = $text;
        }

        $this->serviceRequest['reasonCode'][] = $reasonCode;
    }

    public function addSupportingInfo($reference)
    {
        $this->serviceRequest['supportingInfo'][] = [
            'reference' => $reference,
        ];
    }

    public function addSpecimen($reference)
    {
        $this->serviceRequest['specimen'][] = [
            'reference' => $reference,
        ];
    }

    public function addNote($text)
    {
        $this->serviceRequest['note'][] = [
            'text' => $text,
        ];
    }

    public function setPatientInstruction($instruction)
    {
        $this->serviceRequest['patientInstruction'] = $instruction;
    }

    public function addRelevantHistory($reference)
    {
        $this->serviceRequest['relevantHistory'][] = [
            'reference' => $reference,
        ];
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('status', $this->serviceRequest)) {
            $this->setStatus();
        }

        if (! array_key_exists('intent', $this->serviceRequest)) {
            $this->setIntent();
        }

        if (! array_key_exists('code', $this->serviceRequest)) {
            throw new FHIRMissingProperty('ServiceRequest.code is required');
        }

        if (! array_key_exists('subject', $this->serviceRequest)) {
            throw new FHIRMissingProperty('ServiceRequest.subject is required');
        }

        if (! array_key_exists('encounter', $this->serviceRequest)) {
            throw new FHIRMissingProperty('ServiceRequest.encounter is required');
        }

        if (! array_key_exists('occurrenceDateTime', $this->serviceRequest)) {
            throw new FHIRMissingProperty('ServiceRequest.occurrenceDateTime is required');
        }

        return json_encode($this->serviceRequest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('ServiceRequest', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->serviceRequest['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('ServiceRequest', $id, $payload);

        return [$statusCode, $res];
    }
}

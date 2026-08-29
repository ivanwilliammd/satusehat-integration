<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Procedure extends OAuth2Client
{
    public array $procedure = ['resourceType' => 'Procedure'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->procedure['identifier'][] = $identifier;
    }

    public function setStatus($status = 'completed')
    {
        $validStatuses = ['preparation', 'in-progress', 'not-done', 'on-hold', 'stopped', 'completed', 'entered-in-error', 'unknown'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->procedure['status'] = $status;
    }

    public function setCategory($code, $display, $system = 'http://snomed.info/sct', $text = null)
    {
        $this->procedure['category'] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
            'text' => $text,
        ];
    }

    public function setCode($code, $display, $system = 'http://hl7.org/fhir/sid/icd-9-cm')
    {
        $this->procedure['code']['coding'][] = [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ];
    }

    public function setSubject($reference, $display)
    {
        $this->procedure['subject'] = [
            'reference' => $reference,
            'display' => $display,
        ];
    }

    public function setEncounter($reference, $display = null)
    {
        $this->procedure['encounter'] = [
            'reference' => 'Encounter/' . $reference,
        ];

        if ($display !== null) {
            $this->procedure['encounter']['display'] = $display;
        }
    }

    public function setLocation($reference, $display = null)
    {
        $this->procedure['location'] = [
            'reference' => 'Location/'. $reference,
        ];

        if ($display !== null) {
            $this->procedure['location']['display'] = $display;
        }
    }

    public function addPerformer($reference, $display)
    {
        $performer = [
            'actor' => [
                'reference' => $reference,
                'display' => $display,
            ],
        ];

        $this->procedure['performer'][] = $performer;
    }

    public function setPerformedPeriod($start, $end)
    {
        $this->procedure['performedPeriod'] = [
            'start' => $start,
            'end' => $end,
        ];
    }

    public function addReasonCode($code, $display, $system = 'http://hl7.org/fhir/sid/icd-10')
    {
        $this->procedure['reasonCode'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addBodySite($code, $display, $system = 'http://snomed.info/sct')
    {
        $this->procedure['bodySite'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addNote($text)
    {
        $this->procedure['note'][] = [
            'text' => $text,
        ];
    }

    public function addUsedCode($code, $display, $system = 'http://sys-ids.kemkes.go.id/kfa')
    {
        $this->procedure['usedCode'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addBasedOn($reference)
    {
        $this->procedure['basedOn'][] = [
            'reference' => $reference,
        ];
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('status', $this->procedure)) {
            $this->setStatus();
        }

        if (! array_key_exists('subject', $this->procedure)) {
            throw new FHIRMissingProperty('Procedure.subject is required');
        }

        if (! array_key_exists('encounter', $this->procedure)) {
            throw new FHIRMissingProperty('Procedure.encounter is required');
        }

        if (! array_key_exists('performer', $this->procedure)) {
            throw new FHIRMissingProperty('Procedure.performer is required');
        }

        return json_encode($this->procedure, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Procedure', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->procedure['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Procedure', $id, $payload);

        return [$statusCode, $res];
    }
}

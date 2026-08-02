<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class CarePlan extends OAuth2Client
{
    public array $carePlan = ['resourceType' => 'CarePlan'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->carePlan['identifier'][] = $identifier;
    }

    public function setStatus($status = 'active')
    {
        $validStatuses = ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->carePlan['status'] = $status;
    }

    public function setIntent($intent = 'plan')
    {
        $validIntents = ['proposal', 'plan', 'order', 'option'];
        if (! in_array($intent, $validIntents)) {
            throw new FHIRInvalidPropertyValue('Invalid intent');
        }
        $this->carePlan['intent'] = $intent;
    }

    public function addCategory($code, $display, $system = 'http://snomed.info/sct')
    {
        $this->carePlan['category'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setTitle($title)
    {
        $this->carePlan['title'] = $title;
    }

    public function setDescription($description)
    {
        $this->carePlan['description'] = $description;
    }

    public function setSubject($reference, $display = null)
    {
        $this->carePlan['subject'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->carePlan['subject']['display'] = $display;
        }
    }

    public function setEncounter($reference)
    {
        $this->carePlan['encounter'] = [
            'reference' => $reference,
        ];
    }

    public function setPeriod($start, $end = null)
    {
        $this->carePlan['period'] = [
            'start' => $start,
        ];

        if ($end !== null) {
            $this->carePlan['period']['end'] = $end;
        }
    }

    public function setCreated($dateTime)
    {
        $this->carePlan['created'] = $dateTime;
    }

    public function setAuthor($reference)
    {
        $this->carePlan['author'] = [
            'reference' => $reference,
        ];
    }

    public function addContributor($reference)
    {
        $this->carePlan['contributor'][] = [
            'reference' => $reference,
        ];
    }

    public function addCareTeam($reference)
    {
        $this->carePlan['careTeam'][] = [
            'reference' => $reference,
        ];
    }

    public function addAddresses($reference)
    {
        $this->carePlan['addresses'][] = [
            'reference' => $reference,
        ];
    }

    public function addSupportingInfo($reference)
    {
        $this->carePlan['supportingInfo'][] = [
            'reference' => $reference,
        ];
    }

    public function addGoal($reference)
    {
        $this->carePlan['goal'][] = [
            'reference' => $reference,
        ];
    }

    public function addActivity($detail)
    {
        $this->carePlan['activity'][] = [
            'detail' => $detail,
        ];
    }

    public function addNote($text)
    {
        $this->carePlan['note'][] = [
            'text' => $text,
        ];
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('status', $this->carePlan)) {
            $this->setStatus();
        }

        if (! array_key_exists('intent', $this->carePlan)) {
            $this->setIntent();
        }

        if (! array_key_exists('subject', $this->carePlan)) {
            throw new FHIRMissingProperty('CarePlan.subject is required');
        }

        return json_encode($this->carePlan, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('CarePlan', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->carePlan['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('CarePlan', $id, $payload);

        return [$statusCode, $res];
    }
}

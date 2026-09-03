<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class GenomicStudy extends OAuth2Client
{
    public array $genomicStudy = ['resourceType' => 'GenomicStudy'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->genomicStudy['identifier'][] = $identifier;
    }

    public function setStatus($status)
    {
        $validStatuses = ['registered', 'available', 'cancelled', 'entered-in-error', 'unknown'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->genomicStudy['status'] = $status;
    }

    public function addType($system, $code, $display)
    {
        $this->genomicStudy['type'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setSubject($reference)
    {
        $this->genomicStudy['subject'] = [
            'reference' => $reference,
        ];
    }

    public function setEncounter($reference)
    {
        $this->genomicStudy['encounter'] = [
            'reference' => $reference,
        ];
    }

    public function setStartDate($date)
    {
        $this->genomicStudy['startDate'] = $date;
    }

    public function addBasedOn($reference)
    {
        $this->genomicStudy['basedOn'][] = [
            'reference' => $reference,
        ];
    }

    public function setReferrer($reference)
    {
        $this->genomicStudy['referrer'] = [
            'reference' => $reference,
        ];
    }

    public function addInterpreter($reference)
    {
        $this->genomicStudy['interpreter'][] = [
            'reference' => $reference,
        ];
    }

    public function addReason($system, $code, $display)
    {
        $this->genomicStudy['reason'][] = [
            'concept' => [
                'coding' => [
                    [
                        'system' => $system,
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
        ];
    }

    public function addNote($text)
    {
        $this->genomicStudy['note'][] = [
            'text' => $text,
        ];
    }

    public function setDescription($description)
    {
        $this->genomicStudy['description'] = $description;
    }

    public function addAnalysis($analysis)
    {
        $this->genomicStudy['analysis'][] = $analysis;
    }

    public function addPerformer($reference, $role)
    {
        $this->genomicStudy['performer'][] = [
            'actor' => [
                'reference' => $reference,
            ],
            'role' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/3.1.0/CodeSystem-v3-ParticipationType.html',
                        'code' => $role,
                        'display' => 'Performer',
                    ],
                ],
            ],
        ];
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('subject', $this->genomicStudy)) {
            throw new FHIRMissingProperty('GenomicStudy.subject is required');
        }

        if (! array_key_exists('encounter', $this->genomicStudy)) {
            throw new FHIRMissingProperty('GenomicStudy.encounter is required');
        }

        if (! array_key_exists('performer', $this->genomicStudy)) {
            throw new FHIRMissingProperty('GenomicStudy.performer is required');
        }

        return json_encode($this->genomicStudy, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}

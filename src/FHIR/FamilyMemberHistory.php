<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Terminology\FamilyRelationship;

class FamilyMemberHistory extends OAuth2Client
{
    private $terminology;

    public function __construct()
    {
        parent::__construct();
        $this->terminology = new FamilyRelationship;
    }

    public array $familyMemberHistory = ['resourceType' => 'FamilyMemberHistory'];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->familyMemberHistory['identifier'][] = $identifier;
    }

    public function setStatus($status = 'completed')
    {
        $validStatuses = ['partial', 'completed', 'entered-in-error', 'health-unknown'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->familyMemberHistory['status'] = $status;
    }

    public function setPatient($reference, $display = null)
    {
        $this->familyMemberHistory['patient'] = [
            'reference' => 'Patient/' . $reference,
        ];

        if ($display !== null) {
            $this->familyMemberHistory['patient']['display'] = $display;
        }
    }

    public function setRelationship($code)
    {
        if (! array_key_exists($code, $this->terminology->relationshipCodes)) {
            throw new FHIRInvalidPropertyValue('Invalid relationship code');
        }

        $this->familyMemberHistory['relationship'] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                    'code' => $code,
                    'display' => $this->terminology->relationshipCodes[$code],
                ],
            ],
        ];
    }

    public function setDate($dateTime)
    {
        $this->familyMemberHistory['date'] = $dateTime;
    }

    public function setDeceasedBoolean($deceased)
    {
        $this->familyMemberHistory['deceasedBoolean'] = $deceased;
    }

    public function addCondition($code, $display, $onsetString, $outcomeCode, $outcomeDisplay, $contributedToDeath = false)
    {
        $system = (strlen($code) < 6) ? 'http://hl7.org/fhir/sid/icd-10' : 'http://snomed.info/sct';
        $condition = [
            'code' => [
                'coding' => [
                    [
                        'system' => $system,
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
            'onsetString' => $onsetString,
            'contributedToDeath' => $contributedToDeath,
        ];

        // Add outcome field only if both outcomeCode and outcomeDisplay are provided
        if (!empty($outcomeCode) && !empty($outcomeDisplay)) {
            $condition['outcome'] = [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $outcomeCode,
                        'display' => $outcomeDisplay,
                    ],
                ],
            ];
        }

        $this->familyMemberHistory['condition'][] = $condition;
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('status', $this->familyMemberHistory)) {
            $this->setStatus();
        }

        if (! array_key_exists('patient', $this->familyMemberHistory)) {
            throw new FHIRMissingProperty('FamilyMemberHistory.patient is required');
        }

        if (! array_key_exists('relationship', $this->familyMemberHistory)) {
            throw new FHIRMissingProperty('FamilyMemberHistory.relationship is required');
        }

        return json_encode($this->familyMemberHistory, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('FamilyMemberHistory', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->familyMemberHistory['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('FamilyMemberHistory', $id, $payload);

        return [$statusCode, $res];
    }
}

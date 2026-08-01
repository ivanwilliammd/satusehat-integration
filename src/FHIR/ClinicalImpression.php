<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class ClinicalImpression extends OAuth2Client
{
    public $prognosis_codeable_concept;

    public function __construct()
    {
        parent::__construct();

        $this->prognosis_codeable_concept = [
            '170968001' => 'Prognosis good',
            '65872000' => 'Fair prognosis',
            '67334001' => 'Guarded prognosis',
            '170969009' => 'Prognosis bad',
        ];
    }

    public array $clinical_impression = [
        'resourceType' => 'ClinicalImpression',
    ];

    public function setStatus($status = 'completed')
    {
        // Assert if the status is in-progress | completed | entered-in-error
        $status = strtolower($status);
        if (! in_array($status, ['in-progress', 'completed', 'entered-in-error'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->clinical_impression['status'] = $status;
    }

    public function setSubject($subjectId, $name)
    {
        $this->clinical_impression['subject']['reference'] = 'Patient/'.$subjectId;
        $this->clinical_impression['subject']['display'] = $name;
    }

    public function setEncounter($encounterId, $display = null)
    {
        $this->clinical_impression['encounter']['reference'] = 'Encounter/'.$encounterId;
        $this->clinical_impression['encounter']['display'] = $display ? $display : 'Kunjungan '.$encounterId;
    }

    public function setEffectiveDateTime($effectiveDateTime = null)
    {
        $this->clinical_impression['effectiveDateTime'] = $effectiveDateTime ?
            date('Y-m-d\TH:i:sP', strtotime($effectiveDateTime)) :
            date('Y-m-d\TH:i:sP');
    }

    public function setDate($date = null)
    {
        $this->clinical_impression['date'] = $date ?
            date('Y-m-d\TH:i:sP', strtotime($date)) :
            date('Y-m-d\TH:i:sP');
    }

    public function setAssessor($assessorId)
    {
        $this->clinical_impression['assessor']['reference'] = 'Practitioner/'.$assessorId;
    }

    public function setDescription($description)
    {
        $this->clinical_impression['description'] = $description;
    }

    public function addIdentifier($system, $value, $use = 'official')
    {
        $this->clinical_impression['identifier'][] = [
            'system' => $system,
            'value' => $value,
            'use' => $use,
        ];
    }

    public function addProblem($reference)
    {
        $this->clinical_impression['problem'][] = [
            'reference' => 'Condition/'.$reference,
        ];
    }

    public function addFinding($code, $display, $reference)
    {
        $this->clinical_impression['finding'][] = [
            'itemCodeableConcept' => [
                'coding' => [
                    [
                        'system' => 'http://hl7.org/fhir/sid/icd-10',
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
            'itemReference' => [
                'reference' => 'Condition/'.$reference,
            ],
        ];
    }

    public function addInvestigation($text, $items)
    {
        $investigation = [
            'code' => [
                'text' => $text,
            ],
        ];

        foreach ($items as $item) {
            $investigation['item'][] = [
                'reference' => $item,
            ];
        }

        $this->clinical_impression['investigation'][] = $investigation;
    }

    public function addPrognosisCodeableConcept($code)
    {
        $this->clinical_impression['prognosisCodeableConcept'][] = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $code,
                    'display' => $this->prognosis_codeable_concept[$code],
                ],
            ],
        ];
    }

    public function setSummary($summary)
    {
        $this->clinical_impression['summary'] = $summary;
    }

    public function json()
    {
        // If status not declared, automatically call setStatus() with 'completed' as the default value
        if (! isset($this->clinical_impression['status'])) {
            $this->setStatus();
        }

        // If subject not declared, throw FHIRMissingProperty
        if (! isset($this->clinical_impression['subject'])) {
            throw new FHIRMissingProperty('Subject is required');
        }

        // If encounter not declared, throw FHIRMissingProperty
        if (! isset($this->clinical_impression['encounter'])) {
            throw new FHIRMissingProperty('Encounter is required');
        }

        // If prognosisCodeableConcept not declared, throw FHIRMissingProperty
        if (! isset($this->clinical_impression['prognosisCodeableConcept'])) {
            throw new FHIRMissingProperty('PrognosisCodeableConcept is required');
        }

        return json_encode($this->clinical_impression, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('ClinicalImpression', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->clinical_impression['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('ClinicalImpression', $id, $payload);

        return [$statusCode, $res];
    }
}

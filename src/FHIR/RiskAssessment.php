<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class RiskAssessment extends OAuth2Client
{
    public array $riskAssessment = ['resourceType' => 'RiskAssessment'];

    private array $risk_probability = [
        'negligible' => 'Negligible likelihood',
        'low' => 'Low likelihood',
        'moderate' => 'Moderate likelihood',
        'high' => 'High likelihood',
        'certain' => 'Certain',
    ];

    public function addIdentifier($system, $value)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        $this->riskAssessment['identifier'][] = $identifier;
    }

    public function setStatus($status = 'final')
    {
        $validStatuses = ['registered', 'preliminary', 'final', 'amended'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->riskAssessment['status'] = $status;
    }

    public function setCode($system, $code, $display)
    {
        $this->riskAssessment['code']['coding'][] = [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ];
    }

    public function setSubject($reference)
    {
        $this->riskAssessment['subject'] = [
            'reference' => $reference,
        ];
    }

    public function setEncounter($reference)
    {
        $this->riskAssessment['encounter'] = [
            'reference' => $reference,
        ];
    }

    public function setOccurrenceDateTime($dateTime)
    {
        $this->riskAssessment['occurrenceDateTime'] = $dateTime;
    }

    public function setCondition($reference)
    {
        $this->riskAssessment['condition'] = [
            'reference' => $reference,
        ];
    }

    public function setPerformer($reference)
    {
        $this->riskAssessment['performer'] = [
            'reference' => $reference,
        ];
    }

    public function addReasonReference($reference)
    {
        $this->riskAssessment['reasonReference'][] = [
            'reference' => $reference,
        ];
    }

    public function addBasis($reference)
    {
        $this->riskAssessment['basis'][] = [
            'reference' => $reference,
        ];
    }

    public function addPrediction($outcomeSystem, $outcomeCode, $outcomeDisplay, $probabilityDecimal, $qualitativeRisk=null, $relativeRisk=null)
    {
        $prediction = [
            'outcome' => [
                'coding' => [
                    [
                        'system' => $outcomeSystem,
                        'code' => $outcomeCode,
                        'display' => $outcomeDisplay,
                    ],
                ],
            ],
            'probabilityDecimal' => $probabilityDecimal,
        ];

        if ($qualitativeRisk !== null) {
            $prediction['qualitativeRisk'] = [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/risk-probability',
                        'code' => $qualitativeRisk,
                        'display' => $this->risk_probability[$qualitativeRisk],
                    ],
                ],
            ];
        }

        if ($relativeRisk !== null) {
            $prediction['relativeRisk'] = $relativeRisk;
        }

        $this->riskAssessment['prediction'][] = $prediction;
    }

    public function setMitigation($mitigation)
    {
        $this->riskAssessment['mitigation'] = $mitigation;
    }

    public function addNote($text)
    {
        $this->riskAssessment['note'][] = [
            'text' => $text,
        ];
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('status', $this->riskAssessment)) {
            $this->setStatus();
        }

        if (! array_key_exists('subject', $this->riskAssessment)) {
            throw new FHIRMissingProperty('RiskAssessment.subject is required');
        }

        return json_encode($this->riskAssessment, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}

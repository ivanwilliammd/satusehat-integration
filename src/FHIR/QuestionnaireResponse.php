<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\OAuth2Client;

class QuestionnaireResponse extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public array $questionnaire_response = [
        'resourceType' => 'QuestionnaireResponse',
    ];

    public function setStatus($status = 'completed')
    {
        $status = strtolower($status);
        if (! in_array($status, ['in-progress', 'completed', 'amended', 'entered-in-error', 'stopped'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->questionnaire_response['status'] = $status;
    }

    public function setQuestionnaire($questionnaire)
    {
        $this->questionnaire_response['questionnaire'] = $questionnaire;
    }

    public function setSubject($reference, $display = null)
    {
        $this->questionnaire_response['subject'] = [
            'reference' => $reference,
            'display' => $display,
        ];
    }

    public function setEncounter($reference)
    {
        $this->questionnaire_response['encounter'] = [
            'reference' => $reference,
        ];
    }

    public function setAuthored($dateTime)
    {
        $this->questionnaire_response['authored'] = $dateTime;
    }

    public function setAuthor($reference)
    {
        $this->questionnaire_response['author'] = [
            'reference' => $reference,
        ];
    }

    public function setSource($reference)
    {
        $this->questionnaire_response['source'] = [
            'reference' => $reference,
        ];
    }

    public function addItem($linkId, $text, $valueCodingSystem, $valueCodingCode, $valueCodingDisplay)
    {
        $this->questionnaire_response['item'][] = [
            'linkId' => $linkId,
            'text' => $text,
            'answer' => [
                [
                    'valueCoding' => [
                        'system' => $valueCodingSystem,
                        'code' => $valueCodingCode,
                        'display' => $valueCodingDisplay,
                    ],
                ],
            ],
        ];
    }

    public function setId($id)
    {
        $this->questionnaire_response['id'] = $id;
    }

    public function json()
    {
        if (! isset($this->questionnaire_response['status'])) {
            $this->setStatus();
        }

        return json_encode($this->questionnaire_response, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('QuestionnaireResponse', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->questionnaire_response['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('QuestionnaireResponse', $id, $payload);

        return [$statusCode, $res];
    }
}

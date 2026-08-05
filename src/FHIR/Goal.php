<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Goal extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public array $goal = [
        'resourceType' => 'Goal',
    ];

    public function setLifecycleStatus($status = 'completed')
    {
        $status = strtolower($status);
        if (! in_array($status, ['proposed', 'planned', 'accepted', 'active', 'on-hold', 'completed', 'cancelled', 'entered-in-error', 'rejected'])) {
            throw new FHIRInvalidPropertyValue('Invalid lifecycleStatus value');
        }
        $this->goal['lifecycleStatus'] = $status;
    }

    public function setDescription($text)
    {
        $this->goal['description'] = [
            'text' => $text,
        ];
    }

    public function setSubject($reference)
    {
        $this->goal['subject'] = [
            'reference' => $reference,
        ];
    }

    public function addCategory($system, $code, $display)
    {
        $this->goal['category'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setStatusDate($date)
    {
        $this->goal['statusDate'] = $date;
    }

    public function setExpressedBy($reference)
    {
        $this->goal['expressedBy'] = [
            'reference' => $reference,
        ];
    }

    public function addAddress($reference)
    {
        $this->goal['addresses'][] = [
            'reference' => $reference,
        ];
    }

    public function addTarget($measureSystem, $measureCode, $measureDisplay, $detailSystem, $detailCode, $detailDisplay, $dueDate)
    {
        $this->goal['target'][] = [
            'measure' => [
                'coding' => [
                    [
                        'system' => $measureSystem,
                        'code' => $measureCode,
                        'display' => $measureDisplay,
                    ],
                ],
            ],
            'detailCodeableConcept' => [
                'coding' => [
                    [
                        'system' => $detailSystem,
                        'code' => $detailCode,
                        'display' => $detailDisplay,
                    ],
                ],
            ],
            'dueDate' => $dueDate,
        ];
    }

    public function setId($id)
    {
        $this->goal['id'] = $id;
    }

    public function json()
    {
        if (! isset($this->goal['lifecycleStatus'])) {
            $this->setLifecycleStatus();
        }

        if (! isset($this->goal['subject'])) {
            throw new FHIRMissingProperty('Subject is required');
        }

        if (! isset($this->goal['description'])) {
            throw new FHIRMissingProperty('Description is required');
        }

        return json_encode($this->goal, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Goal', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->goal['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Goal', $id, $payload);

        return [$statusCode, $res];
    }
}

<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class NutritionOrder extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public array $nutrition_order = [
        'resourceType' => 'NutritionOrder',
    ];

    public function setStatus($status = 'completed')
    {
        $status = strtolower($status);
        if (! in_array($status, ['draft', 'active', 'on-hold', 'revoked', 'completed', 'entered-in-error', 'unknown'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->nutrition_order['status'] = $status;
    }

    public function setIntent($intent = 'order')
    {
        $this->nutrition_order['intent'] = $intent;
    }

    public function setDateTime($dateTime)
    {
        $this->nutrition_order['dateTime'] = $dateTime;
    }

    public function setPatient($reference)
    {
        $this->nutrition_order['patient'] = [
            'reference' => $reference,
        ];
    }

    public function setEncounter($reference)
    {
        $this->nutrition_order['encounter'] = [
            'reference' => $reference,
        ];
    }

    public function setOrderer($reference)
    {
        $this->nutrition_order['orderer'] = [
            'reference' => $reference,
        ];
    }

    public function addExcludeFoodModifier($system, $code, $display)
    {
        $this->nutrition_order['excludeFoodModifier'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setOralDietType($system, $code, $display)
    {
        $this->nutrition_order['oralDiet']['type'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addOralDietNutrient($system, $code, $display, $value, $unit)
    {
        $this->nutrition_order['oralDiet']['nutrient'][] = [
            'modifier' => [
                'coding' => [
                    [
                        'system' => $system,
                        'code' => $code,
                        'display' => $display,
                    ],
                ],
            ],
            'amount' => [
                'value' => $value,
                'unit' => $unit,
                'system' => 'http://unitsofmeasure.org',
                'code' => $unit,
            ],
        ];
    }

    public function setId($id)
    {
        $this->nutrition_order['id'] = $id;
    }

    public function json()
    {
        if (! isset($this->nutrition_order['status'])) {
            $this->setStatus();
        }

        if (! isset($this->nutrition_order['intent'])) {
            $this->setIntent();
        }

        if (! isset($this->nutrition_order['patient'])) {
            throw new FHIRMissingProperty('Patient is required');
        }

        if (! isset($this->nutrition_order['dateTime'])) {
            throw new FHIRMissingProperty('DateTime is required');
        }

        return json_encode($this->nutrition_order, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('NutritionOrder', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->nutrition_order['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('NutritionOrder', $id, $payload);

        return [$statusCode, $res];
    }
}

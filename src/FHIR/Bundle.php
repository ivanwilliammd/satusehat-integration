<?php

namespace Satusehat\Integration\FHIR;

use Ramsey\Uuid\Uuid;
use Satusehat\Integration\OAuth2Client;

class Bundle extends OAuth2Client {
    public array $bundle = [
        'resourceType' => 'Bundle',
        'type' => 'transaction',
        'entry' => []
    ];

    public $encounter_id;

    public function addEncounter(Encounter $encounter){
        $this->encounter_id = Uuid::uuid4();
        $encounter_bundle = [
            'fullUrl' => 'urn:uuid:' . $this->encounter_id,
            'resource' => json_decode($encounter->json()),
            'request' => [
                'method' => 'POST',
                'url' => 'Encounter',
            ]
        ];

        $this->bundle['entry'][] = $encounter_bundle;
    }

    public function addCondition(Condition $condition){
        $condition->setEncounter($this->encounter_id);
        $condition_bundle = [
            'fullUrl' => 'urn:uuid:' . Uuid::uuid4(),
            'resource' => json_decode($condition->json()),
            'request' => [
                'method' => 'POST',
                'url' => 'Condition'
            ],
        ];
        $this->bundle['entry'][] = $condition_bundle;
    }

    public function post()
    {
        $payload = $this->bundle;
        [$statusCode, $res] = $this->ss_post('Bundle', $payload);

        return [$statusCode, $res];
    }
}
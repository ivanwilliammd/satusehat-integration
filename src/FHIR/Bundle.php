<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Bundle extends OAuth2Client {

    public array $bundle = [
        'resourceType' => 'Bundle',
        'type' => 'transaction',
        'entry' => []
    ];

    public $encounter_id;

    private function uuidV4() {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $uuid = bin2hex($data);
        $formatted_uuid = sprintf('%s-%s-%s-%s-%s',
            substr($uuid, 0, 8),
            substr($uuid, 8, 4),
            substr($uuid, 12, 4),
            substr($uuid, 16, 4),
            substr($uuid, 20, 12)
        );
    
        return $formatted_uuid;
    }

    public function addEncounter(Encounter $encounter){
        $this->encounter_id = $this->uuidV4();
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
            'fullUrl' => 'urn:uuid:' . $this->uuidV4(),
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

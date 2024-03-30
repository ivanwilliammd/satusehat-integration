<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\FHIR\Exception\FHIRException;

class Bundle extends OAuth2Client
{

    public array $bundle = [
        'resourceType' => 'Bundle',
        'type' => 'transaction',
        'entry' => [],
    ];

    public $encounter_id, $encounter;

    private function uuidV4()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $uuid = bin2hex($data);
        $formatted_uuid = sprintf(
            '%s-%s-%s-%s-%s',
            substr($uuid, 0, 8),
            substr($uuid, 8, 4),
            substr($uuid, 12, 4),
            substr($uuid, 16, 4),
            substr($uuid, 20, 12)
        );

        return $formatted_uuid;
    }

    public function addEncounter(Encounter $encounter)
    {
        $this->encounter_id = $this->uuidV4();
        $this->encounter = $encounter;
    }

    public function addCondition(Condition $condition)
    {
        
        if (!isset($this->encounter_id)) {
            throw new FHIRException("Please call addEncounter method first before addCondition.");
        }

        $condition_uuid = $this->uuidV4();

        // Membuat referensi condition ke encounter saat ini
        $condition->setEncounter($this->encounter_id, '', true);
        
        // Membuat referensi condition di encounter
        $this->encounter->addDiagnosis($condition_uuid, $condition->condition['code']['coding'][0]['code'], '', true);

        if(!isset($this->bundle['entry'][0])){
            $this->bundle['entry'][0] = [
                'fullUrl' => 'urn:uuid:' . $this->encounter_id,
                'resource' => '',
                'request' => [
                    'method' => 'POST',
                    'url' => 'Encounter'
                ]
            ];
        }

        $this->bundle['entry'][0]['resource'] = json_decode($this->encounter->json());
        
        $this->bundle['entry'][] = [
            'fullUrl' => 'urn:uuid:' . $condition_uuid,
            'resource' => json_decode($condition->json()),
            'request' => [
                'method' => 'POST',
                'url' => 'Condition',
            ]
        ];

    }

    public function post()
    {
        $payload = $this->bundle;
        [$statusCode, $res] = $this->ss_post('Bundle', $payload);

        return [$statusCode, $res];
    }
}

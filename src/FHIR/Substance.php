<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\OAuth2Client;

class Substance extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public array $substance = [
        'resourceType' => 'Substance',
    ];

    public function setStatus($status = 'active')
    {
        $status = strtolower($status);
        if (! in_array($status, ['active', 'inactive', 'entered-in-error'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->substance['status'] = $status;
    }

    public function setCode($system, $code, $display)
    {
        $this->substance['code'] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setId($id)
    {
        $this->substance['id'] = $id;
    }

    public function setText($status, $div)
    {
        $this->substance['text'] = [
            'status' => $status,
            'div' => $div,
        ];
    }

    public function json()
    {
        if (! isset($this->substance['status'])) {
            $this->setStatus();
        }

        return json_encode($this->substance, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Substance', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->substance['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Substance', $id, $payload);

        return [$statusCode, $res];
    }
}

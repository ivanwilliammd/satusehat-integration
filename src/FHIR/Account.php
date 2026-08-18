<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\OAuth2Client;

class Account extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();
    }

    public array $account = [
        'resourceType' => 'Account',
    ];

    public function setStatus($status = 'active')
    {
        $status = strtolower($status);
        if (! in_array($status, ['active', 'inactive', 'entered-in-error', 'on-hold', 'unknown'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->account['status'] = $status;
    }

    public function setType($system, $code, $display, $text)
    {
        $this->account['type'] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
            'text' => $text,
        ];
    }

    public function setName($name)
    {
        $this->account['name'] = $name;
    }

    public function addSubject($reference, $display = null)
    {
        $this->account['subject'][] = [
            'reference' => $reference,
            'display' => $display,
        ];
    }

    public function setServicePeriod($start, $end)
    {
        $this->account['servicePeriod'] = [
            'start' => $start,
            'end' => $end,
        ];
    }

    public function addCoverage($reference, $priority)
    {
        $this->account['coverage'][] = [
            'coverage' => [
                'reference' => $reference,
            ],
            'priority' => $priority,
        ];
    }

    public function setOwner($reference)
    {
        $this->account['owner'] = [
            'reference' => $reference,
        ];
    }

    public function setDescription($description)
    {
        $this->account['description'] = $description;
    }

    public function setId($id)
    {
        $this->account['id'] = $id;
    }

    public function json()
    {
        if (! isset($this->account['status'])) {
            $this->setStatus();
        }

        return json_encode($this->account, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Account', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->account['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Account', $id, $payload);

        return [$statusCode, $res];
    }
}

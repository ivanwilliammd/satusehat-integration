<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Organization extends OAuth2Client
{
    public $organization = [
        'resourceType' => 'Organization',
        'active' => true,
        'type' => [
            'coding' => [
                [
                    'system' => 'http://hl7.org/fhir/organization-type',
                    'code' => 'dept',
                    'display' => 'Hospital Department',
                ],
            ],
        ],
    ];

    public function addOrganizationIdentifier($organization_identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/organization'.$this->organization_id;
        $identifier['value'] = $organization_identifier;
        $identifier['use'] = 'official';

        $this->organization['identifier'][] = $identifier;
    }

    public function setOrganizationName($organization_name)
    {
        $this->organization['name'] = $organization_name;
    }

    public function setOrganizationPartOf($partOf)
    {
        $this->organization['partOf']['reference'] = 'Organization/'.$partOf ? $partOf : $this->organization_id;
    }

    public function addPhone($phone_number)
    {
        $this->organization['telecom'][] = [
            'system' => 'phone',
            'value' => $phone_number ? $phone_number : getenv('PHONE'),
            'use' => 'work'
        ];
    }

    public function addEmail($email)
    {
        $this->organization['telecom'][] = [
            'system' => 'email',
            'value' => $email ? $email : getenv('EMAIL'),
            'use' => 'work'
        ];
    }

    public function addUrl($url)
    {
        $this->organization['telecom'][] = [
            'system' => 'url',
            'value' => $url ? $url : getenv('WEBSITE'),
            'use' => 'work'
        ];
    }

    public function addAddress($address)
    {
        $this->organization['address'][] = [
            'line' => $address['line'],
            'city' => $address['city'],
            'district' => $address['district'],
            'state' => $address['state'],
            'postalCode' => $address['postalCode'],
            'country' => $address['country'],
        ];
    }

}

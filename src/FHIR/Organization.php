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

    public function addAddress()
    {
        $this->organization['address'][] = [
            'use' => 'work',
            'type' => 'both',
            'line' => [
                getenv('ALAMAT'),
            ],
            'city' => getenv('KOTA'),
            'postalCode' => getenv('KODEPOS'),
            'country' => 'ID',
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                    'extension' => [
                        [
                            'url' => 'province',
                            'valueCode' => getenv('KODE_PROVINSI'),
                        ],
                        [
                            'url' => 'city',
                            'valueCode' => getenv('KODE_KABUPATEN'),
                        ],
                        [
                            'url' => 'district',
                            'valueCode' => getenv('KODE_KECAMATAN'),
                        ],
                        [
                            'url' => 'village',
                            'valueCode' => getenv('KODE_KELURAHAN'),
                        ],
                    ],
                ],
            ],
        ];
    }

    public function json()
    {
        $this->addPhone();
        $this->addEmail();
        $this->addUrl();
        $this->addAddress();
        $this->setOrganizationPartOf();
        return $this->organization;
    }
}

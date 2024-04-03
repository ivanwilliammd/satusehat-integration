<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\FHIR\Exception\FHIRException;
use Satusehat\Integration\OAuth2Client;

class Organization extends OAuth2Client
{
    private array $orgType = [
        ['code' => 'dept', 'display' => 'Hospital Department'],
        ['code' => 'prov', 'display' => 'Healthcare Provider'],
    ];

    public array $organization = [
        'resourceType' => 'Organization',
        'active' => true,
    ];

    public function addIdentifier($organization_identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/organization/'.$this->organization_id;
        $identifier['value'] = $organization_identifier;
        $identifier['use'] = 'official';

        $this->organization['identifier'][] = $identifier;
    }

    public function setName($organization_name)
    {
        $this->organization['name'] = $organization_name;
    }

    public function setOperationalStatus($operational_status = null)
    {
        $this->organization['extension'][] = [
            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/operationalStatus',
            'valueCodeableConcept' => [
                'coding' => [
                    [
                        'system' => 'https://fhir.kemkes.go.id/r4/CodeSystem/operational-status',
                        'code' => $operational_status ? $operational_status : getenv('OPERATIONAL_STATUS', 'active'),
                    ],
                ],
            ],
        ];
    }

    public function setPartOf($partOf = null)
    {
        $this->organization['partOf']['reference'] = 'Organization/'.($partOf ? $partOf : $this->organization_id);
    }

    public function setType($type = 'dept')
    {
        if (! in_array($type, ['dept', 'prov'])) {
            throw new FHIRException("Types of organizations currently supported : 'prov' | 'dept' ");
        }

        $organizationTypeIndex = array_search('prov', array_column($this->orgType, 'code'));

        $display = $this->orgType[$organizationTypeIndex]['display'];

        $this->organization['type'] = [
            [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                        'code' => $type,
                        'display' => $display,
                    ],
                ],
            ],
        ];
    }

    public function addPhone($phone_number = null)
    {
        $this->organization['telecom'][] = [
            'system' => 'phone',
            'value' => $phone_number ? $phone_number : getenv('PHONE'),
            'use' => 'work',
        ];
    }

    public function addEmail($email = null)
    {
        $this->organization['telecom'][] = [
            'system' => 'email',
            'value' => $email ? $email : getenv('EMAIL'),
            'use' => 'work',
        ];
    }

    public function addUrl($url = null)
    {
        $this->organization['telecom'][] = [
            'system' => 'url',
            'value' => $url ? $url : getenv('WEBSITE'),
            'use' => 'work',
        ];
    }

    public function addAddress($address_line = null, $postal_code = null, $city_name = null, $village_code = null)
    {
        $this->organization['address'][] = [
            'use' => 'work',
            'type' => 'both',
            'line' => [
                $address_line ?? getenv('ALAMAT', ''),
            ],
            'city' => $city_name ?? getenv('KOTA', ''),
            'postalCode' => $postal_code ?? getenv('KODEPOS', ''),
            'country' => 'ID',
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                    'extension' => [
                        [
                            'url' => 'province',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 2) : getenv('KODE_PROVINSI', ''),
                        ],
                        [
                            'url' => 'city',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 4) : getenv('KODE_KABUPATEN', ''),
                        ],
                        [
                            'url' => 'district',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 6) : getenv('KODE_KECAMATAN', ''),
                        ],
                        [
                            'url' => 'village',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 10) : getenv('KODE_KELURAHAN', ''),
                        ],
                    ],
                ],
            ],
        ];
    }

    public function json()
    {
        // Add Organization type
        $this->organization['type'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                    'code' => 'dept',
                    'display' => 'Hospital Department',
                ],
            ],
        ];

        // Identifier is required
        if (! array_key_exists('identifier', $this->organization)) {
            return 'Please use organization->addIdentifier($organization_identifier) to pass the data';
        }

        // Name is required
        if (! array_key_exists('name', $this->organization)) {
            return 'Please use organization->setName($organization_name) to pass the data';
        }

        // Set default Organization part.Of
        if (! array_key_exists('partOf', $this->organization)) {
            $this->setPartOf();
        }

        // Set default Organization type
        if (! array_key_exists('type', $this->organization)) {
            $this->setType($this->organization_type);
        }

        return json_encode($this->organization, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Organization', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->organization['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Organization', $id, $payload);

        return [$statusCode, $res];
    }
}

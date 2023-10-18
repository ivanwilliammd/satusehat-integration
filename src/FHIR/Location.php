<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Location extends OAuth2Client
{
    public $location = [
        'resourceType' => 'Location',
        'status' => 'active',
        'mode' => 'instance',
    ];

    public function addIdentifier($location_identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/location/'.$this->location_id;
        $identifier['value'] = $location_identifier;

        $this->location['identifier'][] = $identifier;
    }

    public function setName($location_name)
    {
        $this->location['name'] = $location_name;
        $this->location['description'] = $location_name;
    }

    public function addPhone($phone_number = null)
    {
        $this->location['telecom'][] = [
            'system' => 'phone',
            'value' => $phone_number ? $phone_number : getenv('PHONE'),
            'use' => 'work',
        ];
    }

    public function addEmail($email = null)
    {
        $this->location['telecom'][] = [
            'system' => 'email',
            'value' => $email ? $email : getenv('EMAIL'),
            'use' => 'work',
        ];
    }

    public function addUrl($url = null)
    {
        $this->location['telecom'][] = [
            'system' => 'url',
            'value' => $url ? $url : getenv('WEBSITE'),
            'use' => 'work',
        ];
    }

    public function addAddress()
    {
        $this->location['address'][] = [
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

    public function addPhysicalType($physical_type = null)
    {
        $code = $physical_type ? $physical_type : 'ro';
        $display = [
            'bu' => 'Building',
            'wi' => 'Wing',
            'co' => 'Corridor',
            'ro' => 'Room',
            've' => 'Vehicle',
            'ho' => 'House',
            'ca' => 'Cabinet',
            'rd' => 'Road',
            'area' => 'Area',
        ];

        $this->location['physicalType']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
            'code' => $code,
            'display' => $display[$code],
        ];
    }

    public function addPosition($latitude = null, $longitude = null)
    {
        $this->location['position'] = [
            'latitude' => $latitude ? $latitude : getenv('LATITUDE'),
            'longitude' => $longitude ? $longitude : getenv('LONGITUDE'),
        ];
    }

    public function setManagingOrganization($managing_organization = null)
    {
        $this->location['managingOrganization']['reference'] = 'Organization/'.$managing_organization ? $managing_organization : $this->organization_id;
    }

    public function json()
    {
        $this->addPhone();
        $this->addEmail();
        $this->addUrl();
        $this->addAddress();

        // Add physicalType if not exist
        if (array_key_exists('physicalType', $this->location)) {
            $this->addPhysicalType();
        }

        // Add latitude & longitude if not exist
        if (array_key_exists('position', $this->location)) {
            $this->addPosition();
        }

        // Add default managing organization from parent (registered sarana)
        if (array_key_exists('managingOrganization', $this->location)) {
            $this->setManagingOrganization();
        }

        return $this->location;
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Location', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Location', $id, $payload);

        return [$statusCode, $res];
    }
}

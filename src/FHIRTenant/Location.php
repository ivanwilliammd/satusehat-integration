<?php

namespace Satusehat\Integration\FHIRTenant;

use Satusehat\Integration\OAuth2ClientTenant;

class Location extends OAuth2ClientTenant
{
    public $location = [
        'resourceType' => 'Location',
        'status' => 'active',
        'mode' => 'instance',
    ];

    public function addIdentifier($location_identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/location/' . $this->profile->organization_id;
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
            'value' => $phone_number ? $phone_number : $this->profile->phone,
            'use' => 'work',
        ];
    }

    public function addEmail($email = null)
    {
        $this->location['telecom'][] = [
            'system' => 'email',
            'value' => $email ? $email : $this->profile->email,
            'use' => 'work',
        ];
    }

    public function addUrl($url = null)
    {
        $this->location['telecom'][] = [
            'system' => 'url',
            'value' => $url ? $url : $this->profile->website,
            'use' => 'work',
        ];
    }

    public function addAddress()
    {
        $this->location['address'] = [
            'use' => 'work',
            'type' => 'both',
            'line' => [
                $this->profile->alamat,
            ],
            'city' => $this->profile->kota,
            'postalCode' => $this->profile->kodepos,
            'country' => 'ID',
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                    'extension' => [
                        [
                            'url' => 'province',
                            'valueCode' => $this->profile->kode_provinsi,
                        ],
                        [
                            'url' => 'city',
                            'valueCode' => $this->profile->kode_kabupaten,
                        ],
                        [
                            'url' => 'district',
                            'valueCode' => $this->profile->kode_kecamatan,
                        ],
                        [
                            'url' => 'village',
                            'valueCode' => $this->profile->kode_kelurahan,
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
            'latitude' => doubleval($latitude ? $latitude : $this->profile->lat),
            'longitude' => doubleval($longitude ? $longitude : $this->profile->long),
        ];
    }

    public function setManagingOrganization($managing_organization = null)
    {
        $this->location['managingOrganization']['reference'] = "Organization/" . ($managing_organization ? $managing_organization : $this->profile->organization_id);
    }

    public function json()
    {
        $this->addPhone();
        $this->addEmail();
        $this->addUrl();
        $this->addAddress();

        // Add physicalType if not exist
        if (!array_key_exists('physicalType', $this->location)) {
            $this->addPhysicalType();
        }

        // Add latitude & longitude if not exist
        if (!array_key_exists('position', $this->location)) {
            $this->addPosition();
        }

        // Add default managing organization from parent (registered sarana)
        if (!array_key_exists('managingOrganization', $this->location)) {
            $this->setManagingOrganization();
        }

        // Name is required
        if (!array_key_exists('name', $this->location)) {
            return 'Please use location->setName($location_name) to pass the data';
        }

        // Identifier is required
        if (!array_key_exists('identifier', $this->location)) {
            return 'Please use location->addIdentifier($location_identifier) to pass the data';
        }

        return json_encode($this->location, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = json_decode($this->json());
        [$statusCode, $res] = $this->ss_post('Location', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = json_decode($this->json());
        [$statusCode, $res] = $this->ss_put('Location', $id, $payload);

        return [$statusCode, $res];
    }
}

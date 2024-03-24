<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Location extends OAuth2Client
{
    public array $location = [
        'resourceType' => 'Location',
        'status' => 'active',
        'mode' => 'instance',
    ];

    public function addIdentifier($location_identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/location/' . $this->organization_id;
        $identifier['value'] = $location_identifier;

        $this->location['identifier'][] = $identifier;
    }

    public function setName($location_name)
    {
        $this->location['name'] = $location_name;
        $this->location['description'] = $location_name;
    }

    public function setStatus($status = 'active')
    {
        $this->location['status'] = $status ?? 'active';
    }

    public function setOperationalStatus($operational_status = 'U')
    {
        $operational_status = $operational_status ?? 'U';

        $display = [
            'U' => 'Unoccupied',
            'O' => 'Occupied',
            'C' => 'Closed',
            'H' => 'Housekeeping',
            'I' => 'Isolated',
            'K' => 'Contaminated',
        ];

        $this->location['operationalStatus'] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/v2-0116',
            'code' => $operational_status,
            'display' => $display[$operational_status],
        ];
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

    public function setAddress($address_line = null, $postal_code = null, $city_name = null, $village_code = null)
    {
        $this->location['address'] = [
            'use' => 'work',
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
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 4) :getenv('KODE_KABUPATEN', ''),
                        ],
                        [
                            'url' => 'district',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 6) : getenv('KODE_KECAMATAN', ''),
                        ],
                        [
                            'url' => 'village',
                            'valueCode' => $village_code ? substr(str_replace('.', '', $village_code), 0, 8) : getenv('KODE_KELURAHAN', ''),
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
        $this->location['managingOrganization']['reference'] = 'Organization/' . $managing_organization;
    }

    public function setPartOf($part_of = null)
    {
        $this->location['partOf']['reference'] = 'Location/'.$part_of;
    }

    public function json()
    {
        // Add physicalType if not exist
        if (!array_key_exists('physicalType', $this->location)) {
            $this->addPhysicalType();
        }

        // // Add latitude & longitude if not exist
        // if (!array_key_exists('position', $this->location)) {
        //     $this->addPosition();
        // }

        // Add default managing organization from parent (registered sarana)
        if (!array_key_exists('managingOrganization', $this->location)) {
            $this->setManagingOrganization();
        }

        // Name is required
        if (!array_key_exists('name', $this->location)) {
            return 'Please use location->setName($location_name) to pass the data';
        }

        // Identifier is required
        if (! array_key_exists('identifier', $this->location)) {
            return 'Please use location->addIdentifier($location_identifier) to pass the data';
        }

        return json_encode($this->location, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Location', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->location['id'] = $id;
        $payload = $this->json();
        // dd($payload);
        [$statusCode, $res] = $this->ss_put('Location', $id, $payload);

        return [$statusCode, $res];
    }
}

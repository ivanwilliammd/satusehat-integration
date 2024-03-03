<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\OAuth2Client;

class Patient extends OAuth2Client
{

    public $patient = [
        'resourceType' => 'Patient',
        'meta' => [
            'profile' => [
                'https://fhir.kemkes.go.id/r4/StructureDefinition/Patient'
            ],
        ],
        'active' => true,
    ];

    public function addIdentifier($identifier_type, $identifier_value){
        $identifier['use'] = 'official';
        $identifier['system'] = 'https://fhir.kemkes.go.id/id/' . $identifier_type;
        $identifier['value'] = $identifier_value;
        
        $this->patient['identifier'][] = $identifier;

    }

    public function setName($patient_name){
        $name['use'] = 'official';
        $name['text'] = $patient_name;

        $this->patient['name'][] = $name;
    }

    public function addTelecom($telecom_system, $telecom_value, $telecom_use){

        $telecom['system'] = $telecom_system; // https://www.hl7.org/fhir/valueset-contact-point-system.html
        $telecom['value'] = $telecom_value;
        $telecom['use'] = $telecom_use; // https://www.hl7.org/fhir/valueset-contact-point-use.html

        $this->patient['telecom'][] = $telecom;

    }

    public function setGender($gender){
        $this->patient['gender'] = $gender;
    }

    public function setBirthDate($date) {
        // YYYY-MM-DD
        $this->patient['birthDate'] = $date; 
    }

    public function setDeceased($bool){
        $this->patient['deceasedBoolean'] = $bool;
    }

    public function setAddress($address_detail){
        
        $address = [
            'use' => 'home',
            'line' => [
                $address_detail['address']
            ],
            'city' => $address_detail['city'],
            'postalCode' => $address_detail['postalCode'],
            'country' => $address_detail['country'],
            'extension' => [
                [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                'extension' => [
                    [
                        'url' => 'province',
                        'valueCode' => $address_detail['provinceCode'],
                    ],
                    [
                        'url' => 'city',
                        'valueCode' => $address_detail['cityCode'],
                    ],
                    [
                        'url' => 'district',
                        'valueCode' => $address_detail['districtCode'],
                    ],
                    [
                        'url' => 'village',
                        'valueCode' => $address_detail['villageCode'],
                    ],
                    [
                        'url' => 'rt',
                        'valueCode' => $address_detail['rt'],
                    ],
                    [
                        'url' => 'rw',
                        'valueCode' => $address_detail['rw'],
                    ]
                ]
                ]
            ]
        ];

        $this->patient['address'][] = $address;
    }

    public function setMaritalStatus($marital_code, $marital_display){
        $marital['coding'] = [
            [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                'code' => $marital_code,
                'display' => $marital_display,
            ]
        ];

        $marital['text'] = $marital_display;

        $this->patient['maritalStatus'] = $marital;

    }

    public function setMultipleBirth($value){
        $this->patient['multipleBirthInteger'] = $value;
    }

    public function setEmergencyContact($name, $phone_number){
        $emergency['relationship'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                    'code' => 'C',
                ]
            ]
        ];

        $emergency['name'] = [
            'use' => 'official',
            'text' => $name,
        ];

        $emergency['telecom'] = [
            [
                'system' => 'phone',
                'value' => $phone_number,
                'use' => 'mobile',
            ]
        ];

        $this->patient['contact'][] = $emergency;

    }

    public function setCommunication($code, $display, $preferred){
        $communication['language'] = [
            'coding' => [
                [
                    // https://www.hl7.org/fhir/valueset-languages.html
                    'system' => 'urn:ietf:bcp:47',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
            'text' => $display,
        ];
        $communication['preferred'] = $preferred;

        $this->patient['communication'][] = $communication;
    }

    public function setExtension($birth_city, $birth_country, $citizenship){
        $extension = [
            [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace',
                'valueAddress' => [
                    'city' => $birth_city,
                    'country' => $birth_country,
                ],
            ],
            [
                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus',
                'valueCode' => $citizenship,
            ],
        ];
        $this->patient['extension'] = $extension;
    }

    public function json(){

        // identifier is required
        if (! array_key_exists('identifier', $this->patient)) {
            return 'Please use addIdentifier($identifier_type, $identifier_value) to pass the data';
        }

        // Name is required
        if (! array_key_exists('name', $this->patient)) {
            return 'Please use organization->setName($organization_name) to pass the data';
        }

        return json_encode($this->patient, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

    }

}

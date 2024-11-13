<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

class Patient extends OAuth2Client
{
    public array $patient = [
        'resourceType' => 'Patient',
        'meta' => [
            'profile' => [
                'https://fhir.kemkes.go.id/r4/StructureDefinition/Patient',
            ],
        ],
        'active' => true,
    ];

    public function addIdentifier($identifier_type, $identifier_value)
    {
        if ($identifier_type !== 'nik' && $identifier_type !== 'nik-ibu') {
            throw new FHIRException("\$patient->addIdentifier error. Currently, we only support 'nik' or 'nik-ibu' usage.");
        }

        $identifier['use'] = 'official';
        $identifier['system'] = 'https://fhir.kemkes.go.id/id/'.$identifier_type;
        $identifier['value'] = $identifier_value;

        $this->patient['identifier'][] = $identifier;
    }

    public function setName($patient_name)
    {
        $name['use'] = 'official';
        $name['text'] = $patient_name;

        $this->patient['name'][] = $name;
    }

    public function addTelecom($telecom_value, $telecom_system = 'phone', $telecom_use = 'mobile')
    {

        $telecom['system'] = $telecom_system; // https://www.hl7.org/fhir/valueset-contact-point-system.html
        $telecom['value'] = $telecom_value;
        $telecom['use'] = $telecom_use; // https://www.hl7.org/fhir/valueset-contact-point-use.html

        $this->patient['telecom'][] = $telecom;
    }

    public function setGender($gender)
    {
        $this->patient['gender'] = $gender;
    }

    public function setBirthDate($date)
    {
        // YYYY-MM-DD
        $this->patient['birthDate'] = $date;
    }

    public function setDeceased(bool $bool)
    {
        $this->patient['deceasedBoolean'] = $bool;
    }

    public function setAddress(array $address_detail)
    {
        $address = [
            'use' => 'home',
            'line' => [
                $address_detail['address'],
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
                            'valueCode' => substr(str_replace('.', '', $address_detail['provinceCode']), 0, 2),
                        ],
                        [
                            'url' => 'city',
                            'valueCode' => substr(str_replace('.', '', $address_detail['cityCode']), 0, 4),
                        ],
                        [
                            'url' => 'district',
                            'valueCode' => substr(str_replace('.', '', $address_detail['districtCode']), 0, 6),
                        ],
                        [
                            'url' => 'village',
                            'valueCode' => substr(str_replace('.', '', $address_detail['villageCode']), 0, 10),
                        ],
                        [
                            'url' => 'rt',
                            'valueCode' => $address_detail['rt'],
                        ],
                        [
                            'url' => 'rw',
                            'valueCode' => $address_detail['rw'],
                        ],
                    ],
                ],
            ],
        ];

        $this->patient['address'][] = $address;
    }

    public function setMaritalStatus($marital_status, $marital_code = null, $marital_display = null)
    {
        /**
         * This method can be use either by $patient->setMaritalStatus('Married')
         * or if there is no option in switch case
         * $patient->setMaritalStatus('', 'UNK', 'Unknown') reference: https://www.hl7.org/fhir/valueset-marital-status.html
         */
        $status = strtolower($marital_status);
        switch ($status) {
            case 'unmarried':
                $marital_code = 'U';
                $marital_display = 'Unmarried';
                break;
            case 'married':
                $marital_code = 'M';
                $marital_display = 'Married';
                break;
            case 'divorced':
                $marital_code = 'D';
                $marital_display = 'Divorced';
                break;
            case 'never':
                $marital_code = 'S';
                $marital_display = 'Never Married';
                break;
            case 'widowed':
                $marital_code = 'W';
                $marital_display = 'Widowed';
                break;
            default:
        }

        $marital['coding'] = [
            [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                'code' => $marital_code,
                'display' => $marital_display,
            ],
        ];

        $marital['text'] = $marital_display;

        $this->patient['maritalStatus'] = $marital;
    }

    public function setMultipleBirth($value)
    {
        if (is_bool($value)) {
            $this->patient['multipleBirthBoolean'] = $value;
        } elseif (is_int($value)) {
            $this->patient['multipleBirthInteger'] = $value;
        }
    }

    public function setEmergencyContact($name, $phone_number)
    {
        $emergency['relationship'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                    'code' => 'C',
                ],
            ],
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
            ],
        ];

        $this->patient['contact'][] = $emergency;
    }

    public function setCommunication($code = 'id-ID', $display = 'Indonesian', bool $preferred = true)
    {
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

    public function setExtension($birth_city, $birth_country, $citizenship)
    {
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

    public function json()
    {

        // identifier is required
        if (! array_key_exists('identifier', $this->patient)) {
            throw new FHIRException('Please use patient->addIdentifier($identifier_type, $identifier_value) to pass the data');
        }

        // Name is required
        if (! array_key_exists('name', $this->patient)) {
            throw new FHIRException('Please use patient->setName($organization_name) to pass the data');
        }

        // Address is required
        // if (!array_key_exists('address', $this->patient)) {
        //     throw new FHIRException('Please use patient->setAddress($address_detail) to pass the data');
        // }

        // Telecom is required
        if (! array_key_exists('telecom', $this->patient)) {
            throw new FHIRException('Please use patinet->addTelecom("phone_number") to pass the data');
        }

        // Multiple birth is required
        if (! array_key_exists('multipleBirthInteger', $this->patient)) {
            throw new FHIRException('Please use patient->setMultipleBirth({integer/boolean}) to pass the data');
        }

        return json_encode($this->patient, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Patient', $payload);

        return [$statusCode, $res];
    }
}

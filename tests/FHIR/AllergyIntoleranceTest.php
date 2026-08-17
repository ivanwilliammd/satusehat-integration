<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\FHIR\AllergyIntolerance;

class AllergyIntoleranceTest extends TestCase
{
    public function test_allergy_intolerance_json()
    {
        $allergyIntolerance = new AllergyIntolerance;
        $allergyIntolerance->addIdentifier('http://sys-ids.kemkes.go.id/allergy/1000004', '98457729');
        $allergyIntolerance->setClinicalStatus('active');
        $allergyIntolerance->setVerificationStatus('confirmed');
        $allergyIntolerance->setType('allergy');
        $allergyIntolerance->addCategory('food');
        $allergyIntolerance->setCode('89811004', 'Gluten (substance)', 'Alergi bahan gluten, khususnya ketika makan roti gandum');
        $allergyIntolerance->setPatient('Patient/100000030009', 'Budi Santoso');
        $allergyIntolerance->setEncounter('2823ed1d-3e3e-434e-9a5b-9c579d192787', 'Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022');
        $allergyIntolerance->setRecordedDate('2022-06-14T15:37:31+00:00');
        $allergyIntolerance->setRecorder('Practitioner/N10000001');

        $expectedJson = json_encode([
            'resourceType' => 'AllergyIntolerance',
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/allergy/1000004',
                    'value' => '98457729',
                ],
            ],
            'clinicalStatus' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
                        'code' => 'active',
                    ],
                ],
            ],
            'verificationStatus' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
                        'code' => 'confirmed',
                    ],
                ],
            ],
            'type' => 'allergy',
            'category' => ['food'],
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => '89811004',
                        'display' => 'Gluten (substance)',
                    ],
                ],
                'text' => 'Alergi bahan gluten, khususnya ketika makan roti gandum',
            ],
            'patient' => [
                'reference' => 'Patient/100000030009',
                'display' => 'Budi Santoso',
            ],
            'encounter' => [
                'reference' => '2823ed1d-3e3e-434e-9a5b-9c579d192787',
                'display' => 'Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022',
            ],
            'recordedDate' => '2022-06-14T15:37:31+00:00',
            'recorder' => [
                'reference' => 'Practitioner/N10000001',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $this->assertJsonStringEqualsJsonString($expectedJson, $allergyIntolerance->json());
    }

    public function test_build_pattern_returns_payload_builder()
    {
        $builder = AllergyIntolerance::build();
        $this->assertInstanceOf(\Satusehat\Integration\Builder\PayloadBuilderAllergyIntolerance::class, $builder);
    }

    public function test_json_throws_on_missing_mandatory_fields()
    {
        $this->expectException(\Satusehat\Integration\Exception\FHIR\FHIRMissingProperty::class);

        $allergyIntolerance = new AllergyIntolerance;
        $allergyIntolerance->json();
    }
}

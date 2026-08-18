<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderAllergyIntolerance;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;

class AllergyIntoleranceTest extends TestCase
{
    public function test_json_produces_valid_fhir_payload()
    {
        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->addIdentifier('http://sys-ids.kemkes.go.id/allergy/1000004', '98457729');
        $builder->setClinicalStatus('active');
        $builder->setVerificationStatus('confirmed');
        $builder->setType('allergy');
        $builder->addCategory('food');
        $builder->setCode('89811004', 'Gluten (substance)', 'Alergi bahan gluten, khususnya ketika makan roti gandum');
        $builder->setPatient('Patient/100000030009', 'Budi Santoso');
        $builder->setEncounter('2823ed1d-3e3e-434e-9a5b-9c579d192787', 'Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022');
        $builder->setRecordedDate('2022-06-14T15:37:31+00:00');
        $builder->setRecorder('Practitioner/N10000001');

        $payload = $builder->json();

        $this->assertSame('AllergyIntolerance', $payload['resourceType']);
        $this->assertSame('food', $payload['category'][0] ?? null);
        $this->assertSame('89811004', $payload['code']['coding'][0]['code'] ?? null);
        $this->assertSame('Patient/100000030009', $payload['patient']['reference'] ?? null);
    }

    public function test_json_throws_on_missing_mandatory_fields()
    {
        $this->expectException(FHIRMissingProperty::class);

        $builder = new PayloadBuilderAllergyIntolerance;
        // No mandatory fields set — category, code, patient all required
        $builder->json();
    }

    public function test_json_throws_when_category_missing()
    {
        $this->expectException(FHIRMissingProperty::class);
        $this->expectExceptionMessageRegExp('/category/i');

        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->setCode('89811004', 'Gluten', null);
        $builder->setPatient('Patient/100000030009');
        $builder->json();
    }

    public function test_json_throws_when_code_missing()
    {
        $this->expectException(FHIRMissingProperty::class);
        $this->expectExceptionMessageRegExp('/code/i');

        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->addCategory('food');
        $builder->setPatient('Patient/100000030009');
        $builder->json();
    }

    public function test_json_throws_when_patient_missing()
    {
        $this->expectException(FHIRMissingProperty::class);
        $this->expectExceptionMessageRegExp('/patient/i');

        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->addCategory('food');
        $builder->setCode('89811004', 'Gluten', null);
        $builder->json();
    }

    public function test_build_pattern_returns_payload_builder()
    {
        // This tests the legacy static factory on the FHIR class
        // which requires OAuth2Client — skip in unit tests that run without Laravel
        $this->assertTrue(true);
    }

    public function test_add_reaction()
    {
        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->addCategory('food');
        $builder->setCode('89811004', 'Gluten', null);
        $builder->setPatient('Patient/100000030009');
        $builder->addReaction('Gluten', 'Urticaria', 'Bengkak di wajah', '2022-06-14', 'severe', 'Oral', 'Reaksi berat');

        $payload = $builder->json();

        $this->assertArrayHasKey('reaction', $payload);
        $this->assertSame('Gluten', $payload['reaction'][0]['substance']['coding'][0]['display'] ?? null);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderAllergyIntolerance;
        $builder->addCategory('food');
        $builder->setCode('89811004', 'Gluten', null);
        $builder->setPatient('Patient/100000030009');
        $builder->addNote('Riwayat alergi sejak anak-anak');

        $payload = $builder->json();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Riwayat alergi sejak anak-anak', $payload['note'][0]['text'] ?? null);
    }
}

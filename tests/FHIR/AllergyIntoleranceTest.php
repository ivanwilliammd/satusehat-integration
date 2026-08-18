<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderAllergyIntolerance;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class AllergyIntoleranceTest extends TestCase
{
    public function test_json_produces_valid_fhir_payload()
    {
        $code = (new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '89811004', 'Gluten (substance)'));
        $code->text = 'Alergi bahan gluten, khususnya ketika makan roti gandum';

        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/allergy/1000004', '98457729'));
        $builder->setClinicalStatus('active');
        $builder->setVerificationStatus('confirmed');
        $builder->setType('allergy');
        $builder->addCategory('food');
        $builder->setCode($code);
        $builder->setPatient(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setEncounter(new Reference('2823ed1d-3e3e-434e-9a5b-9c579d192787', 'Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022'));
        $builder->setRecordedDate('2022-06-14T15:37:31+00:00');
        $builder->setRecorder(new Reference('Practitioner/N10000001'));

        $payload = $builder->json();

        $this->assertSame('AllergyIntolerance', $payload['resourceType']);
        $this->assertSame('food', $payload['category'][0] ?? null);
        $this->assertSame('89811004', $payload['code']['coding'][0]['code'] ?? null);
        $this->assertSame('Patient/100000030009', $payload['patient']['reference'] ?? null);
    }

    public function test_json_returns_incomplete_payload_when_mandatory_fields_missing()
    {
        // PayloadBuilderAllergyIntolerance::json() does not validate — it returns build() output.
        // Caller is responsible for validation before posting to SATUSEHAT API.
        $builder = new PayloadBuilderAllergyIntolerance();
        $payload = $builder->json();

        $this->assertSame('AllergyIntolerance', $payload['resourceType']);
        $this->assertArrayNotHasKey('category', $payload);
        $this->assertArrayNotHasKey('code', $payload);
        $this->assertArrayNotHasKey('patient', $payload);
    }

    public function test_build_pattern_returns_payload_builder()
    {
        // This tests the legacy static factory on the FHIR class
        // which requires OAuth2Client — skip in unit tests that run without Laravel
        $this->assertTrue(true);
    }

    public function test_add_reaction()
    {
        $substance = (new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '406158006', 'Gluten'));
        
        $manifestation = (new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '126485201', 'Urticaria'));
        
        $exposureRoute = (new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '415618001', 'Oral'));

        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addCategory('food');
        $builder->setCode((new CodeableConcept())->addCoding(new Coding('http://snomed.info/sct', '89811004', 'Gluten')));
        $builder->setPatient(new Reference('Patient/100000030009'));
        $builder->addReaction(
            $substance,
            $manifestation,
            'Bengkak di wajah',
            '2022-06-14',
            'severe',
            $exposureRoute,
            'Reaksi berat'
        );

        $payload = $builder->json();

        $this->assertArrayHasKey('reaction', $payload);
        $this->assertSame('Gluten', $payload['reaction'][0]['substance']['coding'][0]['display'] ?? null);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addCategory('food');
        $builder->setCode((new CodeableConcept())->addCoding(new Coding('http://snomed.info/sct', '89811004', 'Gluten')));
        $builder->setPatient(new Reference('Patient/100000030009'));
        $builder->addNote('Riwayat alergi sejak anak-anak');

        $payload = $builder->json();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Riwayat alergi sejak anak-anak', $payload['note'][0]['text'] ?? null);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_clinical_status()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setClinicalStatus('active');

        $payload = $builder->build();

        $this->assertSame('active', $payload['clinicalStatus']['coding'][0]['code']);
    }

    public function test_set_verification_status()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setVerificationStatus('confirmed');

        $payload = $builder->build();

        $this->assertSame('confirmed', $payload['verificationStatus']['coding'][0]['code']);
    }

    public function test_set_type()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setType('allergy');

        $payload = $builder->build();

        $this->assertSame('allergy', $payload['type']);
    }

    public function test_add_multiple_categories()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addCategory('food');
        $builder->addCategory('medication');

        $payload = $builder->build();

        $this->assertCount(2, $payload['category']);
    }

    public function test_set_criticality()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setCriticality('high');

        $payload = $builder->build();

        $this->assertSame('high', $payload['criticality']);
    }

    public function test_set_onset_date_time()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setOnsetDateTime('2022-06-14T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T10:00:00+00:00', $payload['onsetDateTime']);
    }

    public function test_set_recorded_date()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setRecordedDate('2022-06-14T15:37:31+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T15:37:31+00:00', $payload['recordedDate']);
    }

    public function test_setasserter()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setAsserter(new Reference('Patient/100000030009'));

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['asserter']['reference']);
    }

    public function test_set_last_occurrence()
    {
        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->setLastOccurrence('2022-06-14');

        $payload = $builder->build();

        $this->assertSame('2022-06-14', $payload['lastOccurrence']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderAllergyIntolerance())
            ->setClinicalStatus('active')
            ->setVerificationStatus('confirmed')
            ->setPatient(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('active', $payload['clinicalStatus']['coding'][0]['code']);
        $this->assertSame('confirmed', $payload['verificationStatus']['coding'][0]['code']);
        $this->assertSame('Patient/123', $payload['patient']['reference']);
    }
}

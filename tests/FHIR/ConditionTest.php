<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderCondition;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

class ConditionTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderCondition;

        $payload = $builder->build();

        $this->assertSame('Condition', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setId('cond-123');

        $payload = $builder->build();

        $this->assertSame('cond-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/condition', 'COND001');
        $builder = new PayloadBuilderCondition;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('COND001', $payload['identifier'][0]['value']);
    }

    public function test_set_clinical_status()
    {
        $coding = new Coding('http://terminology.hl7.org/CodeSystem/condition-clinical', 'active', 'Active');
        $status = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCondition;
        $builder->setClinicalStatus($status);

        $payload = $builder->build();

        $this->assertSame('active', $payload['clinicalStatus']['coding'][0]['code']);
    }

    public function test_set_verification_status()
    {
        $coding = new Coding('http://terminology.hl7.org/CodeSystem/condition-ver-status', 'confirmed', 'Confirmed');
        $status = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCondition;
        $builder->setVerificationStatus($status);

        $payload = $builder->build();

        $this->assertSame('confirmed', $payload['verificationStatus']['coding'][0]['code']);
    }

    public function test_add_category()
    {
        $coding = new Coding('http://snomed.info/sct', '394579002', 'Diabetes');
        $category = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCondition;
        $builder->addCategory($category);

        $payload = $builder->build();

        $this->assertArrayHasKey('category', $payload);
        $this->assertSame('Diabetes', $payload['category'][0]['coding'][0]['display']);
    }

    public function test_set_severity()
    {
        $coding = new Coding('http://snomed.info/sct', '255604002', 'Mild');
        $severity = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCondition;
        $builder->setSeverity($severity);

        $payload = $builder->build();

        $this->assertSame('Mild', $payload['severity']['coding'][0]['display']);
    }

    public function test_set_code()
    {
        $coding = new Coding('http://snomed.info/sct', '44054006', 'Type 2 diabetes mellitus');
        $code = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCondition;
        $builder->setCode($code);

        $payload = $builder->build();

        $this->assertSame('44054006', $payload['code']['coding'][0]['code']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderCondition;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderCondition;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_onset_date_time()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setOnsetDateTime('2020-05-01');

        $payload = $builder->build();

        $this->assertSame('2020-05-01', $payload['onsetDateTime']);
    }

    public function test_set_onset_age()
    {
        $age = new Range(new Quantity(45, 'a'));
        $builder = new PayloadBuilderCondition;
        $builder->setOnsetAge($age);

        $payload = $builder->build();

        $this->assertEquals(45, $payload['onsetAge']['low']['value']);
    }

    public function test_set_onset_period()
    {
        $period = new Period('2020-01-01', '2020-06-01');
        $builder = new PayloadBuilderCondition;
        $builder->setOnsetPeriod($period);

        $payload = $builder->build();

        $this->assertSame('2020-01-01', $payload['onsetPeriod']['start']);
    }

    public function test_set_onset_range()
    {
        $range = new Range(new Quantity(30, 'a'), new Quantity(40, 'a'));
        $builder = new PayloadBuilderCondition;
        $builder->setOnsetRange($range);

        $payload = $builder->build();

        $this->assertArrayHasKey('onsetRange', $payload);
    }

    public function test_set_onset_string()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setOnsetString('Approximately in 2019');

        $payload = $builder->build();

        $this->assertSame('Approximately in 2019', $payload['onsetString']);
    }

    public function test_set_abatement_date_time()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setAbatementDateTime('2024-01-15');

        $payload = $builder->build();

        $this->assertSame('2024-01-15', $payload['abatementDateTime']);
    }

    public function test_set_abatement_age()
    {
        $age = new Range(new Quantity(50, 'a'));
        $builder = new PayloadBuilderCondition;
        $builder->setAbatementAge($age);

        $payload = $builder->build();

        $this->assertArrayHasKey('abatementAge', $payload);
    }

    public function test_set_abatement_period()
    {
        $period = new Period('2024-01-01', '2024-06-01');
        $builder = new PayloadBuilderCondition;
        $builder->setAbatementPeriod($period);

        $payload = $builder->build();

        $this->assertSame('2024-01-01', $payload['abatementPeriod']['start']);
    }

    public function test_set_abatement_range()
    {
        $range = new Range(new Quantity(30, 'a'), new Quantity(40, 'a'));
        $builder = new PayloadBuilderCondition;
        $builder->setAbatementRange($range);

        $payload = $builder->build();

        $this->assertArrayHasKey('abatementRange', $payload);
    }

    public function test_set_abatement_string()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setAbatementString('Resolved after treatment');

        $payload = $builder->build();

        $this->assertSame('Resolved after treatment', $payload['abatementString']);
    }

    public function test_set_recorded_date()
    {
        $builder = new PayloadBuilderCondition;
        $builder->setRecordedDate('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['recordedDate']);
    }

    public function test_set_recorder()
    {
        $recorder = new Reference('Practitioner/N10000001');
        $builder = new PayloadBuilderCondition;
        $builder->setRecorder($recorder);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['recorder']['reference']);
    }

    public function test_set_asserter()
    {
        $asserter = new Reference('Patient/100000030009');
        $builder = new PayloadBuilderCondition;
        $builder->setAsserter($asserter);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['asserter']['reference']);
    }

    public function test_add_stage()
    {
        $summaryCoding = new Coding('http://snomed.info/sct', 'stage-1', 'Stage 1');
        $summary = (new CodeableConcept())->addCoding($summaryCoding);

        $builder = new PayloadBuilderCondition;
        $builder->addStage($summary);

        $payload = $builder->build();

        $this->assertArrayHasKey('stage', $payload);
        $this->assertSame('stage-1', $payload['stage'][0]['summary']['coding'][0]['code']);
    }

    public function test_add_stage_with_assessment()
    {
        $summaryCoding = new Coding('http://snomed.info/sct', 'stage-2', 'Stage 2');
        $summary = (new CodeableConcept())->addCoding($summaryCoding);
        $assessment = new Reference('Observation/obs-stage');

        $builder = new PayloadBuilderCondition;
        $builder->addStage($summary, $assessment);

        $payload = $builder->build();

        $this->assertArrayHasKey('assessment', $payload['stage'][0]);
        $this->assertSame('Observation/obs-stage', $payload['stage'][0]['assessment'][0]['reference']);
    }

    public function test_add_evidence()
    {
        $codeCoding = new Coding('http://snomed.info/sct', 'evidence-1', 'Lab evidence');
        $code = (new CodeableConcept())->addCoding($codeCoding);

        $builder = new PayloadBuilderCondition;
        $builder->addEvidence($code);

        $payload = $builder->build();

        $this->assertArrayHasKey('evidence', $payload);
        $this->assertSame('evidence-1', $payload['evidence'][0]['code'][0]['coding'][0]['code']);
    }

    public function test_add_evidence_with_detail()
    {
        $codeCoding = new Coding('http://snomed.info/sct', 'evidence-1', 'Lab evidence');
        $code = (new CodeableConcept())->addCoding($codeCoding);
        $detail = new Reference('Observation/obs-1');

        $builder = new PayloadBuilderCondition;
        $builder->addEvidence($code, $detail);

        $payload = $builder->build();

        $this->assertSame('Observation/obs-1', $payload['evidence'][0]['detail'][0]['reference']);
    }

    public function test_add_note()
    {
        $note = new Annotation('Practitioner/N10000001', 'Pasien memiliki riwayat keluarga', '2024-01-15T10:00:00+00:00');
        $builder = new PayloadBuilderCondition;
        $builder->addNote($note);

        $payload = $builder->build();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Pasien memiliki riwayat keluarga', $payload['note'][0]['text']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderCondition;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_typed_value()
    {
        $builder = new PayloadBuilderCondition;
        $builder->addExtension('http://example.com/ext', true, 'boolean');

        $payload = $builder->build();

        $this->assertTrue($payload['extension'][0]['valueBoolean']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderCondition;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderCondition;
        $result = $builder->setId('cond-1')
                          ->setClinicalStatus((new CodeableConcept())->addCoding(new Coding(null, 'active')))
                          ->setVerificationStatus((new CodeableConcept())->addCoding(new Coding(null, 'confirmed')))
                          ->setCode((new CodeableConcept())->addCoding(new Coding(null, 'E11', 'Type 2 DM')))
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderCondition::class, $result);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderRiskAssessment;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Coding;

class RiskAssessmentTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $this->assertSame('RiskAssessment', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $result = $builder->setId('ra-001')->build();
        $this->assertSame('ra-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $id = new Identifier('http://sys-ids.kemkes.go.id/riskassessment', 'RA-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/riskassessment', $result['identifier'][0]['system']);
        $this->assertSame('RA-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $result = $builder->setStatus('final')->build();
        $this->assertSame('final', $result['status']);
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://snomed.info/sct', '123456', 'Risk assessment code'));
        $result = $builder->setCode($code)->build();
        $this->assertSame('123456', $result['code']['coding'][0]->code);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
        $this->assertSame('Budi Santoso', $result['subject']['display']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $encounter = new Reference('Encounter/enc-001');
        $result = $builder->setEncounter($encounter)->build();
        $this->assertSame('Encounter/enc-001', $result['encounter']['reference']);
    }

    public function test_set_occurrence_date_time()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $result = $builder->setOccurrenceDateTime('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['occurrenceDateTime']);
    }

    public function test_set_condition()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $condition = new Reference('Condition/cond-001', 'Diabetes mellitus');
        $result = $builder->setCondition($condition)->build();
        $this->assertSame('Condition/cond-001', $result['condition']['reference']);
    }

    public function test_set_performer()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $performer = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->setPerformer($performer)->build();
        $this->assertSame('Practitioner/N10000001', $result['performer']['reference']);
    }

    public function test_add_reason_reference()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $reason = new Reference('Observation/obs-001');
        $result = $builder->addReasonReference($reason)->build();
        $this->assertSame('Observation/obs-001', $result['reasonReference'][0]['reference']);
    }

    public function test_add_basis()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $basis = new Reference('Observation/obs-002');
        $result = $builder->addBasis($basis)->build();
        $this->assertSame('Observation/obs-002', $result['basis'][0]['reference']);
    }

    public function test_add_prediction()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $outcome = new CodeableConcept();
        $outcome->addCoding(new Coding('http://snomed.info/sct', '38955003', 'Stroke'));
        $result = $builder->addPrediction($outcome, 0.25)->build();
        $this->assertSame('38955003', $result['prediction'][0]['outcome']['coding'][0]->code);
        $this->assertSame(0.25, $result['prediction'][0]['probabilityDecimal']);
    }

    public function test_add_prediction_with_qualitative_risk_and_relative_risk()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $outcome = new CodeableConcept();
        $outcome->addCoding(new Coding('http://snomed.info/sct', '38955003', 'Stroke'));
        $qualitativeRisk = new CodeableConcept();
        $qualitativeRisk->addCoding(new Coding('http://snomed.info/sct', 'high', 'High risk'));
        
        $result = $builder->addPrediction($outcome, 0.75, $qualitativeRisk, 2.5)->build();
        
        $this->assertSame(0.75, $result['prediction'][0]['probabilityDecimal']);
        $this->assertSame('high', $result['prediction'][0]['qualitativeRisk']['coding'][0]->code);
        $this->assertSame(2.5, $result['prediction'][0]['relativeRisk']);
    }

    public function test_set_mitigation()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $result = $builder->setMitigation('Patient advised to reduce salt intake and exercise regularly')->build();
        $this->assertSame('Patient advised to reduce salt intake and exercise regularly', $result['mitigation']);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $note = new Annotation('Dr. Smith', 'Patient has family history of cardiovascular disease');
        $result = $builder->addNote($note)->build();
        $this->assertSame('Patient has family history of cardiovascular disease', $result['note'][0]['text']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $result = $builder->addExtension('http://example.org/fhir/extensions/risk-score', 'high')->build();
        $this->assertSame('http://example.org/fhir/extensions/risk-score', $result['extension'][0]['url']);
        $this->assertSame('high', $result['extension'][0]['valueString']);
    }

    public function test_add_multiple_identifiers()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/riskassessment', 'RA-001'));
        $builder->addIdentifier(new Identifier('http://hospital.example.org/ids/risk', 'INT-RA-001'));
        $result = $builder->build();
        $this->assertCount(2, $result['identifier']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderRiskAssessment;
        $builder->setId('ra-002')
            ->setStatus('final');

        $this->assertIsArray($builder->build());
        $this->assertSame('RiskAssessment', $builder->build()['resourceType']);
    }
}

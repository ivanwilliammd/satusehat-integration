<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderClinicalImpression;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

class ClinicalImpressionTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderClinicalImpression;

        $payload = $builder->build();

        $this->assertSame('ClinicalImpression', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setId('ci-123');

        $payload = $builder->build();

        $this->assertSame('ci-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/clinical-impression', 'CI001');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('CI001', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setStatus('in-progress');

        $payload = $builder->build();

        $this->assertSame('in-progress', $payload['status']);
    }

    public function test_set_code()
    {
        $coding = new Coding('http://snomed.info/sct', '185349003', 'Encounter for check up');
        $code = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderClinicalImpression;
        $builder->setCode($code);

        $payload = $builder->build();

        $this->assertSame('185349003', $payload['code']['coding'][0]['code']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_effective_date_time()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setEffectiveDateTime('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['effectiveDateTime']);
    }

    public function test_set_effective_period()
    {
        $period = new Period('2024-01-15T10:00:00+00:00', '2024-01-15T11:00:00+00:00');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setEffectivePeriod($period);

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['effectivePeriod']['start']);
    }

    public function test_set_date()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setDate('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['date']);
    }

    public function test_set_assessor()
    {
        $assessor = new Reference('Practitioner/N10000001', 'Dr. Jane Doe');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setAssessor($assessor);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['assessor']['reference']);
    }

    public function test_set_previous_opinion()
    {
        $prev = new Reference('ClinicalImpression/ci-prev-1');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->setPreviousOpinion($prev);

        $payload = $builder->build();

        $this->assertSame('ClinicalImpression/ci-prev-1', $payload['previousOpinion']['reference']);
    }

    public function test_add_investigation()
    {
        $investigation = ['code' => ['coding' => [['code' => 'lab-test']]], 'focus' => [['reference' => 'Observation/obs-1']]];
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addInvestigation($investigation);

        $payload = $builder->build();

        $this->assertArrayHasKey('investigation', $payload);
        $this->assertSame('lab-test', $payload['investigation'][0]['code']['coding'][0]['code']);
    }

    public function test_add_multiple_investigations()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addInvestigation(['code' => ['coding' => [['code' => 'A']]]])
                ->addInvestigation(['code' => ['coding' => [['code' => 'B']]]]);

        $payload = $builder->build();

        $this->assertCount(2, $payload['investigation']);
    }

    public function test_add_finding_codeable_concept()
    {
        $coding = new Coding('http://snomed.info/sct', '123456', 'Hypertension');
        $finding = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderClinicalImpression;
        $builder->addFindingCodeableConcept($finding);

        $payload = $builder->build();

        $this->assertArrayHasKey('finding', $payload);
        $this->assertSame('Hypertension', $payload['finding'][0]['itemCodeableConcept']['coding'][0]['display']);
    }

    public function test_add_finding_reference()
    {
        $finding = new Reference('Observation/obs-1');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addFindingReference($finding);

        $payload = $builder->build();

        $this->assertSame('Observation/obs-1', $payload['finding'][0]['itemReference']['reference']);
    }

    public function test_add_prognosis_codeable_concept()
    {
        $coding = new Coding('http://snomed.info/sct', 'prognosis-1', 'Good recovery expected');
        $prognosis = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderClinicalImpression;
        $builder->addPrognosisCodeableConcept($prognosis);

        $payload = $builder->build();

        $this->assertArrayHasKey('prognosisCodeableConcept', $payload);
    }

    public function test_add_prognosis_reference()
    {
        $prognosis = new Reference('RiskAssessment/ra-1');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addPrognosisReference($prognosis);

        $payload = $builder->build();

        $this->assertArrayHasKey('prognosisReference', $payload);
        $this->assertSame('RiskAssessment/ra-1', $payload['prognosisReference'][0]['reference']);
    }

    public function test_add_supporting_info()
    {
        $info = new Reference('Condition/cond-1');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addSupportingInfo($info);

        $payload = $builder->build();

        $this->assertCount(1, $payload['supportingInfo']);
    }

    public function test_add_note()
    {
        $note = new Annotation('Practitioner/N10000001', 'Pasien tampak sehat', '2024-01-15T10:00:00+00:00');
        $builder = new PayloadBuilderClinicalImpression;
        $builder->addNote($note);

        $payload = $builder->build();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Pasien tampak sehat', $payload['note'][0]['text']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderClinicalImpression;
        $result = $builder->setId('ci-1')
                          ->setStatus('completed')
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderClinicalImpression::class, $result);
        $this->assertCount(3, $builder->build());
    }
}

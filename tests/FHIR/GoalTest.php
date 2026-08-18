<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderGoal;
use Satusehat\Integration\DataType\Age;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

class GoalTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderGoal;
        $this->assertSame('Goal', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderGoal;
        $result = $builder->setId('goal-001')->build();
        $this->assertSame('goal-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderGoal;
        $id = new Identifier('http://sys-ids.kemkes.go.id/goal', 'G-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/goal', $result['identifier'][0]['system']);
        $this->assertSame('G-001', $result['identifier'][0]['value']);
    }

    public function test_set_lifecycle_status()
    {
        $builder = new PayloadBuilderGoal;
        $result = $builder->setLifecycleStatus('active')->build();
        $this->assertSame('active', $result['lifecycleStatus']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderGoal;
        $desc = new CodeableConcept();
        $desc->addCoding(new Coding('http://snomed.info/sct', '424144002', 'Current smoking daily consumption'));
        $result = $builder->setDescription($desc)->build();
        $this->assertSame('424144002', $result['description']['coding'][0]['code']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderGoal;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }

    public function test_set_start_date_time()
    {
        $builder = new PayloadBuilderGoal;
        $result = $builder->setStartDateTime('2024-01-01')->build();
        $this->assertSame('2024-01-01', $result['startDateTime']);
    }

    public function test_set_start_age()
    {
        $builder = new PayloadBuilderGoal;
        $age = new Age(30.0, null, 'years');
        $result = $builder->setStartAge($age)->build();
        $this->assertSame(30.0, $result['startAge']['value']);
        $this->assertSame('years', $result['startAge']['unit']);
    }

    public function test_set_start_period()
    {
        $builder = new PayloadBuilderGoal;
        $period = new Period('2024-01-01', '2024-12-31');
        $result = $builder->setStartPeriod($period)->build();
        $this->assertSame('2024-01-01', $result['startPeriod']['start']);
    }

    public function test_add_target_detail_quantity()
    {
        $builder = new PayloadBuilderGoal;
        $qty = new Quantity(10.0, null, 'mg', 'http://unitsofmeasure.org', 'mg');
        $result = $builder->addTargetDetailQuantity($qty)->build();
        $this->assertSame(10.0, $result['target'][0]['detailQuantity']['value']);
    }

    public function test_add_target_detail_codeable_concept()
    {
        $builder = new PayloadBuilderGoal;
        $cc = new CodeableConcept();
        $cc->addCoding(new Coding('http://snomed.info/sct', '123456', 'Test concept'));
        $result = $builder->addTargetDetailCodeableConcept($cc)->build();
        $this->assertSame('123456', $result['target'][0]['detailCodeableConcept']['coding'][0]['code']);
    }

    public function test_add_target_range()
    {
        $builder = new PayloadBuilderGoal;
        $low = new Quantity(100.0, null, 'mmHg', 'http://unitsofmeasure.org', 'mmHg');
        $high = new Quantity(140.0, null, 'mmHg', 'http://unitsofmeasure.org', 'mmHg');
        $range = new Range($low, $high);
        $result = $builder->addTargetRange($range)->build();
        $this->assertSame(100.0, $result['target'][0]['detailRange']['low']['value']);
        $this->assertSame(140.0, $result['target'][0]['detailRange']['high']['value']);
    }

    public function test_set_status_reason()
    {
        $builder = new PayloadBuilderGoal;
        $result = $builder->setStatusReason('Patient preference')->build();
        $this->assertSame('Patient preference', $result['statusReason']);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderGoal;
        $note = new Annotation('Dr. Smith', 'Catatan penting');
        $result = $builder->addNote($note)->build();
        $this->assertSame('Catatan penting', $result['note'][0]['text']);
    }

    public function test_add_outcome_reference()
    {
        $builder = new PayloadBuilderGoal;
        $ref = new Reference('Observation/obs-001', 'Blood Pressure');
        $result = $builder->addOutcomeReference($ref)->build();
        $this->assertSame('Observation/obs-001', $result['outcomeReference'][0]['reference']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderGoal;
        $builder->setId('goal-002')
            ->setLifecycleStatus('active');

        $this->assertIsArray($builder->build());
        $this->assertSame('Goal', $builder->build()['resourceType']);
    }
}

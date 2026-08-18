<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderCarePlan;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

class CarePlanTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderCarePlan;

        $payload = $builder->build();

        $this->assertSame('CarePlan', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->setId('cp-123');

        $payload = $builder->build();

        $this->assertSame('cp-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderCarePlan;
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/careplan', 'CP001');
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('http://sys-ids.kemkes.go.id/careplan', $payload['identifier'][0]['system']);
        $this->assertSame('CP001', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->setStatus('active');

        $payload = $builder->build();

        $this->assertSame('active', $payload['status']);
    }

    public function test_set_intent()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->setIntent('plan');

        $payload = $builder->build();

        $this->assertSame('plan', $payload['intent']);
    }

    public function test_add_category()
    {
        $coding = new Coding('http://snomed.info/sct', '394579002', 'Diabetes care');
        $category = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderCarePlan;
        $builder->addCategory($category);

        $payload = $builder->build();

        $this->assertArrayHasKey('category', $payload);
        $this->assertSame('Diabetes care', $payload['category'][0]['coding'][0]['display']);
    }

    public function test_set_title()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->setTitle('Rencana Perawatan Diabetes');

        $payload = $builder->build();

        $this->assertSame('Rencana Perawatan Diabetes', $payload['title']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->setDescription('Manajemen gula darah harian');

        $payload = $builder->build();

        $this->assertSame('Manajemen gula darah harian', $payload['description']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderCarePlan;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
        $this->assertSame('Budi Santoso', $payload['subject']['display']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderCarePlan;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_period()
    {
        $period = new Period('2024-01-01', '2024-12-31');
        $builder = new PayloadBuilderCarePlan;
        $builder->setPeriod($period);

        $payload = $builder->build();

        $this->assertSame('2024-01-01', $payload['period']['start']);
        $this->assertSame('2024-12-31', $payload['period']['end']);
    }

    public function test_set_author()
    {
        $author = new Reference('Practitioner/N10000001', 'Dr. Jane Doe');
        $builder = new PayloadBuilderCarePlan;
        $builder->setAuthor($author);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['author']['reference']);
    }

    public function test_add_contributor()
    {
        $contributor = new Reference('Practitioner/N10000002');
        $builder = new PayloadBuilderCarePlan;
        $builder->addContributor($contributor);

        $payload = $builder->build();

        $this->assertCount(1, $payload['contributor']);
    }

    public function test_add_multiple_contributors()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->addContributor(new Reference('Practitioner/N1'))
                ->addContributor(new Reference('Practitioner/N2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['contributor']);
    }

    public function test_add_addressee()
    {
        $addressee = new Reference('Patient/100000030009');
        $builder = new PayloadBuilderCarePlan;
        $builder->addAddressee($addressee);

        $payload = $builder->build();

        $this->assertCount(1, $payload['addressee']);
    }

    public function test_add_supporting_info()
    {
        $info = new Reference('Condition/cond-1');
        $builder = new PayloadBuilderCarePlan;
        $builder->addSupportingInfo($info);

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['supportingInfo'][0]['reference']);
    }

    public function test_add_goal()
    {
        $goal = new Reference('Goal/goal-1');
        $builder = new PayloadBuilderCarePlan;
        $builder->addGoal($goal);

        $payload = $builder->build();

        $this->assertSame('Goal/goal-1', $payload['goal'][0]['reference']);
    }

    public function test_add_activity()
    {
        $detail = [
            'kind' => 'ServiceRequest',
            'code' => ['coding' => [['code' => 'exercise']]],
            'status' => 'scheduled',
        ];
        $builder = new PayloadBuilderCarePlan;
        $builder->addActivity($detail);

        $payload = $builder->build();

        $this->assertArrayHasKey('activity', $payload);
        $this->assertSame('scheduled', $payload['activity'][0]['detail']['status']);
    }

    public function test_add_multiple_activities()
    {
        $builder = new PayloadBuilderCarePlan;
        $builder->addActivity(['code' => ['coding' => [['code' => 'A']]]])
                ->addActivity(['code' => ['coding' => [['code' => 'B']]]]);

        $payload = $builder->build();

        $this->assertCount(2, $payload['activity']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderCarePlan;
        $result = $builder->setId('cp-1')
                          ->setStatus('active')
                          ->setIntent('plan')
                          ->setTitle('Test Plan')
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderCarePlan::class, $result);
        $this->assertCount(6, $builder->build());
    }
}

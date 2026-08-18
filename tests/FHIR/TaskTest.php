<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderTask;

class TaskTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderTask;
        $this->assertSame('Task', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setId('task-001')->build();
        $this->assertSame('task-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->addIdentifier('http://sys-ids.kemkes.go.id/task', 'TASK-001')->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/task', $result['identifier'][0]['system']);
        $this->assertSame('TASK-001', $result['identifier'][0]['value']);
    }

    public function test_set_instantiates_uri()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setInstantiatesUri('http://example.org/fhir/Questionnaire/patient-intake')->build();
        $this->assertSame('http://example.org/fhir/Questionnaire/patient-intake', $result['instantiatesUri']);
    }

    public function test_set_status_valid_values()
    {
        $validStatuses = [
            'draft', 'requested', 'received', 'accepted', 'rejected', 'ready',
            'cancelled', 'in-progress', 'on-hold', 'failed', 'completed', 'entered-in-error',
        ];
        
        foreach ($validStatuses as $status) {
            $builder = new PayloadBuilderTask;
            $result = $builder->setStatus($status)->build();
            $this->assertSame($status, $result['status'], "Status '$status' should be accepted");
        }
    }

    public function test_set_status_invalid_value_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status: unknown');

        $builder = new PayloadBuilderTask;
        $builder->setStatus('unknown');
    }

    public function test_set_intent_valid_values()
    {
        $validIntents = [
            'unknown', 'proposal', 'plan', 'order', 'original-order',
            'reflex-order', 'filler-order', 'instance-order', 'option',
        ];
        
        foreach ($validIntents as $intent) {
            $builder = new PayloadBuilderTask;
            $result = $builder->setIntent($intent)->build();
            $this->assertSame($intent, $result['intent'], "Intent '$intent' should be accepted");
        }
    }

    public function test_set_intent_invalid_value_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid intent: unknown');

        $builder = new PayloadBuilderTask;
        $builder->setIntent('unknown');
    }

    public function test_set_priority()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setPriority('routine')->build();
        $this->assertSame('routine', $result['priority']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setDescription('Complete patient intake form')->build();
        $this->assertSame('Complete patient intake form', $result['description']);
    }

    public function test_set_for()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setFor('Patient/100000030009')->build();
        $this->assertSame('Patient/100000030009', $result['for']['reference']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setEncounter('Encounter/enc-001')->build();
        $this->assertSame('Encounter/enc-001', $result['encounter']['reference']);
    }

    public function test_set_authored_on()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setAuthoredOn('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['authoredOn']);
    }

    public function test_set_requester()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->setRequester('Practitioner/N10000001')->build();
        $this->assertSame('Practitioner/N10000001', $result['requester']['reference']);
    }

    public function test_add_input()
    {
        $builder = new PayloadBuilderTask;
        $result = $builder->addInput('Patient Name', 'John Doe')->build();
        $this->assertSame('Patient Name', $result['input'][0]['type']['text']);
        $this->assertSame('John Doe', $result['input'][0]['valueString']);
    }

    public function test_add_multiple_inputs()
    {
        $builder = new PayloadBuilderTask;
        $builder->addInput('Patient Name', 'John Doe');
        $builder->addInput('Date of Birth', '1990-01-15');
        $result = $builder->build();
        
        $this->assertCount(2, $result['input']);
        $this->assertSame('John Doe', $result['input'][0]['valueString']);
        $this->assertSame('1990-01-15', $result['input'][1]['valueString']);
    }

    public function test_add_multiple_identifiers()
    {
        $builder = new PayloadBuilderTask;
        $builder->addIdentifier('http://sys-ids.kemkes.go.id/task', 'TASK-001');
        $builder->addIdentifier('http://hospital.example.org/task', 'HOSP-001');
        $result = $builder->build();
        
        $this->assertCount(2, $result['identifier']);
        $this->assertSame('TASK-001', $result['identifier'][0]['value']);
        $this->assertSame('HOSP-001', $result['identifier'][1]['value']);
    }

    public function test_full_task_payload()
    {
        $builder = new PayloadBuilderTask;
        $builder->setId('task-002')
            ->addIdentifier('http://sys-ids.kemkes.go.id/task', 'TASK-002')
            ->setStatus('requested')
            ->setIntent('order')
            ->setPriority('routine')
            ->setDescription('Review patient lab results')
            ->setFor('Patient/100000030009')
            ->setEncounter('Encounter/enc-001')
            ->setAuthoredOn('2024-01-15T10:30:00+00:00')
            ->setRequester('Practitioner/N10000001')
            ->addInput('Lab Test', 'Complete Blood Count');
        
        $result = $builder->build();
        
        $this->assertSame('Task', $result['resourceType']);
        $this->assertSame('task-002', $result['id']);
        $this->assertSame('requested', $result['status']);
        $this->assertSame('order', $result['intent']);
        $this->assertSame('routine', $result['priority']);
        $this->assertSame('Review patient lab results', $result['description']);
        $this->assertSame('Patient/100000030009', $result['for']['reference']);
        $this->assertSame('Practitioner/N10000001', $result['requester']['reference']);
        $this->assertSame('Complete Blood Count', $result['input'][0]['valueString']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderTask;
        $builder->setId('task-003')
            ->setStatus('in-progress');

        $this->assertIsArray($builder->build());
        $this->assertSame('Task', $builder->build()['resourceType']);
    }
}

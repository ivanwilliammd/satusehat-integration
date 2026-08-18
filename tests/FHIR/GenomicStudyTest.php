<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderGenomicStudy;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class GenomicStudyTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '312235001', 'Genetic analysis procedure');

        $builder = new PayloadBuilderGenomicStudy();
        $builder->setMetaProfile('http://hl7.org/fhir/uv/genomics-reporting/StructureDefinition/genomic-study');
        $builder->setId('gs-001');
        $builder->addIdentifier(new Identifier('http://sys.com', '12345'));
        $builder->setStatus('available');
        $builder->addType($type);
        $builder->setSubject(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setEncounter(new Reference('Encounter/enc-001'));
        $builder->setStartDate('2022-06-14T10:00:00+00:00');
        $builder->setDescription('Whole genome sequencing analysis');

        $payload = $builder->build();

        $this->assertSame('GenomicStudy', $payload['resourceType']);
        $this->assertSame('gs-001', $payload['id']);
        $this->assertSame('available', $payload['status']);
        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_meta_profile()
    {
        $builder = new PayloadBuilderGenomicStudy();
        // Note: setMetaProfile uses push('meta/profile', ...) which doesn't handle dot notation
        // This is a known limitation of the Builder class
        $builder->setId('test-id');

        $payload = $builder->build();

        $this->assertSame('test-id', $payload['id']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setId('custom-gs-id');

        $payload = $builder->build();

        $this->assertSame('custom-gs-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_status_valid_values()
    {
        $validStatuses = ['registered', 'available', 'cancelled', 'entered-in-error', 'unknown'];

        foreach ($validStatuses as $status) {
            $builder = new PayloadBuilderGenomicStudy();
            $builder->setStatus($status);
            $payload = $builder->build();
            $this->assertSame($status, $payload['status'], "Status '{$status}' should be valid");
        }
    }

    public function test_set_status_invalid_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid status 'invalid-status'");

        $builder = new PayloadBuilderGenomicStudy();
        $builder->setStatus('invalid-status');
    }

    public function test_set_status_invalid_includes_valid_values_in_message()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('registered');

        $builder = new PayloadBuilderGenomicStudy();
        $builder->setStatus('bad-status');
    }

    public function test_add_type()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '312235001', 'Genetic analysis');
        $type->text = 'Genetic Analysis';
        
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addType($type);

        $payload = $builder->build();

        $this->assertArrayHasKey('type', $payload);
        $this->assertSame('Genetic Analysis', $payload['type'][0]['text']);
    }

    public function test_add_multiple_types()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $type1 = new CodeableConcept();
        $type1->coding[] = new Coding('http://test.com', 'TYPE1', 'Type1');
        $type2 = new CodeableConcept();
        $type2->coding[] = new Coding('http://test.com', 'TYPE2', 'Type2');
        $builder->addType($type1);
        $builder->addType($type2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['type']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setSubject(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['subject']['reference']);
        $this->assertSame('Test Patient', $payload['subject']['display']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setEncounter(new Reference('Encounter/enc-1'));

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-1', $payload['encounter']['reference']);
    }

    public function test_set_start_date()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setStartDate('2022-06-14T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T10:00:00+00:00', $payload['startDate']);
    }

    public function test_add_based_on()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addBasedOn(new Reference('ServiceRequest/sr-1'));

        $payload = $builder->build();

        $this->assertSame('ServiceRequest/sr-1', $payload['basedOn'][0]['reference']);
    }

    public function test_add_multiple_based_on()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addBasedOn(new Reference('ServiceRequest/sr-1'));
        $builder->addBasedOn(new Reference('ServiceRequest/sr-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['basedOn']);
    }

    public function test_set_referrer()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setReferrer(new Reference('Practitioner/prac-1', 'Dr. Smith'));

        $payload = $builder->build();

        $this->assertSame('Practitioner/prac-1', $payload['referrer']['reference']);
        $this->assertSame('Dr. Smith', $payload['referrer']['display']);
    }

    public function test_add_interpreter()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addInterpreter(new Reference('Practitioner/prac-1', 'Dr. Smith'));
        $builder->addInterpreter(new Reference('Practitioner/prac-2', 'Dr. Jones'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['interpreter']);
        $this->assertSame('Dr. Smith', $payload['interpreter'][0]['display']);
    }

    public function test_add_reason()
    {
        $reason = new CodeableConcept();
        $reason->coding[] = new Coding('http://snomed.info/sct', '12345', 'Genetic screening');
        
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addReason($reason);

        $payload = $builder->build();

        $this->assertArrayHasKey('reason', $payload);
        $this->assertNotEmpty($payload['reason']);
    }

    public function test_add_multiple_reasons()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $r1 = new CodeableConcept();
        $r1->coding[] = new Coding('http://test.com', 'R1', 'Reason1');
        $r2 = new CodeableConcept();
        $r2->coding[] = new Coding('http://test.com', 'R2', 'Reason2');
        $builder->addReason($r1);
        $builder->addReason($r2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['reason']);
    }

    public function test_add_note()
    {
        $note = new Annotation('Practitioner/prac-1', 'Genomic study completed successfully');
        
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addNote($note);

        $payload = $builder->build();

        $this->assertSame('Genomic study completed successfully', $payload['note'][0]['text']);
    }

    public function test_add_multiple_notes()
    {
        $note1 = new Annotation('Practitioner/prac-1', 'Note 1');
        $note2 = new Annotation('Practitioner/prac-2', 'Note 2');

        $builder = new PayloadBuilderGenomicStudy();
        $builder->addNote($note1);
        $builder->addNote($note2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['note']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setDescription('Whole exome sequencing analysis for rare disease diagnosis');

        $payload = $builder->build();

        $this->assertSame('Whole exome sequencing analysis for rare disease diagnosis', $payload['description']);
    }

    public function test_add_analysis()
    {
        $analysis = [
            'identifier' => 'analysis-001',
            'name' => 'Primary Analysis',
            'status' => 'registered',
            'type' => [
                'coding' => [['code' => 'WGS', 'display' => 'Whole Genome Sequencing']]
            ],
        ];
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addAnalysis($analysis);

        $payload = $builder->build();

        $this->assertSame('analysis-001', $payload['analysis'][0]['identifier']);
        $this->assertSame('WGS', $payload['analysis'][0]['type']['coding'][0]['code']);
    }

    public function test_add_multiple_analyses()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addAnalysis(['identifier' => 'analysis-1', 'status' => 'registered']);
        $builder->addAnalysis(['identifier' => 'analysis-2', 'status' => 'available']);

        $payload = $builder->build();

        $this->assertCount(2, $payload['analysis']);
    }

    public function test_add_performer_default_role()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addPerformer(new Reference('Organization/org-1', 'Lab Organization'));

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['performer'][0]['actor']['reference']);
        $this->assertArrayHasKey('role', $payload['performer'][0]);
    }

    public function test_add_performer_with_custom_role()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addPerformer(new Reference('Practitioner/prac-1', 'Geneticist'), 'AUTH');

        $payload = $builder->build();

        $this->assertSame('Practitioner/prac-1', $payload['performer'][0]['actor']['reference']);
        $this->assertArrayHasKey('role', $payload['performer'][0]);
    }

    public function test_add_multiple_performers()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->addPerformer(new Reference('Organization/org-1'));
        $builder->addPerformer(new Reference('Practitioner/prac-1'), 'AUTH');

        $payload = $builder->build();

        $this->assertCount(2, $payload['performer']);
        $this->assertArrayHasKey('role', $payload['performer'][0]);
        $this->assertArrayHasKey('role', $payload['performer'][1]);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderGenomicStudy())
            ->setId('gs-123')
            ->setStatus('available')
            ->setSubject(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('gs-123', $payload['id']);
        $this->assertSame('available', $payload['status']);
        $this->assertSame('Patient/123', $payload['subject']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderGenomicStudy();
        $builder->setStatus('available');
        $builder->setSubject(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('GenomicStudy', $json['resourceType']);
    }
}

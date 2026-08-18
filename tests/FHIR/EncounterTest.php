<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEncounter;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

class EncounterTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '185349003', 'Encounter for check up');

        $builder = new PayloadBuilderEncounter();
        $builder->setId('enc-001');
        $builder->addIdentifier(new Identifier('http://sys.com', '12345'));
        $builder->setStatus('finished');
        $builder->setClass(new Coding('http://terminology.hl7.org/CodeSystem/v3-ActCode', 'AMB', 'ambulatory'));
        $builder->addType($type);
        $builder->setSubject(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setPeriod(new Period('2022-06-14T09:00:00+00:00', '2022-06-14T10:00:00+00:00'));
        $builder->setServiceProvider(new Reference('Organization/org-001'));

        $payload = $builder->build();

        $this->assertSame('Encounter', $payload['resourceType']);
        $this->assertSame('enc-001', $payload['id']);
        $this->assertSame('finished', $payload['status']);
        $this->assertSame('AMB', $payload['class']['code']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setId('custom-enc-id');

        $payload = $builder->build();

        $this->assertSame('custom-enc-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setStatus('in-progress');

        $payload = $builder->build();

        $this->assertSame('in-progress', $payload['status']);
    }

    public function test_set_class()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setClass(new Coding('http://terminology.hl7.org/CodeSystem/v3-ActCode', 'IMP', 'inpatient'));

        $payload = $builder->build();

        $this->assertSame('IMP', $payload['class']['code']);
        $this->assertSame('inpatient', $payload['class']['display']);
    }

    public function test_add_type()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '12345', 'Checkup');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addType($type);

        $payload = $builder->build();

        $this->assertArrayHasKey('type', $payload);
        $this->assertNotEmpty($payload['type']);
    }

    public function test_add_multiple_types()
    {
        $builder = new PayloadBuilderEncounter();
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
        $builder = new PayloadBuilderEncounter();
        $builder->setSubject(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['subject']['reference']);
        $this->assertSame('Test Patient', $payload['subject']['display']);
    }

    public function test_add_participant_with_individual_only()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addParticipant(new Reference('Practitioner/prac-1'));

        $payload = $builder->build();

        $this->assertSame('Practitioner/prac-1', $payload['participant'][0]['individual']['reference']);
    }

    public function test_add_participant_with_type()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v2-0046', 'PPRF', 'primary performer');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addParticipant(new Reference('Practitioner/prac-1'), $type);

        $payload = $builder->build();

        $this->assertArrayHasKey('type', $payload['participant'][0]);
    }

    public function test_add_participant_with_period()
    {
        $period = new Period('2022-06-14T09:00:00+00:00', '2022-06-14T10:00:00+00:00');
        $builder = new PayloadBuilderEncounter();
        $builder->addParticipant(new Reference('Practitioner/prac-1'), null, $period);

        $payload = $builder->build();

        $this->assertArrayHasKey('period', $payload['participant'][0]);
    }

    public function test_add_multiple_participants()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addParticipant(new Reference('Practitioner/prac-1'));
        $builder->addParticipant(new Reference('Practitioner/prac-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['participant']);
    }

    public function test_add_location_with_reference()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addLocation(new Reference('Location/loc-1'));

        $payload = $builder->build();

        $this->assertSame('Location/loc-1', $payload['location'][0]['location']['reference']);
    }

    public function test_add_location_with_status()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addLocation(new Reference('Location/loc-1'), 'active');

        $payload = $builder->build();

        $this->assertSame('active', $payload['location'][0]['status']);
    }

    public function test_add_location_with_physical_type()
    {
        $physicalType = new CodeableConcept();
        $physicalType->coding[] = new Coding('http://snomed.info/sct', 'bd', 'Bed');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addLocation(new Reference('Location/loc-1'), null, $physicalType);

        $payload = $builder->build();

        $this->assertArrayHasKey('physicalType', $payload['location'][0]);
    }

    public function test_add_multiple_locations()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addLocation(new Reference('Location/loc-1'));
        $builder->addLocation(new Reference('Location/loc-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['location']);
    }

    public function test_set_period()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setPeriod(new Period('2022-06-14T09:00:00+00:00', '2022-06-14T10:00:00+00:00'));

        $payload = $builder->build();

        $this->assertSame('2022-06-14T09:00:00+00:00', $payload['period']['start']);
        $this->assertSame('2022-06-14T10:00:00+00:00', $payload['period']['end']);
    }

    public function test_set_service_provider()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setServiceProvider(new Reference('Organization/org-1'));

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['serviceProvider']['reference']);
    }

    public function test_add_diagnosis_with_condition_only()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addDiagnosis(new Reference('Condition/cond-1'));

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['diagnosis'][0]['condition']['reference']);
    }

    public function test_add_diagnosis_with_rank()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addDiagnosis(new Reference('Condition/cond-1'), 1);

        $payload = $builder->build();

        $this->assertSame(1, $payload['diagnosis'][0]['rank']);
    }

    public function test_add_diagnosis_with_use()
    {
        $use = new CodeableConcept();
        $use->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/diagnosis-role', 'AD', 'Admission diagnosis');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addDiagnosis(new Reference('Condition/cond-1'), null, $use);

        $payload = $builder->build();

        $this->assertArrayHasKey('use', $payload['diagnosis'][0]);
    }

    public function test_add_diagnosis_with_role()
    {
        $role = new CodeableConcept();
        $role->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/diagnosis-role', 'CC', 'Chief complaint');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addDiagnosis(new Reference('Condition/cond-1'), null, null, $role);

        $payload = $builder->build();

        $this->assertArrayHasKey('role', $payload['diagnosis'][0]);
    }

    public function test_add_diagnosis_with_all_params()
    {
        $use = new CodeableConcept();
        $use->coding[] = new Coding('http://test.com', 'AD', 'Admission');
        $role = new CodeableConcept();
        $role->coding[] = new Coding('http://test.com', 'CC', 'Chief');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addDiagnosis(
            new Reference('Condition/cond-1'),
            1,
            $use,
            $role
        );

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['diagnosis'][0]['condition']['reference']);
        $this->assertSame(1, $payload['diagnosis'][0]['rank']);
    }

    public function test_add_reason_code()
    {
        $reason = new CodeableConcept();
        $reason->coding[] = new Coding('http://snomed.info/sct', '12345', 'Checkup');
        
        $builder = new PayloadBuilderEncounter();
        $builder->addReasonCode($reason);

        $payload = $builder->build();

        $this->assertArrayHasKey('reasonCode', $payload);
        $this->assertNotEmpty($payload['reasonCode']);
    }

    public function test_add_multiple_reason_codes()
    {
        $builder = new PayloadBuilderEncounter();
        $r1 = new CodeableConcept();
        $r1->coding[] = new Coding('http://test.com', 'R1', 'Reason1');
        $r2 = new CodeableConcept();
        $r2->coding[] = new Coding('http://test.com', 'R2', 'Reason2');
        $builder->addReasonCode($r1);
        $builder->addReasonCode($r2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['reasonCode']);
    }

    public function test_add_reason_reference()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addReasonReference(new Reference('Condition/cond-1'));

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['reasonReference'][0]['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addExtension('http://example.com/ext', 'value', 'string');

        $payload = $builder->build();

        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_integer_value()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->addExtension('http://example.com/ext', 42, 'integer');

        $payload = $builder->build();

        $this->assertSame(42, $payload['extension'][0]['valueInteger']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderEncounter())
            ->setId('enc-123')
            ->setStatus('in-progress')
            ->setClass(new Coding('http://test.com', 'AMB', 'Ambulatory'))
            ->setSubject(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('enc-123', $payload['id']);
        $this->assertSame('in-progress', $payload['status']);
        $this->assertSame('AMB', $payload['class']['code']);
        $this->assertSame('Patient/123', $payload['subject']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderEncounter();
        $builder->setStatus('finished');
        $builder->setClass(new Coding('http://test.com', 'AMB', 'Ambulatory'));
        $builder->setSubject(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('Encounter', $json['resourceType']);
    }
}

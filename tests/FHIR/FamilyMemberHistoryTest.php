<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderFamilyMemberHistory;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class FamilyMemberHistoryTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $relationship = new CodeableConcept();
        $relationship->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v3-RoleCode', 'FTH', 'father');

        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setId('fmh-001');
        $builder->addIdentifier(new Identifier('http://sys.com', '12345'));
        $builder->setInstantiatesCanonical('http://example.org/family-history');
        $builder->setStatus('completed');
        $builder->setPatient(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setDate('2022-06-14T10:00:00+00:00');
        $builder->setName('John Doe');
        $builder->setRelationship($relationship);

        $payload = $builder->build();

        $this->assertSame('FamilyMemberHistory', $payload['resourceType']);
        $this->assertSame('fmh-001', $payload['id']);
        $this->assertSame('completed', $payload['status']);
        $this->assertSame('Patient/100000030009', $payload['patient']['reference']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setId('custom-fmh-id');

        $payload = $builder->build();

        $this->assertSame('custom-fmh-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_instantiates_canonical()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setInstantiatesCanonical('http://example.org/family-history');

        $payload = $builder->build();

        $this->assertSame('http://example.org/family-history', $payload['instantiatesCanonical']);
    }

    public function test_set_status_valid_values()
    {
        $validStatuses = ['partial', 'completed', 'entered-in-error', 'health-unknown'];

        foreach ($validStatuses as $status) {
            $builder = new PayloadBuilderFamilyMemberHistory();
            $builder->setStatus($status);
            $payload = $builder->build();
            $this->assertSame($status, $payload['status'], "Status '{$status}' should be valid");
        }
    }

    public function test_set_status_invalid_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status');

        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setStatus('invalid-status');
    }

    public function test_set_data_absent_reason()
    {
        $reason = new CodeableConcept();
        $reason->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/data-absent-reason', 'unknown', 'Unknown');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDataAbsentReason($reason);

        $payload = $builder->build();

        $this->assertArrayHasKey('dataAbsentReason', $payload);
    }

    public function test_set_patient()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setPatient(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['patient']['reference']);
        $this->assertSame('Test Patient', $payload['patient']['display']);
    }

    public function test_set_date()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDate('2022-06-14T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T10:00:00+00:00', $payload['date']);
    }

    public function test_set_name()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setName('John Doe');

        $payload = $builder->build();

        $this->assertSame('John Doe', $payload['name']);
    }

    public function test_set_name_null_does_not_add()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setName(null);

        $payload = $builder->build();

        $this->assertArrayNotHasKey('name', $payload);
    }

    public function test_set_relationship()
    {
        $rel = new CodeableConcept();
        $rel->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v3-RoleCode', 'MTH', 'mother');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setRelationship($rel);

        $payload = $builder->build();

        $this->assertArrayHasKey('relationship', $payload);
    }

    public function test_set_sex()
    {
        $sex = new CodeableConcept();
        $sex->coding[] = new Coding('http://hl7.org/fhir/administrative-gender', 'female', 'Female');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setSex($sex);

        $payload = $builder->build();

        $this->assertArrayHasKey('sex', $payload);
    }

    public function test_set_born_period()
    {
        $period = new CodeableConcept();
        $period->coding[] = new Coding('http://test.com', '1950', '1950s');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setBornPeriod($period);

        $payload = $builder->build();

        $this->assertArrayHasKey('bornPeriod', $payload);
    }

    public function test_set_born_date()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setBornDate('1960-05-15');

        $payload = $builder->build();

        $this->assertSame('1960-05-15', $payload['bornDate']);
    }

    public function test_set_born_string()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setBornString('Around 1960');

        $payload = $builder->build();

        $this->assertSame('Around 1960', $payload['bornString']);
    }

    public function test_set_age_age()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setAgeAge(45, 'years');

        $payload = $builder->build();

        $this->assertSame(45, $payload['ageAge']);
        $this->assertSame('years', $payload['ageUnit']);
    }

    public function test_set_age_range()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setAgeRange(40, '35', '45');

        $payload = $builder->build();

        $this->assertSame(40, $payload['ageRange']['value']);
        $this->assertSame('35', $payload['ageRange']['low']['value']);
        $this->assertSame('45', $payload['ageRange']['high']['value']);
    }

    public function test_set_age_string()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setAgeString('Middle aged');

        $payload = $builder->build();

        $this->assertSame('Middle aged', $payload['ageString']);
    }

    public function test_set_deceased_boolean_true()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedBoolean(true);

        $payload = $builder->build();

        $this->assertTrue($payload['deceasedBoolean']);
    }

    public function test_set_deceased_boolean_false()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedBoolean(false);

        $payload = $builder->build();

        $this->assertFalse($payload['deceasedBoolean']);
    }

    public function test_set_deceased_age()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedAge(70, 'years');

        $payload = $builder->build();

        $this->assertSame(70, $payload['deceasedAge']);
        $this->assertSame('years', $payload['deceasedAgeUnit']);
    }

    public function test_set_deceased_range()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedRange(65, '60', '70');

        $payload = $builder->build();

        $this->assertSame(65, $payload['deceasedRange']['value']);
        $this->assertSame('60', $payload['deceasedRange']['low']['value']);
        $this->assertSame('70', $payload['deceasedRange']['high']['value']);
    }

    public function test_set_deceased_date()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedDate('2020-06-15');

        $payload = $builder->build();

        $this->assertSame('2020-06-15', $payload['deceasedDate']);
    }

    public function test_set_deceased_string()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedString('Died in old age');

        $payload = $builder->build();

        $this->assertSame('Died in old age', $payload['deceasedString']);
    }

    public function test_set_deceased_codeable_concept()
    {
        $deceased = new CodeableConcept();
        $deceased->coding[] = new Coding('http://snomed.info/sct', '399307007', 'Dead');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setDeceasedCodeableConcept($deceased);

        $payload = $builder->build();

        $this->assertArrayHasKey('deceasedCodeableConcept', $payload);
    }

    public function test_set_reason_code()
    {
        $reason = new CodeableConcept();
        $reason->coding[] = new Coding('http://snomed.info/sct', '12345', 'Heart disease');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setReasonCode($reason);

        $payload = $builder->build();

        $this->assertArrayHasKey('reasonCode', $payload);
    }

    public function test_add_reason_code()
    {
        $reason = new CodeableConcept();
        $reason->coding[] = new Coding('http://snomed.info/sct', '12345', 'Heart disease');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addReasonCode($reason);

        $payload = $builder->build();

        $this->assertArrayHasKey('reasonCode', $payload);
        $this->assertNotEmpty($payload['reasonCode']);
    }

    public function test_add_multiple_reason_codes()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
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
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addReasonReference(new Reference('Condition/cond-1', 'Heart Condition'));

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['reasonReference'][0]['reference']);
        $this->assertSame('Heart Condition', $payload['reasonReference'][0]['display']);
    }

    public function test_add_multiple_reason_references()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addReasonReference(new Reference('Condition/cond-1'));
        $builder->addReasonReference(new Reference('Observation/obs-1'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['reasonReference']);
    }

    public function test_set_note()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setNote('Patient reported family history during consultation');

        $payload = $builder->build();

        $this->assertSame('Patient reported family history during consultation', $payload['note'][0]['text']);
    }

    public function test_add_condition_with_code_only()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '38341003', 'Hypertension');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addCondition($code);

        $payload = $builder->build();

        $this->assertArrayHasKey('condition', $payload);
        $this->assertNotEmpty($payload['condition']);
    }

    public function test_add_condition_with_onset_string()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '38341003', 'Hypertension');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addCondition($code, 'Age 50');

        $payload = $builder->build();

        $this->assertSame('Age 50', $payload['condition'][0]['onsetString']);
    }

    public function test_add_condition_with_outcome()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '38341003', 'Hypertension');
        $outcome = new CodeableConcept();
        $outcome->coding[] = new Coding('http://snomed.info/sct', '399307007', 'Dead');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addCondition($code, null, $outcome);

        $payload = $builder->build();

        $this->assertArrayHasKey('outcome', $payload['condition'][0]);
    }

    public function test_add_condition_with_contributed_to_death()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '38341003', 'Hypertension');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addCondition($code, null, null, true);

        $payload = $builder->build();

        $this->assertTrue($payload['condition'][0]['contributedToDeath']);
    }

    public function test_add_condition_with_all_params()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '38341003', 'Hypertension');
        $outcome = new CodeableConcept();
        $outcome->coding[] = new Coding('http://snomed.info/sct', '399307007', 'Dead');
        
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->addCondition($code, 'Age 50', $outcome, true);

        $payload = $builder->build();

        $this->assertArrayHasKey('condition', $payload);
        $this->assertSame('Age 50', $payload['condition'][0]['onsetString']);
        $this->assertArrayHasKey('outcome', $payload['condition'][0]);
        $this->assertTrue($payload['condition'][0]['contributedToDeath']);
    }

    public function test_add_multiple_conditions()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $c1 = new CodeableConcept();
        $c1->coding[] = new Coding('http://test.com', 'C1', 'Condition1');
        $c2 = new CodeableConcept();
        $c2->coding[] = new Coding('http://test.com', 'C2', 'Condition2');
        $builder->addCondition($c1);
        $builder->addCondition($c2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['condition']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderFamilyMemberHistory())
            ->setId('fmh-123')
            ->setStatus('completed')
            ->setPatient(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('fmh-123', $payload['id']);
        $this->assertSame('completed', $payload['status']);
        $this->assertSame('Patient/123', $payload['patient']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderFamilyMemberHistory();
        $builder->setStatus('completed');
        $builder->setPatient(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('FamilyMemberHistory', $json['resourceType']);
    }
}

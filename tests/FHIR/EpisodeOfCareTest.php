<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEpisodeOfCare;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

class EpisodeOfCareTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '390906007', 'Follow-up');

        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setId('eoc-001');
        $builder->addIdentifier(new Identifier('http://sys.com', '12345'));
        $builder->setStatus('active');
        $builder->addStatusHistory('planned', '2022-06-01', '2022-06-14');
        $builder->setPatient(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setManagingOrganization(new Reference('Organization/org-001'));
        $builder->addType($type);
        $builder->setPeriod(new Period('2022-06-01T00:00:00+00:00', '2022-12-31T23:59:59+00:00'));

        $payload = $builder->build();

        $this->assertSame('EpisodeOfCare', $payload['resourceType']);
        $this->assertSame('eoc-001', $payload['id']);
        $this->assertSame('active', $payload['status']);
        $this->assertSame('Patient/100000030009', $payload['patient']['reference']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setId('custom-eoc-id');

        $payload = $builder->build();

        $this->assertSame('custom-eoc-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_status_valid()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setStatus('active');

        $payload = $builder->build();

        $this->assertSame('active', $payload['status']);
    }

    public function test_set_status_valid_values()
    {
        $validStatuses = ['planned', 'waitlist', 'active', 'onhold', 'finished', 'cancelled', 'entered-in-error'];

        foreach ($validStatuses as $status) {
            $builder = new PayloadBuilderEpisodeOfCare();
            $builder->setStatus($status);
            $payload = $builder->build();
            $this->assertSame($status, $payload['status'], "Status '{$status}' should be valid");
        }
    }

    public function test_set_status_invalid_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status');

        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setStatus('invalid-status');
    }

    public function test_add_status_history()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addStatusHistory('planned', '2022-06-01', '2022-06-14');

        $payload = $builder->build();

        $this->assertSame('planned', $payload['statusHistory'][0]['status']);
        $this->assertSame('2022-06-01', $payload['statusHistory'][0]['period']['start']);
        $this->assertSame('2022-06-14', $payload['statusHistory'][0]['period']['end']);
    }

    public function test_add_status_history_without_end()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addStatusHistory('active', '2022-06-14');

        $payload = $builder->build();

        $this->assertSame('active', $payload['statusHistory'][0]['status']);
        $this->assertSame('2022-06-14', $payload['statusHistory'][0]['period']['start']);
        $this->assertArrayNotHasKey('end', $payload['statusHistory'][0]['period']);
    }

    public function test_add_status_history_invalid_status_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addStatusHistory('invalid', '2022-06-01');
    }

    public function test_add_multiple_status_history()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addStatusHistory('planned', '2022-06-01', '2022-06-14');
        $builder->addStatusHistory('active', '2022-06-14', '2022-07-01');

        $payload = $builder->build();

        $this->assertCount(2, $payload['statusHistory']);
    }

    public function test_set_patient()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setPatient(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['patient']['reference']);
        $this->assertSame('Test Patient', $payload['patient']['display']);
    }

    public function test_set_managing_organization()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setManagingOrganization(new Reference('Organization/org-1', 'Health Org'));

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['managingOrganization']['reference']);
    }

    public function test_add_type()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://snomed.info/sct', '390906007', 'Follow-up');
        $type->text = 'Follow-up visit';
        
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addType($type);

        $payload = $builder->build();

        $this->assertArrayHasKey('type', $payload);
        $this->assertSame('Follow-up visit', $payload['type'][0]['text']);
    }

    public function test_add_multiple_types()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $type1 = new CodeableConcept();
        $type1->coding[] = new Coding('http://test.com', 'TYPE1', 'Type1');
        $type2 = new CodeableConcept();
        $type2->coding[] = new Coding('http://test.com', 'TYPE2', 'Type2');
        $builder->addType($type1);
        $builder->addType($type2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['type']);
    }

    public function test_set_period()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setPeriod(new Period('2022-06-01T00:00:00+00:00', '2022-12-31T23:59:59+00:00'));

        $payload = $builder->build();

        $this->assertSame('2022-06-01T00:00:00+00:00', $payload['period']['start']);
        $this->assertSame('2022-12-31T23:59:59+00:00', $payload['period']['end']);
    }

    public function test_add_diagnosis_with_condition_and_role()
    {
        $role = new CodeableConcept();
        $role->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/diagnosis-role', 'CC', 'Chief complaint');
        
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addDiagnosis(
            new Reference('Condition/cond-1'),
            $role
        );

        $payload = $builder->build();

        $this->assertSame('Condition/cond-1', $payload['diagnosis'][0]['condition']['reference']);
        $this->assertArrayHasKey('role', $payload['diagnosis'][0]);
    }

    public function test_add_diagnosis_with_rank()
    {
        $role = new CodeableConcept();
        $role->coding[] = new Coding('http://test.com', 'AD', 'Admission');
        
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addDiagnosis(
            new Reference('Condition/cond-1'),
            $role,
            1
        );

        $payload = $builder->build();

        $this->assertSame(1, $payload['diagnosis'][0]['rank']);
    }

    public function test_add_multiple_diagnoses()
    {
        $role1 = new CodeableConcept();
        $role1->coding[] = new Coding('http://test.com', 'CC', 'Chief');
        $role2 = new CodeableConcept();
        $role2->coding[] = new Coding('http://test.com', 'AD', 'Admission');
        
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addDiagnosis(new Reference('Condition/cond-1'), $role1);
        $builder->addDiagnosis(new Reference('Condition/cond-2'), $role2, 2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['diagnosis']);
        $this->assertArrayHasKey('rank', $payload['diagnosis'][1]);
    }

    public function test_add_referral_request()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addReferralRequest(new Reference('ServiceRequest/sr-1'));

        $payload = $builder->build();

        $this->assertSame('ServiceRequest/sr-1', $payload['referralRequest'][0]['reference']);
    }

    public function test_add_multiple_referral_requests()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addReferralRequest(new Reference('ServiceRequest/sr-1'));
        $builder->addReferralRequest(new Reference('ServiceRequest/sr-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['referralRequest']);
    }

    public function test_set_care_manager()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setCareManager(new Reference('Practitioner/prac-1', 'Dr. Smith'));

        $payload = $builder->build();

        $this->assertSame('Practitioner/prac-1', $payload['careManager']['reference']);
        $this->assertSame('Dr. Smith', $payload['careManager']['display']);
    }

    public function test_add_team()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addTeam(new Reference('CareTeam/team-1', 'Care Team 1'));

        $payload = $builder->build();

        $this->assertSame('CareTeam/team-1', $payload['team'][0]['reference']);
    }

    public function test_add_multiple_teams()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addTeam(new Reference('CareTeam/team-1'));
        $builder->addTeam(new Reference('CareTeam/team-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['team']);
    }

    public function test_add_account()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addAccount(new Reference('Account/acc-1'));

        $payload = $builder->build();

        $this->assertSame('Account/acc-1', $payload['account'][0]['reference']);
    }

    public function test_add_multiple_accounts()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->addAccount(new Reference('Account/acc-1'));
        $builder->addAccount(new Reference('Account/acc-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['account']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderEpisodeOfCare())
            ->setId('eoc-123')
            ->setStatus('active')
            ->setPatient(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('eoc-123', $payload['id']);
        $this->assertSame('active', $payload['status']);
        $this->assertSame('Patient/123', $payload['patient']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderEpisodeOfCare();
        $builder->setStatus('active');
        $builder->setPatient(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('EpisodeOfCare', $json['resourceType']);
    }
}

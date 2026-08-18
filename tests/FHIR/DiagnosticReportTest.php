<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderDiagnosticReport;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class DiagnosticReportTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $category = new CodeableConcept();
        $category->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v2-0074', 'LAB', 'Laboratory');

        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://loinc.org', '58410-2', 'Complete blood count');
        $code->text = 'Complete Blood Count';

        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setId('dr-001');
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/diagnostic-report', '12345'));
        $builder->setStatus('final');
        $builder->addCategory($category);
        $builder->setCode($code);
        $builder->setSubject(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setEncounter(new Reference('Encounter/enc-001'));
        $builder->setEffectiveDateTime('2022-06-14T10:30:00+00:00');
        $builder->setIssued('2022-06-14T11:00:00+00:00');
        $builder->addPerformer(new Reference('Organization/org-001', 'Lab Unit'));
        $builder->addResult(new Reference('Observation/obs-001'));
        $builder->addSpecimen(new Reference('Specimen/spec-001'));

        $conclusionCode = new CodeableConcept();
        $conclusionCode->coding[] = new Coding('http://snomed.info/sct', '12345', 'Normal');
        $builder->addConclusionCode($conclusionCode);
        $builder->setConclusion('All values within normal range');

        $payload = $builder->build();

        $this->assertSame('DiagnosticReport', $payload['resourceType']);
        $this->assertSame('dr-001', $payload['id']);
        $this->assertSame('final', $payload['status']);
        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
        $this->assertSame('12345', $payload['identifier'][0]['value']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setId('custom-id');

        $payload = $builder->build();

        $this->assertSame('custom-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
        $this->assertSame('val456', $payload['identifier'][1]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setStatus('preliminary');

        $payload = $builder->build();

        $this->assertSame('preliminary', $payload['status']);
    }

    public function test_add_category()
    {
        $cat = new CodeableConcept();
        $cat->coding[] = new Coding('http://test.com', 'RAD', 'Radiology');
        
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addCategory($cat);

        $payload = $builder->build();

        $this->assertArrayHasKey('category', $payload);
        $this->assertNotEmpty($payload['category']);
        $this->assertArrayHasKey('coding', $payload['category'][0]);
    }

    public function test_add_multiple_categories()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $cat1 = new CodeableConcept();
        $cat1->coding[] = new Coding('http://test.com', 'CAT1', 'Cat1');
        $cat2 = new CodeableConcept();
        $cat2->coding[] = new Coding('http://test.com', 'CAT2', 'Cat2');
        $builder->addCategory($cat1);
        $builder->addCategory($cat2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['category']);
    }

    public function test_set_code()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://loinc.org', '12345-6', 'Test');
        $code->text = 'Test Description';
        
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setCode($code);

        $payload = $builder->build();

        $this->assertArrayHasKey('code', $payload);
        $this->assertSame('Test Description', $payload['code']['text']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setSubject(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['subject']['reference']);
        $this->assertSame('Test Patient', $payload['subject']['display']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setEncounter(new Reference('Encounter/enc-1'));

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-1', $payload['encounter']['reference']);
    }

    public function test_set_effective_date_time()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setEffectiveDateTime('2022-06-14T10:30:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T10:30:00+00:00', $payload['effectiveDateTime']);
    }

    public function test_set_issued()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setIssued('2022-06-14T11:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T11:00:00+00:00', $payload['issued']);
    }

    public function test_add_performer()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addPerformer(new Reference('Practitioner/prac-1', 'Dr. Smith'));
        $builder->addPerformer(new Reference('Organization/org-1'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['performer']);
        $this->assertSame('Dr. Smith', $payload['performer'][0]['display']);
    }

    public function test_add_result()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addResult(new Reference('Observation/obs-1'));

        $payload = $builder->build();

        $this->assertSame('Observation/obs-1', $payload['result'][0]['reference']);
    }

    public function test_add_specimen()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addSpecimen(new Reference('Specimen/spec-1'));

        $payload = $builder->build();

        $this->assertSame('Specimen/spec-1', $payload['specimen'][0]['reference']);
    }

    public function test_add_conclusion_code()
    {
        $code = new CodeableConcept();
        $code->coding[] = new Coding('http://snomed.info/sct', '1234', 'Normal');
        
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addConclusionCode($code);

        $payload = $builder->build();

        $this->assertArrayHasKey('conclusionCode', $payload);
        $this->assertNotEmpty($payload['conclusionCode']);
    }

    public function test_set_conclusion()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setConclusion('Patient shows no abnormalities');

        $payload = $builder->build();

        $this->assertSame('Patient shows no abnormalities', $payload['conclusion']);
    }

    public function test_add_based_on()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addBasedOn(new Reference('ServiceRequest/sr-1'));

        $payload = $builder->build();

        $this->assertSame('ServiceRequest/sr-1', $payload['basedOn'][0]['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addExtension('http://example.com/ext', 'value', 'string');

        $payload = $builder->build();

        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_integer_value()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addExtension('http://example.com/ext', 42, 'integer');

        $payload = $builder->build();

        $this->assertSame(42, $payload['extension'][0]['valueInteger']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderDiagnosticReport())
            ->setId('test-123')
            ->setStatus('final')
            ->setSubject(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('test-123', $payload['id']);
        $this->assertSame('final', $payload['status']);
        $this->assertSame('Patient/123', $payload['subject']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderDiagnosticReport();
        $builder->setStatus('final');
        $builder->setSubject(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('DiagnosticReport', $json['resourceType']);
    }
}

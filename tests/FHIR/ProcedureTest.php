<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderProcedure;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Reference;

class ProcedureTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderProcedure;

        $payload = $builder->build();

        $this->assertSame('Procedure', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->setId('proc-123');

        $payload = $builder->build();

        $this->assertSame('proc-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/procedure', 'PROC001');
        $builder = new PayloadBuilderProcedure;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('PROC001', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->setStatus('completed');

        $payload = $builder->build();

        $this->assertSame('completed', $payload['status']);
    }

    public function test_set_category()
    {
        $category = new CodeableConcept;
        $category->addCoding(new Coding('http://snomed.info/sct', '387713003', 'Surgical procedure'));

        $builder = new PayloadBuilderProcedure;
        $builder->setCategory($category);

        $payload = $builder->build();

        $this->assertSame('387713003', $payload['category']['coding'][0]['code']);
    }

    public function test_set_code()
    {
        $code = new CodeableConcept;
        $code->addCoding(new Coding('http://snomed.info/sct', '73761001', 'Appendectomy'));

        $builder = new PayloadBuilderProcedure;
        $builder->setCode($code);

        $payload = $builder->build();

        $this->assertSame('73761001', $payload['code']['coding'][0]['code']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderProcedure;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderProcedure;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_performed_date_time()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->setPerformedDateTime('2024-06-14T10:30:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-06-14T10:30:00+00:00', $payload['performedDateTime']);
    }

    public function test_set_performed_period()
    {
        $period = new Period('2024-06-14T10:00:00+00:00', '2024-06-14T12:00:00+00:00');
        $builder = new PayloadBuilderProcedure;
        $builder->setPerformedPeriod($period);

        $payload = $builder->build();

        $this->assertSame('2024-06-14T10:00:00+00:00', $payload['performedPeriod']['start']);
        $this->assertSame('2024-06-14T12:00:00+00:00', $payload['performedPeriod']['end']);
    }

    public function test_set_performed_string()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->setPerformedString('During afternoon rounds');

        $payload = $builder->build();

        $this->assertSame('During afternoon rounds', $payload['performedString']);
    }

    public function test_set_performed_age()
    {
        $age = new Range(new Quantity(5, 'a'));
        $builder = new PayloadBuilderProcedure;
        $builder->setPerformedAge($age);

        $payload = $builder->build();

        $this->assertArrayHasKey('performedAge', $payload);
        $this->assertEquals(5, $payload['performedAge']['low']['value']);
    }

    public function test_set_performed_range()
    {
        $range = new Range(new Quantity(3, 'a'), new Quantity(5, 'a'));
        $builder = new PayloadBuilderProcedure;
        $builder->setPerformedRange($range);

        $payload = $builder->build();

        $this->assertArrayHasKey('performedRange', $payload);
    }

    public function test_add_performer()
    {
        $actor = new Reference('Practitioner/N10000001', 'Dr. Siti');
        $builder = new PayloadBuilderProcedure;
        $builder->addPerformer($actor);

        $payload = $builder->build();

        $this->assertArrayHasKey('performer', $payload);
        $this->assertSame('Practitioner/N10000001', $payload['performer'][0]['actor']['reference']);
    }

    public function test_add_performer_with_function()
    {
        $actor = new Reference('Practitioner/N10000001');
        $function = new CodeableConcept;
        $function->addCoding(new Coding('http://snomed.info/sct', '112244003', 'Surgeon'));

        $builder = new PayloadBuilderProcedure;
        $builder->addPerformer($actor, $function);

        $payload = $builder->build();

        $this->assertSame('112244003', $payload['performer'][0]['function']['coding'][0]['code']);
    }

    public function test_add_performer_with_on_behalf_of()
    {
        $actor = new Reference('Practitioner/N10000001');
        $onBehalfOf = new Reference('Organization/org-123');

        $builder = new PayloadBuilderProcedure;
        $builder->addPerformer($actor, null, $onBehalfOf);

        $payload = $builder->build();

        $this->assertSame('Organization/org-123', $payload['performer'][0]['onBehalfOf']['reference']);
    }

    public function test_set_outcome()
    {
        $outcome = new CodeableConcept;
        $outcome->addCoding(new Coding('http://snomed.info/sct', '385669000', 'Successful'));

        $builder = new PayloadBuilderProcedure;
        $builder->setOutcome($outcome);

        $payload = $builder->build();

        $this->assertSame('385669000', $payload['outcome']['coding'][0]['code']);
    }

    public function test_add_report()
    {
        $report = new Reference('DiagnosticReport/dr-1');
        $builder = new PayloadBuilderProcedure;
        $builder->addReport($report);

        $payload = $builder->build();

        $this->assertArrayHasKey('report', $payload);
        $this->assertSame('DiagnosticReport/dr-1', $payload['report'][0]['reference']);
    }

    public function test_add_follow_up()
    {
        $followUp = new CodeableConcept;
        $followUp->addCoding(new Coding('http://snomed.info/sct', '181141009', 'Follow-up visit'));

        $builder = new PayloadBuilderProcedure;
        $builder->addFollowUp($followUp);

        $payload = $builder->build();

        $this->assertArrayHasKey('followUp', $payload);
        $this->assertSame('181141009', $payload['followUp'][0]['coding'][0]['code']);
    }

    public function test_add_note()
    {
        $note = new Annotation('Practitioner/N10000001', 'Prosedur berjalan lancar');
        $builder = new PayloadBuilderProcedure;
        $builder->addNote($note);

        $payload = $builder->build();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Prosedur berjalan lancar', $payload['note'][0]['text']);
    }

    public function test_add_focal_device()
    {
        $action = new CodeableConcept;
        $action->addCoding(new Coding('http://snomed.info/sct', '361230007', 'Implanted device'));

        $builder = new PayloadBuilderProcedure;
        $builder->addFocalDevice($action);

        $payload = $builder->build();

        $this->assertArrayHasKey('focalDevice', $payload);
        $this->assertSame('361230007', $payload['focalDevice'][0]['action']['coding'][0]['code']);
    }

    public function test_add_focal_device_with_device()
    {
        $action = new CodeableConcept;
        $action->addCoding(new Coding('http://snomed.info/sct', '361230007', 'Implanted'));
        $device = new Reference('Device/dev-1');

        $builder = new PayloadBuilderProcedure;
        $builder->addFocalDevice($action, null, $device);

        $payload = $builder->build();

        $this->assertSame('Device/dev-1', $payload['focalDevice'][0]['device']['reference']);
    }

    public function test_add_focal_device_with_manufacture_item()
    {
        $action = new CodeableConcept;
        $action->addCoding(new Coding('http://snomed.info/sct', '361230007', 'Implanted'));
        $manufactureItem = new Reference('Device/dev-2');

        $builder = new PayloadBuilderProcedure;
        $builder->addFocalDevice($action, $manufactureItem);

        $payload = $builder->build();

        $this->assertSame('Device/dev-2', $payload['focalDevice'][0]['manufactureItem']['reference']);
    }

    public function test_add_used_reference()
    {
        $reference = new Reference('Device/dev-1');
        $builder = new PayloadBuilderProcedure;
        $builder->addUsedReference($reference);

        $payload = $builder->build();

        $this->assertArrayHasKey('usedReference', $payload);
        $this->assertSame('Device/dev-1', $payload['usedReference'][0]['reference']);
    }

    public function test_add_used_reference_with_type()
    {
        $reference = new Reference('Device/dev-1');
        $type = new CodeableConcept;
        $type->addCoding(new Coding('http://snomed.info/sct', '66727007', 'Implant'));

        $builder = new PayloadBuilderProcedure;
        $builder->addUsedReference($reference, $type);

        $payload = $builder->build();

        $this->assertSame('66727007', $payload['usedReference'][0]['type']['coding'][0]['code']);
    }

    public function test_add_used_code()
    {
        $usedCode = new CodeableConcept;
        $usedCode->addCoding(new Coding('http://snomed.info/sct', '706172002', 'Artificial heart valve'));

        $builder = new PayloadBuilderProcedure;
        $builder->addUsedCode($usedCode);

        $payload = $builder->build();

        $this->assertArrayHasKey('usedCode', $payload);
        $this->assertSame('706172002', $payload['usedCode'][0]['coding'][0]['code']);
    }

    public function test_add_body_site()
    {
        $bodySite = new CodeableConcept;
        $bodySite->addCoding(new Coding('http://snomed.info/sct', '51185008', 'Thorax'));

        $builder = new PayloadBuilderProcedure;
        $builder->addBodySite($bodySite);

        $payload = $builder->build();

        $this->assertArrayHasKey('bodySite', $payload);
        $this->assertSame('51185008', $payload['bodySite'][0]['coding'][0]['code']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_boolean()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->addExtension('http://example.com/ext', true, 'boolean');

        $payload = $builder->build();

        $this->assertTrue($payload['extension'][0]['valueBoolean']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderProcedure;
        $result = $builder->setId('proc-1')
                          ->setStatus('completed')
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderProcedure::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderProcedure;
        $builder->setId('proc-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('status', $payload);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderObservation;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Ratio;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\SampledData;
use Satusehat\Integration\DataType\Timing;

class ObservationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderObservation;
        $this->assertSame('Observation', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setId('obs-001')->build();
        $this->assertSame('obs-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderObservation;
        $id = new Identifier('http://sys-ids.kemkes.go.id/observation', 'OBS-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('OBS-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setStatus('final')->build();
        $this->assertSame('final', $result['status']);
    }

    public function test_add_category()
    {
        $builder = new PayloadBuilderObservation;
        $category = new CodeableConcept();
        $category->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/observation-category', 'laboratory', 'Laboratory'));
        $result = $builder->addCategory($category)->build();
        $this->assertSame('laboratory', $result['category'][0]['coding'][0]->code);
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderObservation;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://loinc.org', '2345-7', 'Glucose'));
        $result = $builder->setCode($code)->build();
        $this->assertSame('2345-7', $result['code']['coding'][0]->code);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderObservation;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderObservation;
        $encounter = new Reference('Encounter/enc-001');
        $result = $builder->setEncounter($encounter)->build();
        $this->assertSame('Encounter/enc-001', $result['encounter']['reference']);
    }

    public function test_set_effective_date_time()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setEffectiveDateTime('2024-01-15T10:00:00+00:00')->build();
        $this->assertSame('2024-01-15T10:00:00+00:00', $result['effectiveDateTime']);
    }

    public function test_set_effective_period()
    {
        $builder = new PayloadBuilderObservation;
        $period = new Period('2024-01-15T08:00:00+00:00', '2024-01-15T08:30:00+00:00');
        $result = $builder->setEffectivePeriod($period)->build();
        $this->assertSame('2024-01-15T08:00:00+00:00', $result['effectivePeriod']['start']);
    }

    public function test_set_effective_instant()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setEffectiveInstant('2024-01-15T10:00:00+00:00')->build();
        $this->assertSame('2024-01-15T10:00:00+00:00', $result['effectiveInstant']);
    }

    public function test_set_effective_timing()
    {
        $builder = new PayloadBuilderObservation;
        $timing = new Timing();
        $result = $builder->setEffectiveTiming($timing)->build();
        // Empty timing returns empty array, just verify no error
        $this->assertIsArray($result);
    }

    public function test_set_value_quantity()
    {
        $builder = new PayloadBuilderObservation;
        $value = new Quantity(120.0, 'mg/dL', 'http://unitsofmeasure.org', 'http://unitsofmeasure.org', 'mg/dL');
        $result = $builder->setValueQuantity($value)->build();
        $this->assertSame(120.0, $result['valueQuantity']['value']);
    }

    public function test_set_value_codeable_concept()
    {
        $builder = new PayloadBuilderObservation;
        $value = new CodeableConcept();
        $value->addCoding(new Coding('http://snomed.info/sct', 'some-code', 'Some display'));
        $result = $builder->setValueCodeableConcept($value)->build();
        $this->assertSame('some-code', $result['valueCodeableConcept']['coding'][0]->code);
    }

    public function test_set_value_string()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setValueString('Positive')->build();
        $this->assertSame('Positive', $result['valueString']);
    }

    public function test_set_value_boolean()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setValueBoolean(true)->build();
        $this->assertTrue($result['valueBoolean']);
    }

    public function test_set_value_integer()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setValueInteger(42)->build();
        $this->assertSame(42, $result['valueInteger']);
    }

    public function test_set_value_range()
    {
        $builder = new PayloadBuilderObservation;
        $low = new Quantity(70.0, 'mg/dL');
        $high = new Quantity(100.0, 'mg/dL');
        $value = new Range($low, $high);
        $result = $builder->setValueRange($value)->build();
        $this->assertArrayHasKey('valueRange', $result);
    }

    public function test_set_value_ratio()
    {
        $builder = new PayloadBuilderObservation;
        $numerator = new Quantity(1.0, 'mmol/L');
        $denominator = new Quantity(1.0, 'L');
        $value = new Ratio($numerator, $denominator);
        $result = $builder->setValueRatio($value)->build();
        $this->assertArrayHasKey('valueRatio', $result);
    }

    public function test_set_value_time()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setValueTime('10:30:00')->build();
        $this->assertSame('10:30:00', $result['valueTime']);
    }

    public function test_set_value_date_time()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->setValueDateTime('2024-01-15')->build();
        $this->assertSame('2024-01-15', $result['valueDateTime']);
    }

    public function test_add_interpretation()
    {
        $builder = new PayloadBuilderObservation;
        $interpretation = new CodeableConcept();
        $interpretation->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation', 'H', 'High'));
        $result = $builder->addInterpretation($interpretation)->build();
        $this->assertSame('H', $result['interpretation'][0]['coding'][0]->code);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderObservation;
        $note = new Annotation('This is a clinical note');
        $result = $builder->addNote($note)->build();
        $this->assertNotEmpty($result['note']);
    }

    public function test_add_body_site()
    {
        $builder = new PayloadBuilderObservation;
        $bodySite = new CodeableConcept();
        $bodySite->addCoding(new Coding('http://snomed.info/sct', '344001', 'Mouth'));
        $result = $builder->addBodySite($bodySite)->build();
        $this->assertSame('344001', $result['bodySite'][0]['coding'][0]->code);
    }

    public function test_set_method()
    {
        $builder = new PayloadBuilderObservation;
        $method = new CodeableConcept();
        $method->addCoding(new Coding('http://snomed.info/sct', '271865001', 'Immunofluorescence assay'));
        $result = $builder->setMethod($method)->build();
        $this->assertSame('271865001', $result['method']['coding'][0]->code);
    }

    public function test_set_specimen()
    {
        $builder = new PayloadBuilderObservation;
        $specimen = new Reference('Specimen/spec-001', 'Blood sample');
        $result = $builder->setSpecimen($specimen)->build();
        $this->assertSame('Specimen/spec-001', $result['specimen']['reference']);
    }

    public function test_set_device()
    {
        $builder = new PayloadBuilderObservation;
        $device = new Reference('Device/dev-001', 'Glucose meter');
        $result = $builder->setDevice($device)->build();
        $this->assertSame('Device/dev-001', $result['device']['reference']);
    }

    public function test_add_reference_range()
    {
        $builder = new PayloadBuilderObservation;
        $low = new Quantity(70.0, 'mg/dL');
        $high = new Quantity(100.0, 'mg/dL');
        $result = $builder->addReferenceRange($low, $high, null, 'Normal range')->build();
        $this->assertSame(70.0, $result['referenceRange'][0]['low']['value']);
        $this->assertSame(100.0, $result['referenceRange'][0]['high']['value']);
        $this->assertSame('Normal range', $result['referenceRange'][0]['text']);
    }

    public function test_add_component_quantity()
    {
        $builder = new PayloadBuilderObservation;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://loinc.org', '2345-7', 'Glucose'));
        $value = new Quantity(120.0, 'mg/dL');

        $result = $builder->addComponent($code, $value)->build();
        $this->assertSame('2345-7', $result['component'][0]['code']['coding'][0]->code);
        $this->assertSame(120.0, $result['component'][0]['valueQuantity']['value']);
    }

    public function test_add_component_string()
    {
        $builder = new PayloadBuilderObservation;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://loinc.org', '12345-6', 'Comment'));
        $result = $builder->addComponent($code, 'This is a comment')->build();
        $this->assertSame('This is a comment', $result['component'][0]['valueString']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderObservation;
        $result = $builder->addExtension('http://example.org/ext', 'value', 'String')->build();
        $this->assertSame('http://example.org/ext', $result['extension'][0]['url']);
        $this->assertSame('value', $result['extension'][0]['valueString']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderObservation;
        $subject = new Reference('Patient/100000030009');

        $builder->setId('obs-002')
            ->setStatus('final')
            ->setSubject($subject);

        $this->assertIsArray($builder->build());
        $this->assertSame('obs-002', $builder->build()['id']);
    }
}

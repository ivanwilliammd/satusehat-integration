<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderSpecimen;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Coding;

class SpecimenTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderSpecimen;
        $this->assertSame('Specimen', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->setId('spec-001')->build();
        $this->assertSame('spec-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderSpecimen;
        $id = new Identifier('http://sys-ids.kemkes.go.id/specimen', 'SPEC-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/specimen', $result['identifier'][0]['system']);
        $this->assertSame('SPEC-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->setStatus('available')->build();
        $this->assertSame('available', $result['status']);
    }

    public function test_set_type()
    {
        $builder = new PayloadBuilderSpecimen;
        $type = new CodeableConcept();
        $type->addCoding(new Coding('http://snomed.info/sct', '119364003', 'Serum sample'));
        $result = $builder->setType($type)->build();
        $this->assertSame('119364003', $result['type']['coding'][0]['code']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderSpecimen;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }

    public function test_set_received_time()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->setReceivedTime('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['receivedTime']);
    }

    public function test_add_request()
    {
        $builder = new PayloadBuilderSpecimen;
        $request = new Reference('ServiceRequest/sr-001');
        $result = $builder->addRequest($request)->build();
        $this->assertSame('ServiceRequest/sr-001', $result['request'][0]['reference']);
    }

    public function test_set_collected_date_time()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->setCollectedDateTime('2024-01-15T09:00:00+00:00')->build();
        $this->assertSame('2024-01-15T09:00:00+00:00', $result['collection']['collectedDateTime']);
    }

    public function test_set_collector()
    {
        $builder = new PayloadBuilderSpecimen;
        $collector = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->setCollector($collector)->build();
        $this->assertSame('Practitioner/N10000001', $result['collection']['collector']['reference']);
    }

    public function test_set_fasting_status_codeable_concept()
    {
        $builder = new PayloadBuilderSpecimen;
        $fastingStatus = new CodeableConcept();
        $fastingStatus->addCoding(new Coding('http://snomed.info/sct', '15170003', 'Fasting'));
        $result = $builder->setFastingStatusCodeableConcept($fastingStatus)->build();
        $this->assertSame('15170003', $result['collection']['fastingStatusCodeableConcept']['coding'][0]['code']);
    }

    public function test_set_method()
    {
        $builder = new PayloadBuilderSpecimen;
        $method = new CodeableConcept();
        $method->addCoding(new Coding('http://snomed.info/sct', '27845005', 'Venipuncture'));
        $result = $builder->setMethod($method)->build();
        $this->assertSame('27845005', $result['collection']['method']['coding'][0]['code']);
    }

    public function test_set_quantity()
    {
        $builder = new PayloadBuilderSpecimen;
        $qty = new Quantity(5.0, null, 'mL', 'http://unitsofmeasure.org', 'mL');
        $result = $builder->setQuantity($qty)->build();
        $this->assertSame(5.0, $result['collection']['quantity']['value']);
        $this->assertSame('mL', $result['collection']['quantity']['unit']);
    }

    public function test_set_body_site()
    {
        $builder = new PayloadBuilderSpecimen;
        $bodySite = new CodeableConcept();
        $bodySite->addCoding(new Coding('http://snomed.info/sct', '49852007', 'Left antecubital fossa'));
        $result = $builder->setBodySite($bodySite)->build();
        $this->assertSame('49852007', $result['collection']['bodySite']['coding'][0]['code']);
    }

    public function test_add_condition()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->addCondition('Hemolyzed sample')->build();
        $this->assertSame('Hemolyzed sample', $result['condition'][0]['text']);
    }

    public function test_add_processing()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->addProcessing('2024-01-15T10:00:00+00:00')->build();
        $this->assertSame('2024-01-15T10:00:00+00:00', $result['processing'][0]['timeDateTime']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->addExtension('http://example.org/fhir/extensions/specimen-quality', 'high')->build();
        $this->assertSame('http://example.org/fhir/extensions/specimen-quality', $result['extension'][0]['url']);
        $this->assertSame('high', $result['extension'][0]['valueString']);
    }

    public function test_add_transported_time()
    {
        $builder = new PayloadBuilderSpecimen;
        $result = $builder->addTransportedTime('2024-01-15T11:00:00+00:00')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedTime', $result['extension'][0]['url']);
        $this->assertSame('2024-01-15T11:00:00+00:00', $result['extension'][0]['valueDateTime']);
    }

    public function test_add_transported_person()
    {
        $builder = new PayloadBuilderSpecimen;
        $telecom = [
            ['system' => 'phone', 'value' => '081234567890'],
        ];
        $result = $builder->addTransportedPerson('John Doe', $telecom)->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/TransportedPerson', $result['extension'][0]['url']);
        $this->assertSame('John Doe', $result['extension'][0]['valueContactDetail']['name']);
        $this->assertSame('081234567890', $result['extension'][0]['valueContactDetail']['telecom'][0]['value']);
    }

    public function test_add_received_person()
    {
        $builder = new PayloadBuilderSpecimen;
        $receivedPerson = new Reference('Practitioner/N10000002', 'Lab Receiver');
        $result = $builder->addReceivedPerson($receivedPerson)->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/ReceivedPerson', $result['extension'][0]['url']);
        $this->assertSame('Practitioner/N10000002', $result['extension'][0]['valueReference']['reference']);
    }

    public function test_add_multiple_identifiers()
    {
        $builder = new PayloadBuilderSpecimen;
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/specimen', 'SPEC-001'));
        $builder->addIdentifier(new Identifier('http://lab.example.org/barcode', 'BAR-12345'));
        $result = $builder->build();
        $this->assertCount(2, $result['identifier']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderSpecimen;
        $builder->setId('spec-002')
            ->setStatus('available');

        $this->assertIsArray($builder->build());
        $this->assertSame('Specimen', $builder->build()['resourceType']);
    }
}

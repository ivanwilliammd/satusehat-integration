<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderServiceRequest;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Coding;

class ServiceRequestTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderServiceRequest;
        $this->assertSame('ServiceRequest', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setId('sr-001')->build();
        $this->assertSame('sr-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderServiceRequest;
        $id = new Identifier('http://sys-ids.kemkes.go.id/servicerequest', 'SR-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/servicerequest', $result['identifier'][0]['system']);
        $this->assertSame('SR-001', $result['identifier'][0]['value']);
    }

    public function test_set_requisition()
    {
        $builder = new PayloadBuilderServiceRequest;
        $id = new Identifier('http://hospital.example.org/requisition', 'REQ-001');
        $result = $builder->setRequisition($id)->build();
        $this->assertSame('REQ-001', $result['requisition']['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_intent()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setIntent('order')->build();
        $this->assertSame('order', $result['intent']);
    }

    public function test_add_category()
    {
        $builder = new PayloadBuilderServiceRequest;
        $category = new CodeableConcept();
        $category->addCoding(new Coding('http://snomed.info/sct', '108252007', 'Laboratory procedure'));
        $result = $builder->addCategory($category)->build();
        $this->assertSame('108252007', $result['category'][0]['coding'][0]['code']);
    }

    public function test_set_priority()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setPriority('routine')->build();
        $this->assertSame('routine', $result['priority']);
    }

    public function test_set_do_not_perform()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setDoNotPerform(true)->build();
        $this->assertTrue($result['doNotPerform']);
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderServiceRequest;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://loinc.org', '24323-8', 'Glucose [Moles/volume] in Serum or Plasma'));
        $result = $builder->setCode($code)->build();
        $this->assertSame('24323-8', $result['code']['coding'][0]['code']);
    }

    public function test_set_quantity_quantity()
    {
        $builder = new PayloadBuilderServiceRequest;
        $qty = new Quantity(10.0, null, 'mL', 'http://unitsofmeasure.org', 'mL');
        $result = $builder->setQuantityQuantity($qty)->build();
        $this->assertSame(10.0, $result['quantityQuantity']['value']);
        $this->assertSame('mL', $result['quantityQuantity']['unit']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderServiceRequest;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderServiceRequest;
        $encounter = new Reference('Encounter/enc-001');
        $result = $builder->setEncounter($encounter)->build();
        $this->assertSame('Encounter/enc-001', $result['encounter']['reference']);
    }

    public function test_set_occurrence_date_time()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setOccurrenceDateTime('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['occurrenceDateTime']);
    }

    public function test_set_authored_on()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setAuthoredOn('2024-01-10T08:00:00+00:00')->build();
        $this->assertSame('2024-01-10T08:00:00+00:00', $result['authoredOn']);
    }

    public function test_set_requester()
    {
        $builder = new PayloadBuilderServiceRequest;
        $requester = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->setRequester($requester)->build();
        $this->assertSame('Practitioner/N10000001', $result['requester']['reference']);
    }

    public function test_add_performer()
    {
        $builder = new PayloadBuilderServiceRequest;
        $performer = new Reference('Practitioner/N10000002', 'Lab Technician');
        $result = $builder->addPerformer($performer)->build();
        $this->assertSame('Practitioner/N10000002', $result['performer'][0]['reference']);
    }

    public function test_add_reason_code()
    {
        $builder = new PayloadBuilderServiceRequest;
        $reasonCode = new CodeableConcept();
        $reasonCode->addCoding(new Coding('http://snomed.info/sct', '166072009', 'Blood glucose elevated'));
        $result = $builder->addReasonCode($reasonCode)->build();
        $this->assertSame('166072009', $result['reasonCode'][0]['coding'][0]['code']);
    }

    public function test_add_supporting_info()
    {
        $builder = new PayloadBuilderServiceRequest;
        $supportingInfo = new Reference('Observation/obs-001');
        $result = $builder->addSupportingInfo($supportingInfo)->build();
        $this->assertSame('Observation/obs-001', $result['supportingInfo'][0]['reference']);
    }

    public function test_add_specimen()
    {
        $builder = new PayloadBuilderServiceRequest;
        $specimen = new Reference('Specimen/spec-001');
        $result = $builder->addSpecimen($specimen)->build();
        $this->assertSame('Specimen/spec-001', $result['specimen'][0]['reference']);
    }

    public function test_add_note()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->addNote('Patient should fast for 12 hours before test')->build();
        $this->assertSame('Patient should fast for 12 hours before test', $result['note'][0]['text']);
    }

    public function test_set_patient_instruction()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->setPatientInstruction('Collect morning urine sample')->build();
        $this->assertSame('Collect morning urine sample', $result['patientInstruction']);
    }

    public function test_add_relevant_history()
    {
        $builder = new PayloadBuilderServiceRequest;
        $relevantHistory = new Reference('AuditEvent/audit-001');
        $result = $builder->addRelevantHistory($relevantHistory)->build();
        $this->assertSame('AuditEvent/audit-001', $result['relevantHistory'][0]['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderServiceRequest;
        $result = $builder->addExtension('http://example.org/fhir/extensions/specialty', 'cardiology')->build();
        $this->assertSame('http://example.org/fhir/extensions/specialty', $result['extension'][0]['url']);
        $this->assertSame('cardiology', $result['extension'][0]['valueString']);
    }

    public function test_add_multiple_categories()
    {
        $builder = new PayloadBuilderServiceRequest;
        $cat1 = new CodeableConcept();
        $cat1->addCoding(new Coding('http://snomed.info/sct', '108252007', 'Laboratory'));
        $cat2 = new CodeableConcept();
        $cat2->addCoding(new Coding('http://snomed.info/sct', '409063005', 'Pathology'));
        
        $builder->addCategory($cat1);
        $builder->addCategory($cat2);
        $result = $builder->build();
        $this->assertCount(2, $result['category']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderServiceRequest;
        $builder->setId('sr-002')
            ->setStatus('active')
            ->setIntent('order');

        $this->assertIsArray($builder->build());
        $this->assertSame('ServiceRequest', $builder->build()['resourceType']);
    }
}

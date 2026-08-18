<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMedicationAdministration;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

class MedicationAdministrationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $this->assertSame('MedicationAdministration', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $result = $builder->setId('medadm-001')->build();
        $this->assertSame('medadm-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $id = new Identifier('http://sys-ids.kemkes.go.id/medication-administration', 'MA-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('MA-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $result = $builder->setStatus('completed')->build();
        $this->assertSame('completed', $result['status']);
    }

    public function test_set_medication_reference()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $med = new Reference('Medication/med-001', 'Paracetamol 500mg');
        $result = $builder->setMedicationReference($med)->build();
        $this->assertSame('Medication/med-001', $result['medicationReference']['reference']);
        $this->assertSame('Paracetamol 500mg', $result['medicationReference']['display']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setSubject($subject)->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }

    public function test_set_context()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $ctx = new Reference('Encounter/enc-001', 'Kunjungan Rawat Inap');
        $result = $builder->setContext($ctx)->build();
        $this->assertSame('Encounter/enc-001', $result['context']['reference']);
    }

    public function test_set_effective_period()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $period = new Period('2024-01-15T08:00:00+00:00', '2024-01-15T08:30:00+00:00');
        $result = $builder->setEffectivePeriod($period)->build();
        $this->assertSame('2024-01-15T08:00:00+00:00', $result['effectivePeriod']['start']);
        $this->assertSame('2024-01-15T08:30:00+00:00', $result['effectivePeriod']['end']);
    }

    public function test_add_performer()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $perf = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->addPerformer($perf)->build();
        $this->assertSame('Practitioner/N10000001', $result['performer'][0]['actor']['reference']);
    }

    public function test_add_reason_code()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $reason = new CodeableConcept();
        $reason->addCoding(new Coding('http://snomed.info/sct', '282100009', 'Premedication given'));
        $result = $builder->addReasonCode($reason)->build();
        $this->assertSame('282100009', $result['reasonCode'][0]['coding'][0]['code']);
    }

    public function test_set_request()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $req = new Reference('MedicationRequest/mr-001');
        $result = $builder->setRequest($req)->build();
        $this->assertSame('MedicationRequest/mr-001', $result['request']['reference']);
    }

    public function test_set_dosage()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $dose = new Quantity(500.0, null, 'mg', 'http://unitsofmeasure.org', 'mg');
        $route = new CodeableConcept();
        $route->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v3-RouteOfAdministration', 'PO', 'Oral'));
        $site = new CodeableConcept();
        $site->addCoding(new Coding('http://snomed.info/sct', '344001', 'Mouth'));

        $result = $builder->setDosage($dose, $route, $site)->build();

        $this->assertSame(500.0, $result['dosage']['dose']['value']);
        $this->assertSame('PO', $result['dosage']['route']['coding'][0]['code']);
        $this->assertSame('344001', $result['dosage']['site']['coding'][0]['code']);
    }

    public function test_add_contained()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $containedMed = [
            'resourceType' => 'Medication',
            'id' => 'inline-med',
            'code' => [
                'coding' => [
                    ['system' => 'http://www.whocc.no/atc', 'code' => 'N02BE01', 'display' => 'Paracetamol']
                ]
            ]
        ];
        $result = $builder->addContained($containedMed)->build();
        $this->assertSame('inline-med', $result['contained'][0]['id']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderMedicationAdministration;
        $builder->setId('medadm-002')
            ->setStatus('completed')
            ->setSubject(new Reference('Patient/100000030009'));

        $this->assertIsArray($builder->build());
        $this->assertSame('medadm-002', $builder->build()['id']);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMedicationDispense;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class MedicationDispenseTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $this->assertSame('MedicationDispense', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setId('md-001')->build();
        $this->assertSame('md-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $id = new Identifier('http://sys-ids.kemkes.go.id/medication-dispense', 'MD-001');
        $result = $builder->addIdentifier($id->system, $id->value)->build();
        $this->assertSame('MD-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setStatus('completed')->build();
        $this->assertSame('completed', $result['status']);
    }

    public function test_set_medication_reference()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setMedicationReference('Medication/med-001', 'Paracetamol 500mg')->build();
        $this->assertSame('Medication/med-001', $result['medicationReference']['reference']);
        $this->assertSame('Paracetamol 500mg', $result['medicationReference']['display']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setSubject('Patient/100000030009', 'Budi Santoso')->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
        $this->assertSame('Budi Santoso', $result['subject']['display']);
    }

    public function test_set_context()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setContext('Encounter/enc-001')->build();
        $this->assertSame('Encounter/enc-001', $result['context']['reference']);
    }

    public function test_add_performer()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->addPerformer('Practitioner/N10000001', 'Dr. Smith')->build();
        $this->assertSame('Practitioner/N10000001', $result['performer'][0]['actor']['reference']);
    }

    public function test_set_location()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setLocation('loc-001', 'Apotek Utama')->build();
        $this->assertSame('Location/loc-001', $result['location']['reference']);
    }

    public function test_add_authorizing_prescription()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->addAuthorizingPrescription('mr-001')->build();
        $this->assertSame('MedicationRequest/mr-001', $result['authorizingPrescription'][0]['reference']);
    }

    public function test_set_category()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setCategory('outpatient', 'Outpatient')->build();
        $this->assertSame('outpatient', $result['category']['coding'][0]['code']);
    }

    public function test_set_quantity()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setQuantity(30.0, 'tab', 'http://snomed.info/sct')->build();
        $this->assertSame(30.0, $result['quantity']['value']);
        $this->assertSame('tab', $result['quantity']['unit']);
    }

    public function test_set_days_supply()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setDaysSupply(7.0, 'd', 'http://unitsofmeasure.org', 'days')->build();
        $this->assertSame(7.0, $result['daysSupply']['value']);
    }

    public function test_set_when_prepared()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setWhenPrepared('2024-01-15 08:00:00')->build();
        $this->assertNotEmpty($result['whenPrepared']);
    }

    public function test_set_when_handed_over()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setWhenHandedOver('2024-01-15 10:00:00')->build();
        $this->assertNotEmpty($result['whenHandedOver']);
    }

    public function test_add_dosage_instruction()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->addDosageInstruction(
            1,
            'After meals',
            500.0,
            'mg',
            'PO',
            'Oral',
            'TID',
            'Three times daily',
            '418290006',
            'Take with food'
        )->build();

        $this->assertSame(1, $result['dosageInstruction'][0]['sequence']);
        $this->assertSame(500.0, $result['dosageInstruction'][0]['doseAndRate'][0]['doseQuantity']['value']);
    }

    public function test_set_substitution()
    {
        $builder = new PayloadBuilderMedicationDispense;
        $result = $builder->setSubstitution(true, 'E', 'Substituted')->build();
        $this->assertTrue($result['substitution']['wasSubstituted']);
        $this->assertSame('E', $result['substitution']['type']['coding'][0]['code']);
    }

    public function test_add_contained()
    {
        $builder = new PayloadBuilderMedicationDispense;
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
        $builder = new PayloadBuilderMedicationDispense;
        $builder->setId('md-002')
            ->setStatus('completed')
            ->setSubject('Patient/100000030009', 'Budi Santoso');

        $this->assertIsArray($builder->build());
        $this->assertSame('md-002', $builder->build()['id']);
    }
}

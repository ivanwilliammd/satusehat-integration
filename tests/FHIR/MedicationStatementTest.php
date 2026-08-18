<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMedicationStatement;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class MedicationStatementTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $this->assertSame('MedicationStatement', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setId('ms-001')->build();
        $this->assertSame('ms-001', $result['id']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_add_status_reason()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->addStatusReason('282100009', 'Premedication', 'http://snomed.info/sct')->build();
        $this->assertSame('282100009', $result['statusReason'][0]['coding'][0]['code']);
    }

    public function test_set_medication_reference()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setMedicationReference('Medication/med-001', 'Paracetamol 500mg')->build();
        $this->assertSame('Medication/med-001', $result['medicationReference']['reference']);
        $this->assertSame('Paracetamol 500mg', $result['medicationReference']['display']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setSubject('100000030009', 'Budi Santoso')->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
        $this->assertSame('Budi Santoso', $result['subject']['display']);
    }

    public function test_set_context()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setContext('enc-001', 'Kunjungan Rawat Jalan')->build();
        $this->assertSame('Encounter/enc-001', $result['context']['reference']);
    }

    public function test_set_date_asserted()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setDateAsserted('2024-01-15 10:00:00')->build();
        $this->assertNotEmpty($result['dateAsserted']);
    }

    public function test_set_effective_date_time()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setEffectiveDateTime('2024-01-15 08:00:00')->build();
        $this->assertNotEmpty($result['effectiveDateTime']);
    }

    public function test_set_information_source()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->setInformationSource('100000030009', 'Budi Santoso')->build();
        $this->assertSame('Patient/100000030009', $result['informationSource']['reference']);
    }

    public function test_add_dosage_instruction()
    {
        $builder = new PayloadBuilderMedicationStatement;
        $result = $builder->addDosageInstruction('Paracetamol 500mg three times daily', 3, 1, 'd')->build();

        $this->assertSame('Paracetamol 500mg three times daily', $result['dosage'][0]['text']);
        $this->assertSame(3, $result['dosage'][0]['timing']['repeat']['frequency']);
        $this->assertEquals(1, $result['dosage'][0]['timing']['repeat']['period']);
        $this->assertSame('d', $result['dosage'][0]['timing']['repeat']['periodUnit']);
    }

    public function test_add_contained()
    {
        $builder = new PayloadBuilderMedicationStatement;
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
        $builder = new PayloadBuilderMedicationStatement;
        $builder->setId('ms-002')
            ->setStatus('completed')
            ->setSubject('100000030009', 'Budi Santoso');

        $this->assertIsArray($builder->build());
        $this->assertSame('ms-002', $builder->build()['id']);
    }
}

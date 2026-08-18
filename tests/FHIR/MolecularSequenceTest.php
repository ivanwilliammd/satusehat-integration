<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMolecularSequence;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Reference;

class MolecularSequenceTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $builder->setCoordinateSystem(1);
        $this->assertSame('MolecularSequence', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $result = $builder->setId('seq-001')->setCoordinateSystem(1)->build();
        $this->assertSame('seq-001', $result['id']);
    }

    public function test_set_text()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $result = $builder->setText('generated', '<div>Test sequence</div>')->setCoordinateSystem(1)->build();
        $this->assertSame('generated', $result['text']['status']);
        $this->assertSame('<div>Test sequence</div>', $result['text']['div']);
    }

    public function test_set_type_valid()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $result = $builder->setType('dna')->setCoordinateSystem(1)->build();
        $this->assertSame('dna', $result['type']);
    }

    public function test_set_type_invalid()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $this->expectException(\InvalidArgumentException::class);
        $builder->setType('invalid');
    }

    public function test_set_coordinate_system()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $result = $builder->setCoordinateSystem(1)->build();
        $this->assertSame(1, $result['coordinateSystem']);
    }

    public function test_set_patient()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $patient = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setPatient($patient)->setCoordinateSystem(1)->build();
        $this->assertSame('Patient/100000030009', $result['patient']['reference']);
    }

    public function test_set_specimen()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $specimen = new Reference('Specimen/spec-001', 'Blood Sample');
        $result = $builder->setSpecimen($specimen)->setCoordinateSystem(1)->build();
        $this->assertSame('Specimen/spec-001', $result['specimen']['reference']);
    }

    public function test_set_device()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $device = new Reference('Device/dev-001', 'Sequencer');
        $result = $builder->setDevice($device)->setCoordinateSystem(1)->build();
        $this->assertSame('Device/dev-001', $result['device']['reference']);
    }

    public function test_set_performer()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $performer = new Reference('Organization/org-001', 'Lab Corp');
        $result = $builder->setPerformer($performer)->setCoordinateSystem(1)->build();
        $this->assertSame('Organization/org-001', $result['performer']['reference']);
    }

    public function test_set_reference_seq()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $refSeqId = new CodeableConcept();
        $refSeqId->addCoding(new Coding('http://ncbi.nlm.nih.gov/nuccore', 'NC_000001', 'Chromosome 1'));

        $result = $builder->setReferenceSeq($refSeqId, 'watson', 100, 200, 'sense')->setCoordinateSystem(1)->build();

        $this->assertSame('watson', $result['referenceSeq']['strand']);
        $this->assertSame(100, $result['referenceSeq']['windowStart']);
        $this->assertSame(200, $result['referenceSeq']['windowEnd']);
    }

    public function test_add_variant()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $result = $builder->addVariant(12345, 12346, 'A', 'G')->setCoordinateSystem(1)->build();

        $this->assertSame(12345, $result['variant'][0]['start']);
        $this->assertSame(12346, $result['variant'][0]['end']);
        $this->assertSame('A', $result['variant'][0]['observedAllele']);
        $this->assertSame('G', $result['variant'][0]['referenceAllele']);
    }

    public function test_add_variant_with_pointer()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $pointer = new Reference('MolecularSequence/seq-002', 'Related variant');
        $result = $builder->addVariant(12345, 12346, 'A', 'G', $pointer)->setCoordinateSystem(1)->build();

        $this->assertSame('MolecularSequence/seq-002', $result['variant'][0]['variantPointer']['reference']);
    }

    public function test_build_without_coordinate_system_throws()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('MolecularSequence.coordinateSystem is required');
        $builder->build();
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderMolecularSequence;
        $patient = new Reference('Patient/100000030009');

        $builder->setId('seq-002')
            ->setType('dna')
            ->setCoordinateSystem(1)
            ->setPatient($patient);

        $this->assertIsArray($builder->build());
        $this->assertSame('seq-002', $builder->build()['id']);
    }
}

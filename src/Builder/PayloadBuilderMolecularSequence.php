<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Reference;

/**
 * MolecularSequence FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/molecularsequence.html
 */
class PayloadBuilderMolecularSequence extends Builder
{
    protected string $resourceType = 'MolecularSequence';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function setText(string $status, string $div): self
    {
        $this->set('text', [
            'status' => $status,
            'div' => $div,
        ]);
        return $this;
    }

    public function setType(string $type): self
    {
        $validTypes = ['aa', 'dna', 'rna'];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException('Invalid type: must be one of aa, dna, rna');
        }
        $this->set('type', $type);
        return $this;
    }

    public function setCoordinateSystem(int $coordinateSystem): self
    {
        $this->set('coordinateSystem', $coordinateSystem);
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setSpecimen(Reference $specimen): self
    {
        $this->set('specimen', $specimen->toArray());
        return $this;
    }

    public function setDevice(Reference $device): self
    {
        $this->set('device', $device->toArray());
        return $this;
    }

    public function setPerformer(Reference $performer): self
    {
        $this->set('performer', $performer->toArray());
        return $this;
    }

    public function setReferenceSeq(
        CodeableConcept $referenceSeqId,
        ?string $strand = 'watson',
        ?int $windowStart = null,
        ?int $windowEnd = null,
        ?string $orientation = null
    ): self {
        $refSeq = [
            'referenceSeqId' => $referenceSeqId->toArray(),
        ];

        if ($strand !== null) {
            $refSeq['strand'] = $strand;
        }
        if ($windowStart !== null) {
            $refSeq['windowStart'] = $windowStart;
        }
        if ($windowEnd !== null) {
            $refSeq['windowEnd'] = $windowEnd;
        }
        if ($orientation !== null) {
            $refSeq['orientation'] = $orientation;
        }

        $this->set('referenceSeq', $refSeq);
        return $this;
    }

    public function addVariant(
        int $start,
        int $end,
        string $observedAllele,
        string $referenceAllele,
        ?Reference $variantPointer = null
    ): self {
        $variant = [
            'start' => $start,
            'end' => $end,
            'observedAllele' => $observedAllele,
            'referenceAllele' => $referenceAllele,
        ];

        if ($variantPointer !== null) {
            $variant['variantPointer'] = $variantPointer->toArray();
        }

        $this->push('variant', $variant);
        return $this;
    }

    public function build(): array
    {
        if (!isset($this->data['coordinateSystem'])) {
            throw new \RuntimeException('MolecularSequence.coordinateSystem is required');
        }
        return parent::build();
    }
}

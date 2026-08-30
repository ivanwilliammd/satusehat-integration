<?php

namespace Satusehat\Integration\Parser;

class MolecularSequenceParser
{
    private $molecularSequence;

    public function __construct($molecularSequence)
    {
        $this->molecularSequence = $molecularSequence;
    }

    public function getText()
    {
        return $this->molecularSequence['text'] ?? null;
    }

    public function getType()
    {
        return $this->molecularSequence['type'] ?? null;
    }

    public function getCoordinateSystem()
    {
        return $this->molecularSequence['coordinateSystem'] ?? null;
    }

    public function getPatientReference()
    {
        return $this->removePrefix($this->molecularSequence['patient']['reference'] ?? null, 'Patient/');
    }

    public function getSpecimenReference()
    {
        return $this->removePrefix($this->molecularSequence['specimen']['reference'] ?? null, 'Specimen/');
    }

    public function getDeviceReference()
    {
        return $this->removePrefix($this->molecularSequence['device']['reference'] ?? null, 'Device/');
    }

    public function getPerformerReference()
    {
        return $this->removePrefix($this->molecularSequence['performer']['reference'] ?? null, 'Organization/');
    }

    public function getReferenceSeq()
    {
        return $this->molecularSequence['referenceSeq'] ?? null;
    }

    public function getVariants()
    {
        return $this->molecularSequence['variant'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

<?php

namespace Satusehat\Integration\Parser;

class ObservationParser
{
    private $observation;

    public function __construct($observation)
    {
        $this->observation = $observation;
    }

    public function getStatus()
    {
        return $this->observation['status'] ?? null;
    }

    public function getCategories()
    {
        return $this->observation['category'] ?? null;
    }

    public function getCode()
    {
        return $this->observation['code'] ?? null;
    }

    public function getSubject()
    {
        return $this->observation['subject'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->removePrefix($this->observation['subject']['reference'] ?? null, 'Patient/');
    }

    public function getEncounter()
    {
        return $this->observation['encounter'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->observation['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getEffectiveDateTime()
    {
        return $this->observation['effectiveDateTime'] ?? null;
    }

    public function getIssued()
    {
        return $this->observation['issued'] ?? null;
    }

    public function getPerformers()
    {
        return $this->observation['performer'] ?? null;
    }

    public function getPerformerReferences()
    {
        return array_map(function($performer) {
            return $this->removePrefix($performer['reference'] ?? null, 'Practitioner/');
        }, $this->observation['performer'] ?? []);
    }

    public function getValueQuantity()
    {
        return $this->observation['valueQuantity'] ?? null;
    }

    public function getValueString()
    {
        return $this->observation['valueString'] ?? null;
    }

    public function getValueCodeableConcept()
    {
        return $this->observation['valueCodeableConcept'] ?? null;
    }

    public function getBasedOn()
    {
        return $this->observation['basedOn'] ?? null;
    }

    public function getSpecimen()
    {
        return $this->observation['specimen'] ?? null;
    }

    public function getSpecimenReference()
    {
        return $this->removePrefix($this->observation['specimen']['reference'] ?? null, 'Specimen/');
    }

    public function getInterpretations()
    {
        return $this->observation['interpretation'] ?? null;
    }

    public function getReferenceRanges()
    {
        return $this->observation['referenceRange'] ?? null;
    }

    public function getDerivedFrom()
    {
        return $this->observation['derivedFrom'] ?? null;
    }

    public function getComponents()
    {
        return $this->observation['component'] ?? null;
    }

    public function getIdentifiers()
    {
        return $this->observation['identifier'] ?? null;
    }

    public function getPartOf()
    {
        return $this->observation['partOf'] ?? null;
    }

    public function getMethod()
    {
        return $this->observation['method'] ?? null;
    }

    public function getNote()
    {
        return $this->observation['note'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }
}

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

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getCategory()
    {
        return $this->data['category'] ?? null;
    }

    public function getEffectivePeriod()
    {
        return $this->data['effectivePeriod'] ?? null;
    }

    public function getEffectiveInstant()
    {
        return $this->data['effectiveInstant'] ?? null;
    }

    public function getEffectiveTiming()
    {
        return $this->data['effectiveTiming'] ?? null;
    }

    public function getValueBoolean()
    {
        return $this->data['valueBoolean'] ?? null;
    }

    public function getValueInteger()
    {
        return $this->data['valueInteger'] ?? null;
    }

    public function getValueRange()
    {
        return $this->data['valueRange'] ?? null;
    }

    public function getValueRatio()
    {
        return $this->data['valueRatio'] ?? null;
    }

    public function getValueTime()
    {
        return $this->data['valueTime'] ?? null;
    }

    public function getValueDateTime()
    {
        return $this->data['valueDateTime'] ?? null;
    }

    public function getValuePeriod()
    {
        return $this->data['valuePeriod'] ?? null;
    }

    public function getInterpretation()
    {
        return $this->data['interpretation'] ?? null;
    }

    public function getBodySite()
    {
        return $this->data['bodySite'] ?? null;
    }

    public function getDevice()
    {
        return $this->data['device'] ?? null;
    }

    public function getReferenceRange()
    {
        return $this->data['referenceRange'] ?? null;
    }

    public function getComponent()
    {
        return $this->data['component'] ?? null;
    }

    public function getExtension()
    {
        return $this->data['extension'] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;

class PayloadBuilderImmunization extends Builder
{
    protected string $resourceType = 'Immunization';
    private const STATUSES = ['completed', 'entered-in-error', 'not-done'];
    private const VACCINE_CODES = ['93001282'];

    public function __construct() { $this->data['resourceType'] = $this->resourceType; }

    private function refArray(Reference $ref, ?string $display = null): array
    {
        $arr = $ref->toArray();
        if ($display !== null) $arr['display'] = $display;
        return $arr;
    }

    public function setMetaProfile(string $profile): self { $this->data['meta/profile'] = [$profile]; return $this; }
    public function setId(string $id): self { $this->set('id', $id); return $this; }
    public function addIdentifier(Identifier $identifier): self { $this->push('identifier', $identifier->toArray()); return $this; }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::STATUSES, true)) throw new FHIRInvalidPropertyValue('Invalid Immunization.status: ' . $status);
        $this->set('status', $status);
        return $this;
    }

    public function setVaccineCode(CodeableConcept $vaccineCode): self { $this->set('vaccineCode', $vaccineCode->toArray()); return $this; }

    public function setVaccineCodeFromCode(string $code, ?string $display = null, string $system = 'http://snomed.info/sct'): self
    {
        if (!in_array($code, self::VACCINE_CODES, true)) throw new FHIRInvalidPropertyValue('Invalid vaccine code: ' . $code);
        $concept = new CodeableConcept();
        $concept->addCoding(new Coding($system, $code, $display));
        return $this->setVaccineCode($concept);
    }

    public function setPatient(Reference $patient, ?string $display = null): self { $this->set('patient', $this->refArray($patient, $display)); return $this; }
    public function setOccurrenceDateTime(string $dateTime): self { $this->set('occurrenceDateTime', $dateTime); return $this; }

    public function addPerformer(Reference $actor, ?CodeableConcept $function = null): self
    {
        $performer = ['actor' => $actor->toArray()];
        if ($function !== null) $performer['function'] = $function->toArray();
        $this->push('performer', $performer);
        return $this;
    }

    public function setDoseQuantity(float $value, string $unit, ?string $system = null, ?string $code = null): self
    {
        $this->set('doseQuantity', (new Quantity($value, null, $unit, $system, $code))->toArray());
        return $this;
    }

    public function setLocation(Reference $location, ?string $display = null): self { $this->set('location', $this->refArray($location, $display)); return $this; }
    public function setLotNumber(string $lotNumber): self { $this->set('lotNumber', $lotNumber); return $this; }
    public function setRecorded(string $dateTime): self { $this->set('recorded', $dateTime); return $this; }
    public function setPrimarySource(bool $primarySource): self { $this->set('primarySource', $primarySource); return $this; }

    public function addProtocolApplied(int $doseNumberPositiveInt, ?CodeableConcept $series = null): self
    {
        $row = ['doseNumberPositiveInt' => $doseNumberPositiveInt];
        if ($series !== null) $row['seriesDosesPositiveInt'] = 1;
        $this->push('protocolApplied', $row);
        return $this;
    }

    public function addReasonCode(CodeableConcept $reason): self { $this->push('reasonCode', $reason->toArray()); return $this; }
    public function setRoute(CodeableConcept $route): self { $this->set('route', $route->toArray()); return $this; }
    public function setRouteFromCode(string $code, ?string $display = null, string $system = 'http://terminology.hl7.org/CodeSystem/v3-RouteOfAdministration'): self
    {
        $route = new CodeableConcept();
        $route->addCoding(new Coding($system, $code, $display));
        return $this->setRoute($route);
    }
    public function addExtension(string $url, string $value): self { $this->push('extension', ['url' => $url, 'valueString' => $value]); return $this; }
}

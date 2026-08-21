<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Terminology\ImmunizationTerminology;

/**
 * Immunization FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/R4/immunization.html
 */
class PayloadBuilderImmunization extends Builder
{
    protected string $resourceType = 'Immunization';

    private ImmunizationTerminology $terminology;

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
        $this->terminology = new ImmunizationTerminology;
    }

    public function setMetaProfile(string $profile): self
    {
        $this->push('meta/profile', $profile);
        return $this;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    /**
     * @param string $status completed | entered-in-error | not-done
     * @throws FHIRInvalidPropertyValue
     */
    public function setStatus(string $status): self
    {
        $validStatuses = ['completed', 'entered-in-error', 'not-done'];
        if (! in_array($status, $validStatuses, true)) {
            throw new FHIRInvalidPropertyValue('Immunization.status', implode('|', $validStatuses), $status);
        }
        $this->set('status', $status);
        return $this;
    }

    /**
     * Direct CodeableConcept for vaccineCode.
     */
    public function setVaccineCode(CodeableConcept $vaccineCode): self
    {
        $this->set('vaccineCode', $vaccineCode->toArray());
        return $this;
    }

    /**
     * Set vaccineCode using a KFA vaccine code from ImmunizationTerminology::$vaccine_map.
     *
     * @param string $immunizationCode KFA code key (e.g. '93001282')
     * @throws FHIRInvalidPropertyValue
     */
    public function setVaccineCodeFromCode(string $immunizationCode): self
    {
        $map = $this->terminology->vaccine_map;
        if (! array_key_exists($immunizationCode, $map)) {
            $available = implode(', ', array_keys($map));
            throw new FHIRInvalidPropertyValue('immunizationCode', $available, $immunizationCode);
        }
        $this->set('vaccineCode', ['coding' => $map[$immunizationCode]]);
        return $this;
    }

    /**
     * @param Reference $patient
     * @param string|null $display Optional display string for the patient reference
     */
    public function setPatient(Reference $patient, ?string $display = null): self
    {
        $ref = $patient->toArray();
        if ($display !== null) {
            $ref['display'] = $display;
        }
        $this->set('patient', $ref);
        return $this;
    }

    public function setOccurrenceDateTime(string $occurrenceDateTime): self
    {
        $this->set('occurrenceDateTime', $occurrenceDateTime);
        return $this;
    }

    /**
     * @param Reference $actor Performer actor
     * @param CodeableConcept|null $function Performer function (e.g. http://terminology.hl7.org/CodeSystem/v2-0443)
     */
    public function addPerformer(Reference $actor, ?CodeableConcept $function = null): self
    {
        $performer = [
            'actor' => $actor->toArray(),
        ];

        if ($function !== null) {
            $performer['function'] = $function->toArray();
        }

        $this->push('performer', $performer);
        return $this;
    }

    /**
     * @param float|int $value
     * @param string $unit e.g. 'mL'
     * @param string $system
     * @param string $code UCUM code e.g. 'ml'
     */
    public function setDoseQuantity($value, string $unit, string $system = 'http://unitsofmeasure.org', string $code = 'ml'): self
    {
        if (!is_float($value) && !is_int($value)) {
            throw new \InvalidArgumentException('DoseQuantity value must be float or int');
        }
        $this->set('doseQuantity', [
            'value' => $value,
            'unit' => $unit,
            'system' => $system,
            'code' => $code,
        ]);
        return $this;
    }

    /**
     * @param Reference $location
     * @param string|null $display Optional display string for the location reference
     */
    public function setLocation(Reference $location, ?string $display = null): self
    {
        $ref = $location->toArray();
        if ($display !== null) {
            $ref['display'] = $display;
        }
        $this->set('location', $ref);
        return $this;
    }

    public function setLotNumber(string $lotNumber): self
    {
        $this->set('lotNumber', $lotNumber);
        return $this;
    }

    /**
     * @param string $recorded ISO 8601 datetime when immunization was recorded
     */
    public function setRecorded(string $recorded): self
    {
        $this->set('recorded', $recorded);
        return $this;
    }

    /**
     * @param bool $primarySource True if this record was sourced from the original immunization event
     */
    public function setPrimarySource(bool $primarySource): self
    {
        $this->set('primarySource', $primarySource);
        return $this;
    }

    /**
     * @param int $doseNumberPositiveInt The dose number in the series
     * @param CodeableConcept|null $series Code indicating the series (e.g. "1 of 3")
     */
    public function addProtocolApplied(int $doseNumberPositiveInt, ?CodeableConcept $series = null): self
    {
        $protocolApplied = [
            'doseNumberPositiveInt' => $doseNumberPositiveInt,
        ];

        if ($series !== null) {
            $protocolApplied['seriesDosesPositiveInt'] = $series->toArray();
        }

        $this->push('protocolApplied', $protocolApplied);
        return $this;
    }

    public function addReasonCode(CodeableConcept $reasonCode): self
    {
        if (! isset($this->data['reasonCode'])) {
            $this->data['reasonCode'] = [];
        }
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    /**
     * Direct CodeableConcept for route.
     */
    public function setRoute(CodeableConcept $route): self
    {
        $this->set('route', $route->toArray());
        return $this;
    }

    /**
     * @param string $routeCode Route code from MedicationTerminology::$route
     * @param string|null $display Optional display override
     * @throws FHIRInvalidPropertyValue
     */
    public function setRouteFromCode(string $routeCode, ?string $display = null): self
    {
        $route = new CodeableConcept();
        $route->addCoding(new Coding(
            'http://terminology.hl7.org/CodeSystem/v3-RouteOfAdministration',
            $routeCode,
            $display ?? $routeCode,
        ));
        $this->set('route', $route->toArray());
        return $this;
    }

    public function addExtension(string $url, mixed $value, ?string $valueType = null): self
    {
        $extension = ['url' => $url];

        if ($valueType !== null) {
            $extension['value' . ucfirst($valueType)] = $value;
        } else {
            $extension['valueString'] = is_string($value) ? $value : $value;
        }

        $this->push('extension', $extension);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

/**
 * Immunization FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/immunization.html
 */
class PayloadBuilderImmunization extends Builder
{
    protected string $resourceType = 'Immunization';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
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

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setVaccineCode(CodeableConcept $vaccineCode): self
    {
        $this->set('vaccineCode', $vaccineCode->toArray());
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setOccurrenceDateTime(string $occurrenceDateTime): self
    {
        $this->set('occurrenceDateTime', $occurrenceDateTime);
        return $this;
    }

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

    public function setDoseQuantity(Quantity $doseQuantity): self
    {
        $this->set('doseQuantity', $doseQuantity->toArray());
        return $this;
    }

    public function setLocation(Reference $location): self
    {
        $this->set('location', $location->toArray());
        return $this;
    }

    public function setLotNumber(string $lotNumber): self
    {
        $this->set('lotNumber', $lotNumber);
        return $this;
    }

    public function setRecorded(string $recorded): self
    {
        $this->set('recorded', $recorded);
        return $this;
    }

    public function setPrimarySource(bool $primarySource): self
    {
        $this->set('primarySource', $primarySource);
        return $this;
    }

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
        $this->push('reasonCode', $reasonCode->toArray());
        return $this;
    }

    public function setRoute(CodeableConcept $route): self
    {
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

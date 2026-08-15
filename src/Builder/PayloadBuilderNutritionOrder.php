<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Timing;

/**
 * NutritionOrder FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/nutritionorder.html
 */
class PayloadBuilderNutritionOrder extends Builder
{
    protected string $resourceType = 'NutritionOrder';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
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

    public function setIntent(string $intent): self
    {
        $this->set('intent', $intent);
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setEncounter(Reference $encounter): self
    {
        $this->set('encounter', $encounter->toArray());
        return $this;
    }

    public function setDateTime(string $dateTime): self
    {
        $this->set('dateTime', $dateTime);
        return $this;
    }

    public function setOrderer(Reference $orderer): self
    {
        $this->set('orderer', $orderer->toArray());
        return $this;
    }

    public function addAllergyType(CodeableConcept $allergyType): self
    {
        $this->push('allergyType', $allergyType->toArray());
        return $this;
    }

    public function addFoodPreferenceModifier(CodeableConcept $foodPreferenceModifier): self
    {
        $this->push('foodPreferenceModifier', $foodPreferenceModifier->toArray());
        return $this;
    }

    public function addExcludeFoodModifier(CodeableConcept $excludeFoodModifier): self
    {
        $this->push('excludeFoodModifier', $excludeFoodModifier->toArray());
        return $this;
    }

    public function setOralDietType(CodeableConcept $type): self
    {
        if (!isset($this->data['oralDiet'])) {
            $this->data['oralDiet'] = [];
        }
        $this->push('oralDiet/type', $type->toArray());
        return $this;
    }

    public function addOralDietSchedule(Timing $schedule): self
    {
        if (!isset($this->data['oralDiet'])) {
            $this->data['oralDiet'] = [];
        }
        $this->push('oralDiet/schedule', $schedule->toArray());
        return $this;
    }

    public function addSupplement(array $supplement): self
    {
        $this->push('supplement', $supplement);
        return $this;
    }

    public function setEnteralFormula(array $enteralFormula): self
    {
        $this->set('enteralFormula', $enteralFormula);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

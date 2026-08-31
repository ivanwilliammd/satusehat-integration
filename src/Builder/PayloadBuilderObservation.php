<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Range;
use Satusehat\Integration\DataType\Ratio;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\SampledData;

/**
 * Observation FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/observation.html
 */
class PayloadBuilderObservation extends Builder
{
    protected string $resourceType = 'Observation';

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

    public function addCategory(CodeableConcept|string $category): self
    {
        if (is_string($category)) {
            $cc = \Satusehat\Integration\Terminology\Resolver::resolve($category);
            $this->push('category', $cc->toArray());
            return $this;
        }
        $this->push('category', $category->toArray());
        return $this;
    }

    public function setCode(CodeableConcept|string $code): self
    {
        $this->setCodeable('code', $code);
        return $this;
    }

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setEncounter(Reference $encounter): self
    {
        $this->set('encounter', $encounter->toArray());
        return $this;
    }

    public function setEffectiveDateTime(string $dateTime): self
    {
        $this->set('effectiveDateTime', $dateTime);
        return $this;
    }

    public function setEffectivePeriod(Period $period): self
    {
        $this->set('effectivePeriod', $period->toArray());
        return $this;
    }

    public function setEffectiveInstant(string $instant): self
    {
        $this->set('effectiveInstant', $instant);
        return $this;
    }

    public function setEffectiveTiming(\Satusehat\Integration\DataType\Timing $timing): self
    {
        $this->set('effectiveTiming', $timing->toArray());
        return $this;
    }

    public function setEffectivePeriodStart(string $start): self
    {
        $this->set('effectivePeriod', ['start' => $start]);
        return $this;
    }

    public function setEffectivePeriodEnd(string $end): self
    {
        $existing = $this->data['effectivePeriod'] ?? [];
        $existing['end'] = $end;
        $this->set('effectivePeriod', $existing);
        return $this;
    }

    // Value[x] polymorphic setters
    public function setValueQuantity(Quantity $value): self
    {
        $this->set('valueQuantity', $value->toArray());
        return $this;
    }

    public function setValueCodeableConcept(CodeableConcept $value): self
    {
        $this->set('valueCodeableConcept', $value->toArray());
        return $this;
    }

    public function setValueString(string $value): self
    {
        $this->set('valueString', $value);
        return $this;
    }

    public function setValueBoolean(bool $value): self
    {
        $this->set('valueBoolean', $value);
        return $this;
    }

    public function setValueInteger(int $value): self
    {
        $this->set('valueInteger', $value);
        return $this;
    }

    public function setValueRange(Range $value): self
    {
        $this->set('valueRange', $value->toArray());
        return $this;
    }

    public function setValueRatio(Ratio $value): self
    {
        $this->set('valueRatio', $value->toArray());
        return $this;
    }

    public function setValueTime(string $value): self
    {
        $this->set('valueTime', $value);
        return $this;
    }

    public function setValueDateTime(string $value): self
    {
        $this->set('valueDateTime', $value);
        return $this;
    }

    public function setValuePeriod(Period $value): self
    {
        $this->set('valuePeriod', $value->toArray());
        return $this;
    }

    public function addInterpretation(CodeableConcept $interpretation): self
    {
        $this->push('interpretation', $interpretation->toArray());
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }

    public function addBodySite(CodeableConcept $bodySite): self
    {
        $this->push('bodySite', $bodySite->toArray());
        return $this;
    }

    public function setMethod(CodeableConcept $method): self
    {
        $this->set('method', $method->toArray());
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

    public function addReferenceRange(
        ?Quantity $low = null,
        ?Quantity $high = null,
        ?CodeableConcept $type = null,
        ?string $text = null
    ): self {
        $range = [];

        if ($low !== null || $high !== null) {
            $range['low'] = $low?->toArray();
            $range['high'] = $high?->toArray();
        }

        if ($type !== null) {
            $range['type'] = $type->toArray();
        }

        if ($text !== null) {
            $range['text'] = $text;
        }

        $this->push('referenceRange', array_filter($range, fn($v) => $v !== null));
        return $this;
    }

    public function addComponent(CodeableConcept $code, mixed $value): self
    {
        $component = ['code' => $code->toArray()];

        if ($value instanceof Quantity) {
            $component['valueQuantity'] = $value->toArray();
        } elseif ($value instanceof CodeableConcept) {
            $component['valueCodeableConcept'] = $value->toArray();
        } elseif ($value instanceof Range) {
            $component['valueRange'] = $value->toArray();
        } elseif ($value instanceof Ratio) {
            $component['valueRatio'] = $value->toArray();
        } elseif ($value instanceof SampledData) {
            $component['valueSampledData'] = $value->toArray();
        } elseif (is_string($value)) {
            $component['valueString'] = $value;
        } elseif (is_int($value)) {
            $component['valueInteger'] = $value;
        } elseif (is_bool($value)) {
            $component['valueBoolean'] = $value;
        }

        $this->push('component', $component);
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

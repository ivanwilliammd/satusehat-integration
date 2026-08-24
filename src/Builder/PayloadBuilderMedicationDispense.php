<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

class PayloadBuilderMedicationDispense extends Builder
{
    protected string $resourceType = 'MedicationDispense';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    private function reference(string $type, string $idOrReference): string
    {
        return strpos($idOrReference, '/') === false ? $type . '/' . $idOrReference : $idOrReference;
    }

    public function setId(string $id): self { $this->set('id', $id); return $this; }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', ['system' => $system, 'value' => $value]);
        return $this;
    }

    public function setStatus(string $s): self
    {
        $this->set('status', $s);
        return $this;
    }

    public function setMedicationReference(string $reference, ?string $display = null): self
    {
        $medicationReference = ['reference' => $reference];
        if ($display !== null) $medicationReference['display'] = $display;
        $this->set('medicationReference', $medicationReference);
        return $this;
    }

    public function setSubject(string $subjectId, ?string $display = null): self
    {
        $subject = ['reference' => $this->reference('Patient', $subjectId)];
        if ($display !== null) $subject['display'] = $display;
        $this->set('subject', $subject);
        return $this;
    }

    public function setContext(string $contextId): self
    {
        $this->set('context/reference', $this->reference('Encounter', $contextId));
        return $this;
    }

    public function addPerformer(string $actorId, ?string $display = null): self
    {
        $actor = ['reference' => $this->reference('Practitioner', $actorId)];
        if ($display !== null) $actor['display'] = $display;
        $this->push('performer', ['actor' => $actor]);
        return $this;
    }

    public function setLocation(string $locationId, ?string $display = null): self
    {
        $location = ['reference' => $this->reference('Location', $locationId)];
        if ($display !== null) $location['display'] = $display;
        $this->set('location', $location);
        return $this;
    }

    public function addAuthorizingPrescription(string $requestId): self
    {
        $this->push('authorizingPrescription', ['reference' => $this->reference('MedicationRequest', $requestId)]);
        return $this;
    }

    public function setCategory(string $code, ?string $display = null, string $system = 'http://terminology.hl7.org/CodeSystem/medicationdispense-category'): self
    {
        $coding = ['system' => $system, 'code' => $code];
        if ($display !== null) $coding['display'] = $display;
        $this->set('category/coding', [$coding]);
        return $this;
    }

    public function setQuantity(float $value, string $unit, ?string $system = null, ?string $code = null): self
    {
        $quantity = ['value' => $value, 'unit' => $unit];
        if ($system !== null) $quantity['system'] = $system;
        if ($code !== null) $quantity['code'] = $code;
        $this->set('quantity', $quantity);
        return $this;
    }

    public function setDaysSupply(float $value, string $unit, ?string $system = null, ?string $code = null): self
    {
        $daysSupply = ['value' => $value, 'unit' => $unit];
        if ($system !== null) $daysSupply['system'] = $system;
        if ($code !== null) $daysSupply['code'] = $code;
        $this->set('daysSupply', $daysSupply);
        return $this;
    }

    public function setWhenPrepared(string $dateTime): self { $this->set('whenPrepared', date('c', strtotime($dateTime))); return $this; }

    public function setWhenHandedOver(string $dateTime): self { $this->set('whenHandedOver', date('c', strtotime($dateTime))); return $this; }

    public function addDosageInstruction(int $sequence, string $text, float $doseValue, string $doseUnit, string $routeCode, string $routeDisplay, string $timingCode, string $timingDisplay, string $additionalCode, string $additionalText): self
    {
        $this->push('dosageInstruction', [
            'sequence' => $sequence,
            'text' => $text,
            'additionalInstruction' => [['coding' => [['code' => $additionalCode, 'display' => $additionalText]]]],
            'timing' => ['code' => ['coding' => [['code' => $timingCode, 'display' => $timingDisplay]]]],
            'route' => ['coding' => [['code' => $routeCode, 'display' => $routeDisplay]]],
            'doseAndRate' => [['doseQuantity' => ['value' => $doseValue, 'unit' => $doseUnit]]],
        ]);
        return $this;
    }

    public function setSubstitution(bool $wasSubstituted, ?string $typeCode = null, ?string $typeDisplay = null): self
    {
        $substitution = ['wasSubstituted' => $wasSubstituted];
        if ($typeCode !== null) {
            $coding = ['code' => $typeCode];
            if ($typeDisplay !== null) $coding['display'] = $typeDisplay;
            $substitution['type']['coding'] = [$coding];
        }
        $this->set('substitution', $substitution);
        return $this;
    }

    public function addContained(array $resource): self
    {
        $this->push('contained', $resource);
        return $this;
    }
}

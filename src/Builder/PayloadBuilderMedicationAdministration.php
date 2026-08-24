<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

class PayloadBuilderMedicationAdministration extends Builder
{
    protected string $resourceType = 'MedicationAdministration';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    private function refArray($reference): array
    {
        if ($reference instanceof Reference) return $reference->toArray();
        return ['reference' => (string) $reference];
    }

    public function setId(string $id): self { $this->set('id', $id); return $this; }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setStatus(string $s): self
    {
        $this->set('status', $s);
        return $this;
    }

    public function setMedicationReference($reference): self
    {
        $this->set('medicationReference', $this->refArray($reference));
        return $this;
    }

    public function setSubject($subject): self
    {
        $this->set('subject', $this->refArray($subject));
        return $this;
    }

    public function setContext($context): self
    {
        $this->set('context', $this->refArray($context));
        return $this;
    }

    public function setEffectivePeriod(Period $period): self
    {
        $this->set('effectivePeriod', $period->toArray());
        return $this;
    }

    public function addPerformer(Reference $actor): self
    {
        $this->push('performer', ['actor' => $actor->toArray()]);
        return $this;
    }

    public function addReasonCode(CodeableConcept $reason): self
    {
        $this->push('reasonCode', $reason->toArray());
        return $this;
    }

    public function setRequest(Reference $request): self
    {
        $this->set('request', $request->toArray());
        return $this;
    }

    public function setDosage(Quantity $dose, CodeableConcept $route, CodeableConcept $site): self
    {
        $this->set('dosage', [
            'dose' => $dose->toArray(),
            'route' => $route->toArray(),
            'site' => $site->toArray(),
        ]);
        return $this;
    }

    public function addContained(array $resource): self
    {
        $this->push('contained', $resource);
        return $this;
    }
}

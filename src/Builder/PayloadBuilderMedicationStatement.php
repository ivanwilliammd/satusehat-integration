<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * MedicationStatement FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medicationstatement.html
 */
class PayloadBuilderMedicationStatement extends Builder
{
    protected string $resourceType = 'MedicationStatement';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function setStatus(string $status = 'completed'): self
    {
        $this->set('status', strtolower($status));
        return $this;
    }

    public function addStatusReason(string $code, ?string $display = null, string $system = 'http://snomed.info/sct'): self
    {
        $this->push('statusReason', [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ]);
        return $this;
    }

    public function setMedicationReference(string $reference, string $display): self
    {
        $this->set('medicationReference', [
            'reference' => $reference,
            'display' => $display,
        ]);
        return $this;
    }

    public function setSubject(string $subjectId, string $name): self
    {
        $this->set('subject', [
            'reference' => 'Patient/'.$subjectId,
            'display' => $name,
        ]);
        return $this;
    }

    public function setContext(string $contextId, ?string $display = null): self
    {
        $this->set('context', [
            'reference' => 'Encounter/'.$contextId,
            'display' => $display ?? 'Kunjungan '.$contextId,
        ]);
        return $this;
    }

    public function setDateAsserted(?string $dateAsserted = null): self
    {
        $this->set('dateAsserted', $dateAsserted
            ? date('Y-m-d\TH:i:sP', strtotime($dateAsserted))
            : date('Y-m-d\TH:i:sP'));
        return $this;
    }

    public function setEffectiveDateTime(?string $effectiveDateTime = null): self
    {
        $this->set('effectiveDateTime', $effectiveDateTime
            ? date('Y-m-d\TH:i:sP', strtotime($effectiveDateTime))
            : date('Y-m-d\TH:i:sP'));
        return $this;
    }

    public function setInformationSource(string $sourceId, string $name): self
    {
        $this->set('informationSource', [
            'reference' => 'Patient/'.$sourceId,
            'display' => $name,
        ]);
        return $this;
    }

    public function addDosageInstruction(string $text, int $frequency, float $period, string $periodUnit): self
    {
        $this->push('dosage', [
            'text' => $text,
            'timing' => [
                'repeat' => [
                    'frequency' => $frequency,
                    'period' => $period,
                    'periodUnit' => $periodUnit,
                ],
            ],
        ]);
        return $this;
    }

    public function addContained(array $containedResource): self
    {
        $this->push('contained', $containedResource);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

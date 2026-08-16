<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * MedicationDispense FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/medicationdispense.html
 */
class PayloadBuilderMedicationDispense extends Builder
{
    protected string $resourceType = 'MedicationDispense';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value, string $use = 'official'): self
    {
        $this->push('identifier', [
            'system' => $system,
            'value' => $value,
            'use' => $use,
        ]);
        return $this;
    }

    public function setStatus(string $status = 'completed'): self
    {
        $this->set('status', strtolower($status));
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

    public function setSubject(string $reference, string $display): self
    {
        $this->set('subject', [
            'reference' => $reference,
            'display' => $display,
        ]);
        return $this;
    }

    public function setContext(string $reference): self
    {
        $this->set('context', [
            'reference' => $reference,
        ]);
        return $this;
    }

    public function addPerformer(string $reference, string $display): self
    {
        $this->push('performer', [
            'actor' => [
                'reference' => $reference,
                'display' => $display,
            ],
        ]);
        return $this;
    }

    public function setLocation(string $reference, string $display): self
    {
        $this->set('location', [
            'reference' => 'Location/'.$reference,
            'display' => $display,
        ]);
        return $this;
    }

    public function addAuthorizingPrescription(string $reference): self
    {
        $this->push('authorizingPrescription', [
            'reference' => 'MedicationRequest/'.$reference,
        ]);
        return $this;
    }

    public function setCategory(string $code, ?string $display = null): self
    {
        $this->set('category', [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ]);
        return $this;
    }

    public function setQuantity(float $value, string $unit, string $system = 'http://snomed.info/sct'): self
    {
        $this->set('quantity', [
            'value' => $value,
            'unit' => $unit,
            'system' => $system,
            'code' => $unit,
        ]);
        return $this;
    }

    public function setDaysSupply(float $value, string $unit, string $system = 'http://unitsofmeasure.org', ?string $unitDisplay = null): self
    {
        $this->set('daysSupply', [
            'value' => $value,
            'code' => $unit,
            'system' => $system,
            'unit' => $unitDisplay,
        ]);
        return $this;
    }

    public function setWhenPrepared(string $datetime): self
    {
        $this->set('whenPrepared', date('Y-m-d\TH:i:sP', strtotime($datetime)));
        return $this;
    }

    public function setWhenHandedOver(string $datetime): self
    {
        $this->set('whenHandedOver', date('Y-m-d\TH:i:sP', strtotime($datetime)));
        return $this;
    }

    public function addDosageInstruction(
        int $sequence,
        ?string $patientInstruction,
        float $doseValue,
        string $doseUnit,
        string $routeCode,
        ?string $routeDisplay,
        string $timingCode,
        ?string $timingDisplay,
        ?string $additionalInstructionCode,
        ?string $additionalInstructionDisplay,
        ?string $additionalInstructionSystem = 'http://snomed.info/sct'
    ): self {
        $dosageInstruction = [
            'sequence' => $sequence,
            'patientInstruction' => $patientInstruction,
            'doseAndRate' => [
                [
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                'code' => 'ordered',
                                'display' => 'Ordered',
                            ],
                        ],
                    ],
                    'doseQuantity' => [
                        'value' => $doseValue,
                        'code' => $doseUnit,
                        'system' => 'http://unitsofmeasure.org',
                        'unit' => $doseUnit,
                    ],
                ],
            ],
            'route' => [
                'coding' => [
                    [
                        'system' => 'http://www.whocc.no/atc',
                        'code' => $routeCode,
                        'display' => $routeDisplay,
                    ],
                ],
            ],
            'timing' => [
                'code' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-GTSAbbreviation',
                            'code' => $timingCode,
                            'display' => $timingDisplay,
                        ],
                    ],
                ],
            ],
        ];

        if ($additionalInstructionCode !== null && $additionalInstructionDisplay !== null) {
            $dosageInstruction['additionalInstruction'] = [
                [
                    'coding' => [
                        [
                            'system' => $additionalInstructionSystem,
                            'code' => $additionalInstructionCode,
                            'display' => $additionalInstructionDisplay,
                        ],
                    ],
                ],
            ];
        }

        $this->push('dosageInstruction', $dosageInstruction);
        return $this;
    }

    public function setSubstitution(bool $wasSubstituted = true, ?string $reasonCode = null, ?string $reasonDisplay = null): self
    {
        $substitution = [
            'wasSubstituted' => $wasSubstituted,
        ];

        if ($reasonCode !== null) {
            $substitution['type'] = [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-substanceAdminSubstitution',
                        'code' => $reasonCode,
                        'display' => $reasonDisplay,
                    ],
                ],
            ];
        }

        $this->set('substitution', $substitution);
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

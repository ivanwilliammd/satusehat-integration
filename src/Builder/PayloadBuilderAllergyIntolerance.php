<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * AllergyIntolerance FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/allergyintolerance.html
 */
class PayloadBuilderAllergyIntolerance extends Builder
{
    protected string $resourceType = 'AllergyIntolerance';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', [
            'system' => $system,
            'value' => $value,
        ]);
        return $this;
    }

    public function setClinicalStatus(string $status): self
    {
        $this->set('clinicalStatus', [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
                    'code' => $status,
                ],
            ],
        ]);
        return $this;
    }

    public function setVerificationStatus(string $status): self
    {
        $this->set('verificationStatus', [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
                    'code' => $status,
                ],
            ],
        ]);
        return $this;
    }

    public function setType(string $type): self
    {
        $this->set('type', $type);
        return $this;
    }

    public function addCategory(string $category): self
    {
        $this->push('category', $category);
        return $this;
    }

    public function setCriticality(string $criticality): self
    {
        $this->set('criticality', $criticality);
        return $this;
    }

    public function setCode(string $code, string $display, ?string $text = null): self
    {
        $codeData = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];

        if ($text !== null) {
            $codeData['text'] = $text;
        }

        $this->set('code', $codeData);
        return $this;
    }

    public function setPatient(string $reference, ?string $display = null): self
    {
        $patient = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $patient['display'] = $display;
        }

        $this->set('patient', $patient);
        return $this;
    }

    public function setEncounter(string $reference, ?string $display = null): self
    {
        $encounter = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $encounter['display'] = $display;
        }

        $this->set('encounter', $encounter);
        return $this;
    }

    public function setOnsetDateTime(string $dateTime): self
    {
        $this->set('onsetDateTime', $dateTime);
        return $this;
    }

    public function setRecordedDate(string $dateTime): self
    {
        $this->set('recordedDate', $dateTime);
        return $this;
    }

    public function setRecorder(string $reference): self
    {
        $this->set('recorder', [
            'reference' => $reference,
        ]);
        return $this;
    }

    public function setAsserter(string $reference): self
    {
        $this->set('asserter', [
            'reference' => $reference,
        ]);
        return $this;
    }

    public function setLastOccurrence(string $dateTime): self
    {
        $this->set('lastOccurrence', $dateTime);
        return $this;
    }

    public function addNote(string $text): self
    {
        $this->push('note', [
            'text' => $text,
        ]);
        return $this;
    }

    public function addReaction(
        array $substance,
        array $manifestation,
        ?string $description = null,
        ?string $onset = null,
        ?string $severity = null,
        ?array $exposureRoute = null,
        ?string $note = null
    ): self {
        $reaction = [
            'substance' => [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $substance['code'],
                        'display' => $substance['display'],
                    ],
                ],
            ],
            'manifestation' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => $manifestation['code'],
                            'display' => $manifestation['display'],
                        ],
                    ],
                ],
            ],
        ];

        if ($description !== null) {
            $reaction['description'] = $description;
        }

        if ($onset !== null) {
            $reaction['onset'] = $onset;
        }

        if ($severity !== null) {
            $reaction['severity'] = $severity;
        }

        if ($exposureRoute !== null) {
            $reaction['exposureRoute'] = [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $exposureRoute['code'],
                        'display' => $exposureRoute['display'],
                    ],
                ],
            ];
        }

        if ($note !== null) {
            $reaction['note'] = [
                ['text' => $note],
            ];
        }

        $this->push('reaction', $reaction);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

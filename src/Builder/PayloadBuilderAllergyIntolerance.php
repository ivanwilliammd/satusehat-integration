<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

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

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
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

    public function setCode(CodeableConcept $code): self
    {
        $this->set('code', $code->toArray());
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

    public function setRecorder(Reference $recorder): self
    {
        $this->set('recorder', $recorder->toArray());
        return $this;
    }

    public function setAsserter(Reference $asserter): self
    {
        $this->set('asserter', $asserter->toArray());
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
        CodeableConcept $substance,
        CodeableConcept $manifestation,
        ?string $description = null,
        ?string $onset = null,
        ?string $severity = null,
        ?CodeableConcept $exposureRoute = null,
        ?string $note = null
    ): self {
        $reaction = [
            'substance' => $substance->toArray(),
            'manifestation' => [$manifestation->toArray()],
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
            $reaction['exposureRoute'] = $exposureRoute->toArray();
        }

        if ($note !== null) {
            $reaction['note'] = [['text' => $note]];
        }

        $this->push('reaction', $reaction);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

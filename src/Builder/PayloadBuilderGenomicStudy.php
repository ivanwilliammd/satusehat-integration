<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * GenomicStudy FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/genomicstudy.html
 */
class PayloadBuilderGenomicStudy extends Builder
{
    protected string $resourceType = 'GenomicStudy';

    private const VALID_STATUSES = [
        'registered',
        'available',
        'cancelled',
        'entered-in-error',
        'unknown',
    ];

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
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new \InvalidArgumentException(
                "Invalid status '{$status}'. Valid values: " . implode(', ', self::VALID_STATUSES)
            );
        }
        $this->set('status', $status);
        return $this;
    }

    public function addType(CodeableConcept $type): self
    {
        $this->push('type', $type->toArray());
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

    public function setStartDate(string $startDate): self
    {
        $this->set('startDate', $startDate);
        return $this;
    }

    public function addBasedOn(Reference $basedOn): self
    {
        $this->push('basedOn', $basedOn->toArray());
        return $this;
    }

    public function setReferrer(Reference $referrer): self
    {
        $this->set('referrer', $referrer->toArray());
        return $this;
    }

    public function addInterpreter(Reference $interpreter): self
    {
        $this->push('interpreter', $interpreter->toArray());
        return $this;
    }

    public function addReason(CodeableConcept $reason): self
    {
        $this->push('reason', ['concept' => $reason->toArray()]);
        return $this;
    }

    public function addNote(Annotation $note): self
    {
        $this->push('note', $note->toArray());
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function addAnalysis(array $analysis): self
    {
        $this->push('analysis', $analysis);
        return $this;
    }

    public function addPerformer(Reference $actor, string $role = 'PERF'): self
    {
        $performerRole = (new CodeableConcept())->addCoding(
            new \Satusehat\Integration\DataType\Coding(
                'http://terminology.hl7.org/3.1.0/CodeSystem-v3-ParticipationType.html',
                $role,
                'Performer'
            )
        );

        $this->push('performer', [
            'actor' => $actor->toArray(),
            'role' => $performerRole->toArray(),
        ]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

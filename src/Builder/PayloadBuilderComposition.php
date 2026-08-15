<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Narrative;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * Composition FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/composition.html
 */
class PayloadBuilderComposition extends Builder
{
    protected string $resourceType = 'Composition';

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

    public function setType(CodeableConcept $type): self
    {
        $this->set('type', $type->toArray());
        return $this;
    }

    public function addCategory(CodeableConcept $category): self
    {
        $this->push('category', $category->toArray());
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->set('title', $title);
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->set('date', $date);
        return $this;
    }

    public function addAuthor(Reference $author): self
    {
        $this->push('author', $author->toArray());
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

    public function setCustodian(Reference $custodian): self
    {
        $this->set('custodian', $custodian->toArray());
        return $this;
    }

    public function addAttester(string $mode, Reference $party): self
    {
        $this->push('attester', [
            'mode' => $mode,
            'party' => $party->toArray(),
        ]);
        return $this;
    }

    public function addSection(array $section): self
    {
        $this->push('section', $section);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

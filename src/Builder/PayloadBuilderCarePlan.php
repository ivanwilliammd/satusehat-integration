<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * CarePlan FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/careplan.html
 */
class PayloadBuilderCarePlan extends Builder
{
    protected string $resourceType = 'CarePlan';

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

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
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

    public function setPeriod(Period $period): self
    {
        $this->set('period', $period->toArray());
        return $this;
    }

    public function setAuthor(Reference $author): self
    {
        $this->set('author', $author->toArray());
        return $this;
    }

    public function addContributor(Reference $contributor): self
    {
        $this->push('contributor', $contributor->toArray());
        return $this;
    }

    public function addAddressee(Reference $addressee): self
    {
        $this->push('addressee', $addressee->toArray());
        return $this;
    }

    public function addSupportingInfo(Reference $supportingInfo): self
    {
        $this->push('supportingInfo', $supportingInfo->toArray());
        return $this;
    }

    public function addGoal(Reference $goal): self
    {
        $this->push('goal', $goal->toArray());
        return $this;
    }

    public function addActivity(array $detail): self
    {
        $this->push('activity', ['detail' => $detail]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

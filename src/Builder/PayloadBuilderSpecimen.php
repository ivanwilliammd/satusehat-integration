<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Reference;

class PayloadBuilderSpecimen extends Builder
{
    protected string $resourceType = 'Specimen';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
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

    public function setSubject(Reference $subject): self
    {
        $this->set('subject', $subject->toArray());
        return $this;
    }

    public function setCollectedDateTime(string $dateTime): self
    {
        $this->set('collection/collectedDateTime', $dateTime);
        return $this;
    }
}

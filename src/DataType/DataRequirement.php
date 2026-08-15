<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class DataRequirement extends DataType
{
    public ?string $type = null;
    /** @var string[] */
    public array $profile = [];
    /** @var CodeableConcept|Reference|null */
    public $subject = null;
    /** @var array[] */
    public array $codeFilter = [];
    /** @var array[] */
    public array $dateFilter = [];
    /** @var array[] */
    public array $sort = [];

    public function addProfile(string $profile): static
    {
        $this->profile[] = $profile;
        return $this;
    }

    public function setSubject($subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function addCodeFilter(array $filter): static
    {
        $this->codeFilter[] = $filter;
        return $this;
    }

    public function addDateFilter(array $filter): static
    {
        $this->dateFilter[] = $filter;
        return $this;
    }

    public function addSort(array $sort): static
    {
        $this->sort[] = $sort;
        return $this;
    }
}

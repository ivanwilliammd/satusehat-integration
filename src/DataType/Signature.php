<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Signature extends DataType
{
    /** @var Coding[] */
    public array $type = [];
    public ?string $when = null;
    public ?Reference $who = null;
    public ?Reference $onBehalfOf = null;
    public ?string $targetFormat = null;
    public ?string $sigFormat = null;
    public ?string $data = null;

    public function addType(Coding $type): static
    {
        $this->type[] = $type;
        return $this;
    }

    public function setWho(Reference $who): static
    {
        $this->who = $who;
        return $this;
    }

    public function setOnBehalfOf(Reference $onBehalfOf): static
    {
        $this->onBehalfOf = $onBehalfOf;
        return $this;
    }
}

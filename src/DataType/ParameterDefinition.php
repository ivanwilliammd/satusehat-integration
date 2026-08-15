<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class ParameterDefinition extends DataType
{
    public ?string $name = null;
    /** @var string|null in|out */
    public ?string $use = null;
    public ?int $min = null;
    public ?string $max = null;
    public ?string $documentation = null;
    public ?string $type = null;
    public ?string $profile = null;

    public function __construct(
        ?string $name = null,
        ?string $use = null,
        ?int $min = null,
        ?string $max = null
    ) {
        $this->name = $name;
        $this->use = $use;
        $this->min = $min;
        $this->max = $max;
    }
}

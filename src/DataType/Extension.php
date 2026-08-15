<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Extension extends DataType
{
    public ?string $url = null;
    /** @var mixed */
    public $value = null;

    public function __construct(string $url, $value = null)
    {
        $this->url = $url;
        $this->value = $value;
    }

    public function setValue($value): static
    {
        $this->value = $value;
        return $this;
    }
}

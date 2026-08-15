<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Money extends DataType
{
    public ?float $value = null;
    /** @var string|null ISO 4217 e.g. IDR, USD */
    public ?string $currency = null;

    public function __construct(?float $value = null, ?string $currency = null)
    {
        $this->value = $value;
        $this->currency = $currency;
    }
}

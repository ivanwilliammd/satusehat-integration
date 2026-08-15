<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Narrative extends DataType
{
    public ?string $status = null;
    public ?string $div = null;

    public function __construct(?string $status = null, ?string $div = null)
    {
        $this->status = $status;
        $this->div = $div;
    }
}

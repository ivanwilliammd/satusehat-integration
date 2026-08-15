<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Reference extends DataType
{
    public ?string $reference = null; // usually 'ResourceType/id'
    public ?string $type = null;      // uri
    public ?string $display = null;

    public function __construct(
        ?string $reference = null,
        ?string $display = null,
        ?string $type = null
    ) {
        $this->reference = $this->str($reference);
        $this->display = $this->str($display);
        $this->type = $this->str($type);
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($v) => $v !== null && $v !== []);
    }
}

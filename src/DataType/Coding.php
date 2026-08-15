<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Coding extends DataType
{
    public ?string $system = null;
    public ?string $version = null;
    public ?string $code = null;
    public ?string $display = null;
    public ?bool $userSelected = null;

    public function __construct(
        ?string $system = null,
        ?string $code = null,
        ?string $display = null,
        ?string $version = null,
        ?bool $userSelected = null
    ) {
        $this->system = $this->str($system);
        $this->code = $this->str($code);
        $this->display = $this->str($display);
        $this->version = $this->str($version);
        $this->userSelected = $this->bool($userSelected);
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($v) => $v !== null && $v !== []);
    }
}

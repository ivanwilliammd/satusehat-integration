<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class HumanName extends DataType
{
    public ?string $use = null;    // usual|official|temp|nickname|anonymous|old|maiden
    public ?string $text = null;
    public ?string $family = null;
    public array $given = [];
    public array $prefix = [];
    public array $suffix = [];
    public ?Period $period = null;

    public function __construct(
        ?string $family = null,
        array $given = [],
        ?string $use = null,
        ?string $text = null,
        array $prefix = [],
        array $suffix = [],
        ?Period $period = null
    ) {
        $this->family = $this->str($family);
        $this->given = $given;
        $this->use = $this->str($use);
        $this->text = $this->str($text);
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->period = $period;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        if (isset($data['period']) && $data['period'] instanceof Period) {
            $data['period'] = $data['period']->toArray();
        }

        return array_filter($data, fn($v) => $v !== null && $v !== []);
    }
}

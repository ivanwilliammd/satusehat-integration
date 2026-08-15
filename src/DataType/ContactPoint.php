<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class ContactPoint extends DataType
{
    public ?string $system = null; // phone|fax|email|url|sms|other
    public ?string $value = null;
    public ?string $use = null;   // home|work|temp|old|mobile
    public ?int $rank = null;      // positiveInt
    public ?Period $period = null;

    public function __construct(
        ?string $system = null,
        ?string $value = null,
        ?string $use = null,
        ?int $rank = null,
        ?Period $period = null
    ) {
        $this->system = $this->str($system);
        $this->value = $this->str($value);
        $this->use = $this->str($use);
        $this->rank = $this->int($rank);
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

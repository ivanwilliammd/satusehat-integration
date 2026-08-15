<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Address extends DataType
{
    public ?string $use = null;       // home|work|temp|old|billing
    public ?string $type = null;      // postal|physical|both
    public ?string $text = null;
    public array $line = [];
    public ?string $city = null;
    public ?string $district = null;
    public ?string $state = null;
    public ?string $postalCode = null;
    public ?string $country = null;
    public ?Period $period = null;

    public function __construct() {}

    public function toArray(): array
    {
        $data = get_object_vars($this);
        if (isset($data['period']) && $data['period'] instanceof Period) {
            $data['period'] = $data['period']->toArray();
        }

        return array_filter($data, fn($v) => $v !== null && $v !== []);
    }
}

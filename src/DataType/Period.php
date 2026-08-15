<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property string|null $start
 * @property string|null $end
 */
class Period extends DataType
{
    public ?string $start = null;
    public ?string $end = null;

    public function __construct(?string $start = null, ?string $end = null)
    {
        $this->start = $start;
        $this->end = $end;
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * @property string|null $reference
 * @property string|null $type
 * @property string|null $display
 */
class Reference extends DataType
{
    public ?string $reference = null;
    public ?string $type = null;
    public ?string $display = null;

    public function __construct(?string $reference = null, ?string $display = null)
    {
        $this->reference = $reference;
        $this->display = $display;
    }
}

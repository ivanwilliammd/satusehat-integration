<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * FHIR R4 Parameters.parameter — one named argument of an operation.
 *
 * `$valueKey` names the value[x] variant, e.g. `valueString`, `valueQuantity`,
 * `valueBoolean`. FHIR does not allow a bare `value` key, so the caller must
 * choose the variant explicitly.
 */
class ParameterComponent extends DataType
{
    public ?string $name = null;
    public ?string $valueKey = null;
    /** @var mixed */
    public $value = null;
    /** @var mixed */
    public $resource = null;
    /** @var ParameterComponent[]|null */
    public ?array $part = null;

    /**
     * @param ParameterComponent[]|null $part
     * @param mixed $value
     * @param mixed $resource
     */
    public function __construct(
        ?string $name = null,
        ?string $valueKey = null,
        $value = null,
        $resource = null,
        ?array $part = null
    ) {
        $this->name = $name;
        $this->valueKey = $valueKey;
        $this->value = $value;
        $this->resource = $resource;
        $this->part = $part;
    }

    public function toArray(): array
    {
        $hasil = [];
        if ($this->name !== null && $this->name !== '') {
            $hasil['name'] = $this->name;
        }
        if ($this->valueKey !== null && $this->valueKey !== '' && $this->value !== null) {
            $hasil[$this->valueKey] = $this->toArrayRecursive($this->value);
        }
        if ($this->resource !== null) {
            $hasil['resource'] = $this->toArrayRecursive($this->resource);
        }
        if (!empty($this->part)) {
            $hasil['part'] = $this->toArrayRecursive($this->part);
        }

        return $hasil;
    }
}

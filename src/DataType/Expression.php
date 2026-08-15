<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class Expression extends DataType
{
    public ?string $description = null;
    /** @var string|null text/cql|text/fhirpath|text/x-fhir-query|text/plain */
    public ?string $language = null;
    public ?string $expression = null;
    public ?string $reference = null;

    public function __construct(
        ?string $language = null,
        ?string $expression = null,
        ?string $description = null
    ) {
        $this->language = $language;
        $this->expression = $expression;
        $this->description = $description;
    }
}

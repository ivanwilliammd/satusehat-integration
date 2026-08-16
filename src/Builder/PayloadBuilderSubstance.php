<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Substance FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/substance.html
 */
class PayloadBuilderSubstance extends Builder
{
    protected string $resourceType = 'Substance';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $valid = ['active', 'inactive', 'entered-in-error'];
        if (!in_array($status, $valid)) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }
        $this->set('status', $status);
        return $this;
    }

    public function setCode(string $system, string $code, string $display): self
    {
        $this->set('code', [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ]);
        return $this;
    }

    public function setText(string $status, string $div): self
    {
        $this->set('text', [
            'status' => $status,
            'div' => $div,
        ]);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

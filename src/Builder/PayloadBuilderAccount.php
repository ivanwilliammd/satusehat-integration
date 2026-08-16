<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * Account FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/account.html
 */
class PayloadBuilderAccount extends Builder
{
    protected string $resourceType = 'Account';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setStatus(string $status = 'active'): self
    {
        $status = strtolower($status);
        if (! in_array($status, ['active', 'inactive', 'entered-in-error', 'on-hold', 'unknown'])) {
            throw new \InvalidArgumentException('Invalid status value');
        }
        $this->set('status', $status);
        return $this;
    }

    public function setType(string $system, string $code, string $display, string $text): self
    {
        $this->set('type', [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
            'text' => $text,
        ]);
        return $this;
    }

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function addSubject(string $reference, ?string $display = null): self
    {
        $subject = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $subject['display'] = $display;
        }

        $this->push('subject', $subject);
        return $this;
    }

    public function setServicePeriod(string $start, string $end): self
    {
        $this->set('servicePeriod', [
            'start' => $start,
            'end' => $end,
        ]);
        return $this;
    }

    public function addCoverage(string $reference, int $priority): self
    {
        $this->push('coverage', [
            'coverage' => [
                'reference' => $reference,
            ],
            'priority' => $priority,
        ]);
        return $this;
    }

    public function setOwner(string $reference): self
    {
        $this->set('owner', [
            'reference' => $reference,
        ]);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

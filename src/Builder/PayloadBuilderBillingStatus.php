<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * BillingStatus NON-FHIR JSON Resource Builder
 * @link https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/billing-status
 */
class PayloadBuilderBillingStatus extends Builder
{
    protected string $resourceType = 'BillingStatus';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string|Identifier $systemOrIdentifier, ?string $value = null): self
    {
        if ($systemOrIdentifier instanceof Identifier) {
            $this->push('identifier', $systemOrIdentifier->toArray());
        } else {
            $this->push('identifier', ['system' => $systemOrIdentifier, 'value' => $value]);
        }
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setInsurer(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Organization/' . $reference;
        }
        $this->set('insurer', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function setRecipient(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Organization/' . $reference;
        }
        $this->set('recipient', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function setSubject(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Patient/' . $reference;
        }
        $this->set('subject', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function setRequest(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'CoverageEligibilityRequest/' . $reference;
        }
        $this->set('request', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

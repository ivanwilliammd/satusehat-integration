<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

/**
 * PurificationDecision SATUSEHAT-specific NON-FHIR JSON Resource Builder
 * @link https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/purification-decision
 */
class PayloadBuilderPurificationDecision extends Builder
{
    protected string $resourceType = 'PurificationDecision';

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

    public function setStatus(string $code, ?string $display = null, ?string $system = null): self
    {
        $coding = ['code' => $code, 'display' => $display ?? $code];
        if ($system !== null) {
            $coding['system'] = $system;
        }
        $this->set('status', [
            'coding' => [$coding],
        ]);
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

    public function setProvider(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Organization/' . $reference;
        }
        $this->set('provider', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function setClaimResponse(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'ClaimResponse/' . $reference;
        }
        $this->set('claimResponse', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function setCreated(string $dateTime): self
    {
        $this->set('created', $dateTime);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

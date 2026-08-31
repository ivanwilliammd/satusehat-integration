<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * Endpoint FHIR R4 Resource Builder
 * @link https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/endpoint
 * @link https://www.hl7.org/fhir/endpoint.html
 */
class PayloadBuilderEndpoint extends Builder
{
    protected string $resourceType = 'Endpoint';

    private const VALID_STATUSES = [
        'active', 'suspended', 'error', 'off', 'entered-in-error', 'test',
    ];

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
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }
        $this->set('status', $status);
        return $this;
    }

    public function setConnectionType(string $code, string $display, string $system = 'http://terminology.hl7.org/CodeSystem/endpoint-connection-type'): self
    {
        $this->set('connectionType', [
            'coding' => [[
                'code' => $code,
                'display' => $display,
                'system' => $system,
            ]],
        ]);
        return $this;
    }

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function setManagingOrganization(string $reference, ?string $display = null): self
    {
        if (!preg_match('/^(urn:|https?:\/\/)/', $reference) && strpos($reference, '/') === false) {
            $reference = 'Organization/' . $reference;
        }
        $this->set('managingOrganization', array_filter(['reference' => $reference, 'display' => $display], fn($v) => $v !== null));
        return $this;
    }

    public function addContact(string $system, string $value, ?string $use = null): self
    {
        $contact = ['system' => $system, 'value' => $value];
        if ($use !== null) {
            $contact['use'] = $use;
        }
        $this->push('contact', $contact);
        return $this;
    }

    public function setPeriod(string $start, ?string $end = null): self
    {
        $period = ['start' => $start];
        if ($end !== null) {
            $period['end'] = $end;
        }
        $this->set('period', $period);
        return $this;
    }

    public function addPayloadType(string $code, string $display, string $system = 'http://terminology.hl7.org/CodeSystem/endpoint-payload-type'): self
    {
        $this->push('payloadType', [
            'coding' => [[
                'code' => $code,
                'display' => $display,
                'system' => $system,
            ]],
        ]);
        return $this;
    }

    public function addPayloadMimeType(string $mimeType): self
    {
        $this->push('payloadMimeType', $mimeType);
        return $this;
    }

    public function setAddress(string $address): self
    {
        $this->set('address', $address);
        return $this;
    }

    public function addHeader(string $header): self
    {
        $this->push('header', $header);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

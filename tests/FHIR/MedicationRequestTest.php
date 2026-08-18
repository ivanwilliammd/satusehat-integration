<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMedicationRequest;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class MedicationRequestTest extends TestCase
{
    public function test_builder_instantiates(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $this->assertInstanceOf(PayloadBuilderMedicationRequest::class, $b);
    }

    public function test_json_produces_valid_fhir_payload(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $b->setId('mr-001');
        $b->setStatus('active');
        $b->setIntent('order');
        $b->setSubject(new Reference('Patient/123'));
        $b->setAuthoredOn('2024-01-15');

        $payload = $b->json();

        $this->assertSame('MedicationRequest', $payload['resourceType']);
        $this->assertSame('mr-001', $payload['id']);
        $this->assertSame('active', $payload['status']);
    }

    public function test_build_returns_array(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $this->assertIsArray($b->build());
    }

    public function test_fluent_setters_return_self(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $this->assertSame($b, $b->setStatus('active'));
        $this->assertSame($b, $b->setIntent('order'));
        $this->assertSame($b, $b->setSubject(new Reference('Patient/123')));
    }

    public function test_add_identifier(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $b->addIdentifier(new Identifier('http://example.org', 'RX-001'));
        $payload = $b->json();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('RX-001', $payload['identifier'][0]['value'] ?? null);
    }

    public function test_add_reason_code(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $cc = new CodeableConcept('J06.9', 'Acute upper respiratory infection');
        $b->addReasonCode($cc);

        $payload = $b->json();
        $this->assertArrayHasKey('reasonCode', $payload);
    }

    public function test_set_requester(): void
    {
        $b = new PayloadBuilderMedicationRequest();
        $b->setRequester(new Reference('Practitioner/456'));
        $payload = $b->json();

        $this->assertSame('Practitioner/456', $payload['requester']['reference'] ?? null);
    }
}

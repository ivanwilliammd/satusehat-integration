<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderAccount;

class AccountTest extends TestCase
{
    public function test_builder_instantiates(): void
    {
        $b = new PayloadBuilderAccount();
        $this->assertInstanceOf(PayloadBuilderAccount::class, $b);
    }

    public function test_json_produces_valid_fhir_payload(): void
    {
        $b = new PayloadBuilderAccount();
        $b->setName('Test Account');
        $payload = $b->json();

        $this->assertSame('Account', $payload['resourceType']);
        $this->assertSame('Test Account', $payload['name']);
    }

    public function test_build_returns_array(): void
    {
        $b = new PayloadBuilderAccount();
        $result = $b->build();
        $this->assertIsArray($result);
    }

    public function test_fluent_setters_return_self(): void
    {
        $b = new PayloadBuilderAccount();
        $this->assertSame($b, $b->setName('Acct'));
        $this->assertSame($b, $b->setStatus('active'));
        $this->assertSame($b, $b->setId('acc-001'));
    }

    public function test_set_status(): void
    {
        $b = new PayloadBuilderAccount();
        $b->setStatus('active');
        $payload = $b->json();
        $this->assertSame('active', $payload['status']);
    }

    public function test_set_type(): void
    {
        $b = new PayloadBuilderAccount();
        $b->setType('http://example.org', 'patient', 'Patient Account', 'Patient billing account');
        $payload = $b->json();
        $this->assertSame('patient', $payload['type']['coding'][0]['code'] ?? null);
    }

    public function test_add_subject(): void
    {
        $b = new PayloadBuilderAccount();
        $b->addSubject('Patient/123', 'John Doe');
        $payload = $b->json();
        $this->assertSame('Patient/123', $payload['subject'][0]['reference'] ?? null);
        $this->assertSame('John Doe', $payload['subject'][0]['display'] ?? null);
    }

    public function test_set_service_period(): void
    {
        $b = new PayloadBuilderAccount();
        $b->setServicePeriod('2024-01-01', '2024-12-31');
        $payload = $b->json();
        $this->assertSame('2024-01-01', $payload['servicePeriod']['start'] ?? null);
    }

    public function test_add_coverage(): void
    {
        $b = new PayloadBuilderAccount();
        $b->addCoverage('Coverage/456', 1);
        $payload = $b->json();
        $this->assertArrayHasKey('coverage', $payload);
    }
}

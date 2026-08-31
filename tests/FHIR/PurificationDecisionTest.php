<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderPurificationDecision;

class PurificationDecisionTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $this->assertSame('PurificationDecision', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setId('pd-001')->build();
        $this->assertSame('pd-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->addIdentifier('http://sys-ids.kemkes.go.id/purification/org-001', 'PD-12345')->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/purification/org-001', $result['identifier'][0]['system']);
        $this->assertSame('PD-12345', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setStatus('approved', 'Approved', 'http://terminology.kemkes.go.id/purification-status')->build();
        $this->assertSame('approved', $result['status']['coding'][0]['code']);
        $this->assertSame('Approved', $result['status']['coding'][0]['display']);
        $this->assertSame('http://terminology.kemkes.go.id/purification-status', $result['status']['coding'][0]['system']);
    }

    public function test_set_insurer()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setInsurer('Organization/org-bpjs', 'BPJS Kesehatan')->build();
        $this->assertSame('Organization/org-bpjs', $result['insurer']['reference']);
        $this->assertSame('BPJS Kesehatan', $result['insurer']['display']);
    }

    public function test_set_insurer_bare()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setInsurer('org-bpjs')->build();
        $this->assertSame('Organization/org-bpjs', $result['insurer']['reference']);
    }

    public function test_set_provider()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setProvider('Organization/hos-001', 'Rumah Sakit Sehat')->build();
        $this->assertSame('Organization/hos-001', $result['provider']['reference']);
    }

    public function test_set_provider_bare()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setProvider('hos-001')->build();
        $this->assertSame('Organization/hos-001', $result['provider']['reference']);
    }

    public function test_set_claim_response()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setClaimResponse('cr-001', 'Claim Response 001')->build();
        $this->assertSame('ClaimResponse/cr-001', $result['claimResponse']['reference']);
    }

    public function test_set_claim_response_bare()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setClaimResponse('cr-001')->build();
        $this->assertSame('ClaimResponse/cr-001', $result['claimResponse']['reference']);
    }

    public function test_set_created()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder->setCreated('2024-01-15T10:35:00+00:00')->build();
        $this->assertSame('2024-01-15T10:35:00+00:00', $result['created']);
    }

    public function test_full_payload()
    {
        $builder = new PayloadBuilderPurificationDecision;
        $result = $builder
            ->setId('pd-full-001')
            ->addIdentifier('http://sys-ids.kemkes.go.id/purification/org-001', 'PD-99999')
            ->setStatus('approved', 'Approved')
            ->setInsurer('Organization/org-bpjs', 'BPJS Kesehatan')
            ->setProvider('Organization/hos-001', 'Rumah Sakit Sehat')
            ->setClaimResponse('cr-response-001', 'Claim Response Full')
            ->setCreated('2024-01-15T10:35:00+00:00')
            ->build();

        $this->assertSame('PurificationDecision', $result['resourceType']);
        $this->assertSame('approved', $result['status']['coding'][0]['code']);
        $this->assertSame('ClaimResponse/cr-response-001', $result['claimResponse']['reference']);
    }
}

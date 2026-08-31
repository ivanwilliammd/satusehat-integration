<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderBillingStatus;

class BillingStatusTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderBillingStatus;
        $this->assertSame('BillingStatus', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setId('bs-001')->build();
        $this->assertSame('bs-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->addIdentifier('http://sys-ids.kemkes.go.id/billing/org-001', 'BILL-12345')->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/billing/org-001', $result['identifier'][0]['system']);
        $this->assertSame('BILL-12345', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_insurer()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setInsurer('Organization/org-001', 'BPJS Kesehatan')->build();
        $this->assertSame('Organization/org-001', $result['insurer']['reference']);
        $this->assertSame('BPJS Kesehatan', $result['insurer']['display']);
    }

    public function test_set_insurer_bare()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setInsurer('org-001')->build();
        $this->assertSame('Organization/org-001', $result['insurer']['reference']);
    }

    public function test_set_recipient()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setRecipient('Organization/hos-001', 'Rumah Sakit Sehat')->build();
        $this->assertSame('Organization/hos-001', $result['recipient']['reference']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setSubject('100000030009', 'Budi Santoso')->build();
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
        $this->assertSame('Budi Santoso', $result['subject']['display']);
    }

    public function test_set_request()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder->setRequest('cer-001', 'Eligibility Request')->build();
        $this->assertSame('CoverageEligibilityRequest/cer-001', $result['request']['reference']);
    }

    public function test_full_payload()
    {
        $builder = new PayloadBuilderBillingStatus;
        $result = $builder
            ->setId('bs-full-001')
            ->addIdentifier('http://sys-ids.kemkes.go.id/billing/org-001', 'BILL-99999')
            ->setStatus('active')
            ->setInsurer('Organization/org-bpjs', 'BPJS Kesehatan')
            ->setRecipient('Organization/hos-001', 'Rumah Sakit Umum')
            ->setSubject('100000030009', 'Budi Santoso')
            ->setRequest('cer-001', 'Eligibility Request')
            ->build();

        $this->assertSame('BillingStatus', $result['resourceType']);
        $this->assertSame('bs-full-001', $result['id']);
        $this->assertSame('active', $result['status']);
        $this->assertSame('Patient/100000030009', $result['subject']['reference']);
    }
}

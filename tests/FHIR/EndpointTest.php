<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEndpoint;

class EndpointTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderEndpoint;
        $this->assertSame('Endpoint', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setId('ep-001')->build();
        $this->assertSame('ep-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->addIdentifier('http://sys-ids.kemkes.go.id/endpoint/org-001', 'EP-12345')->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/endpoint/org-001', $result['identifier'][0]['system']);
        $this->assertSame('EP-12345', $result['identifier'][0]['value']);
    }

    public function test_set_status_valid()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_status_invalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new PayloadBuilderEndpoint;
        $builder->setStatus('invalid-status');
    }

    public function test_set_connection_type()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setConnectionType('ihe-xcpd', 'IHE XCPD', 'http://terminology.hl7.org/CodeSystem/endpoint-connection-type')->build();
        $this->assertSame('ihe-xcpd', $result['connectionType']['coding'][0]['code']);
        $this->assertSame('IHE XCPD', $result['connectionType']['coding'][0]['display']);
    }

    public function test_set_name()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setName('FHIR Server Endpoint')->build();
        $this->assertSame('FHIR Server Endpoint', $result['name']);
    }

    public function test_set_managing_organization()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setManagingOrganization('Organization/org-001', 'Rumah Sakit Sehat')->build();
        $this->assertSame('Organization/org-001', $result['managingOrganization']['reference']);
    }

    public function test_set_managing_organization_bare()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setManagingOrganization('org-001')->build();
        $this->assertSame('Organization/org-001', $result['managingOrganization']['reference']);
    }

    public function test_add_contact()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->addContact('phone', '+622112345678', 'work')->build();
        $this->assertSame('phone', $result['contact'][0]['system']);
        $this->assertSame('+622112345678', $result['contact'][0]['value']);
        $this->assertSame('work', $result['contact'][0]['use']);
    }

    public function test_set_period()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setPeriod('2022-12-20', '2022-12-30')->build();
        $this->assertSame('2022-12-20', $result['period']['start']);
        $this->assertSame('2022-12-30', $result['period']['end']);
    }

    public function test_add_payload_type()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->addPayloadType('none', 'None', 'http://terminology.hl7.org/CodeSystem/endpoint-payload-type')->build();
        $this->assertSame('none', $result['payloadType'][0]['coding'][0]['code']);
    }

    public function test_add_payload_mime_type()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->addPayloadMimeType('application/fhir+json')->build();
        $this->assertSame('application/fhir+json', $result['payloadMimeType'][0]);
    }

    public function test_set_address()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->setAddress('https://fhir.example.com/r4')->build();
        $this->assertSame('https://fhir.example.com/r4', $result['address']);
    }

    public function test_add_header()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder->addHeader('Authorization: Bearer token123')->build();
        $this->assertSame('Authorization: Bearer token123', $result['header'][0]);
    }

    public function test_full_payload()
    {
        $builder = new PayloadBuilderEndpoint;
        $result = $builder
            ->setId('ep-full-001')
            ->addIdentifier('http://sys-ids.kemkes.go.id/endpoint/org-001', 'EP-99999')
            ->setStatus('active')
            ->setConnectionType('ihe-xcpd', 'IHE XCPD')
            ->setName('SATUSEHAT FHIR Endpoint')
            ->setManagingOrganization('Organization/org-ihs', 'Ministry of Health')
            ->setPeriod('2022-12-20')
            ->addPayloadType('none', 'None')
            ->addPayloadMimeType('application/fhir+json')
            ->setAddress('https://satusehat-api.example.com/fhir/r4')
            ->build();

        $this->assertSame('Endpoint', $result['resourceType']);
        $this->assertSame('active', $result['status']);
        $this->assertSame('Organization/org-ihs', $result['managingOrganization']['reference']);
    }
}

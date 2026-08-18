<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderOrganization;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class OrganizationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderOrganization;
        $this->assertSame('Organization', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderOrganization;
        $result = $builder->setId('org-001')->build();
        $this->assertSame('org-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderOrganization;
        $id = new Identifier('http://sys-ids.kemkes.go.id/organization', 'ORG-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('ORG-001', $result['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderOrganization;
        $result = $builder->setActive(true)->build();
        $this->assertTrue($result['active']);
    }

    public function test_set_name()
    {
        $builder = new PayloadBuilderOrganization;
        $result = $builder->setName('Rumah Sakit Sehat')->build();
        $this->assertSame('Rumah Sakit Sehat', $result['name']);
    }

    public function test_add_alias()
    {
        $builder = new PayloadBuilderOrganization;
        $result = $builder->addAlias('RS Sehat')->build();
        $this->assertSame('RS Sehat', $result['alias'][0]);
    }

    public function test_set_type()
    {
        $builder = new PayloadBuilderOrganization;
        $type = new CodeableConcept();
        $type->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/organization-type', 'prov', 'Healthcare Provider'));
        $result = $builder->setType($type)->build();
        $this->assertSame('prov', $result['type']['coding'][0]->code);
    }

    public function test_add_telecom()
    {
        $builder = new PayloadBuilderOrganization;
        $telecom = new ContactPoint('phone', '021-1234567');
        $result = $builder->addTelecom($telecom)->build();
        $this->assertSame('021-1234567', $result['telecom'][0]['value']);
    }

    public function test_add_address()
    {
        $builder = new PayloadBuilderOrganization;
        $address = new Address();
        $address->line = ['Jl. Raya Sehat No. 1'];
        $address->city = 'Jakarta';
        $address->postalCode = '12345';

        $result = $builder->addAddress($address)->build();
        $this->assertSame('Jakarta', $result['address'][0]['city']);
    }

    public function test_set_part_of()
    {
        $builder = new PayloadBuilderOrganization;
        $partOf = new Reference('Organization/org-parent', 'Ministry of Health');
        $result = $builder->setPartOf($partOf)->build();
        $this->assertSame('Organization/org-parent', $result['partOf']['reference']);
    }

    public function test_add_contact()
    {
        $builder = new PayloadBuilderOrganization;
        $telecom = new ContactPoint('phone', '021-1234567');
        $result = $builder->addContact($telecom, 'Press', 'Media Relations', null)->build();

        $this->assertSame('021-1234567', $result['contact'][0]['telecom'][0]['value']);
        $this->assertSame('Press', $result['contact'][0]['purpose']['text']);
        $this->assertSame('Media Relations', $result['contact'][0]['name']['text']);
    }

    public function test_add_contact_with_address()
    {
        $builder = new PayloadBuilderOrganization;
        $telecom = new ContactPoint('email', 'contact@example.com');
        $address = new Address();
        $address->line = ['Jl. Example No. 1'];
        $address->city = 'Jakarta';

        $result = $builder->addContact($telecom, null, null, $address)->build();
        $this->assertSame('contact@example.com', $result['contact'][0]['telecom'][0]['value']);
        $this->assertSame('Jakarta', $result['contact'][0]['address']['city']);
    }

    public function test_add_endpoint()
    {
        $builder = new PayloadBuilderOrganization;
        $endpoint = new Reference('Endpoint/ep-001', 'FHIR Endpoint');
        $result = $builder->addEndpoint($endpoint)->build();
        $this->assertSame('Endpoint/ep-001', $result['endpoint'][0]['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderOrganization;
        $result = $builder->addExtension('http://example.org/ext', 'value', 'String')->build();
        $this->assertSame('http://example.org/ext', $result['extension'][0]['url']);
        $this->assertSame('value', $result['extension'][0]['valueString']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderOrganization;

        $builder->setId('org-002')
            ->setActive(true)
            ->setName('Klinik Sehat');

        $this->assertIsArray($builder->build());
        $this->assertSame('org-002', $builder->build()['id']);
    }
}

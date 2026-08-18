<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderLocation;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class LocationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderLocation;
        $this->assertSame('Location', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setId('loc-001')->build();
        $this->assertSame('loc-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderLocation;
        $id = new Identifier('http://sys-ids.kemkes.go.id/location', 'LOC-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('LOC-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_operational_status()
    {
        $builder = new PayloadBuilderLocation;
        $status = new CodeableConcept();
        $status->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v2-0307', 'CI', 'Closed - Imminent'));
        $result = $builder->setOperationalStatus($status)->build();
        $this->assertSame('CI', $result['operationalStatus']['coding'][0]['code']);
    }

    public function test_set_name()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setName('Ruang ICU')->build();
        $this->assertSame('Ruang ICU', $result['name']);
    }

    public function test_add_alias()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->addAlias('ICU')->build();
        $this->assertSame('ICU', $result['alias'][0]);

        $result2 = $builder->addAlias('Intensive Care');
        $this->assertCount(2, $result2->build()['alias']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setDescription('Ruang ICU lantai 3')->build();
        $this->assertSame('Ruang ICU lantai 3', $result['description']);
    }

    public function test_set_type()
    {
        $builder = new PayloadBuilderLocation;
        $type = new CodeableConcept();
        $type->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/location-type', 'ICU', 'Intensive Care Unit'));
        $result = $builder->setType($type)->build();
        $this->assertSame('ICU', $result['type']['coding'][0]['code']);
    }

    public function test_add_telecom()
    {
        $builder = new PayloadBuilderLocation;
        $telecom = new ContactPoint('phone', '021-1234567', 1);
        $result = $builder->addTelecom($telecom)->build();
        $this->assertSame('021-1234567', $result['telecom'][0]['value']);
    }

    public function test_add_address()
    {
        $builder = new PayloadBuilderLocation;
        $address = new Address();
        $address->line = ['Jl. Sudirman No. 1'];
        $address->city = 'Jakarta';
        $address->state = 'DKI Jakarta';
        $address->postalCode = '10220';
        $address->country = 'ID';
        $result = $builder->addAddress($address)->build();
        $this->assertSame('Jl. Sudirman No. 1', $result['address'][0]['line'][0]);
        $this->assertSame('Jakarta', $result['address'][0]['city']);
    }

    public function test_set_physical_type()
    {
        $builder = new PayloadBuilderLocation;
        $physType = new CodeableConcept();
        $physType->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/location-physical-type', 'ro', 'Room'));
        $result = $builder->setPhysicalType($physType)->build();
        $this->assertSame('ro', $result['physicalType']['coding'][0]['code']);
    }

    public function test_set_position_all_params()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setPosition(-6.2088, 106.8456, 10.0)->build();
        $this->assertSame(-6.2088, $result['position']['latitude']);
        $this->assertSame(106.8456, $result['position']['longitude']);
        $this->assertSame(10.0, $result['position']['altitude']);
    }

    public function test_set_position_partial_params()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->setPosition(-6.2088, 106.8456)->build();
        $this->assertSame(-6.2088, $result['position']['latitude']);
        $this->assertSame(106.8456, $result['position']['longitude']);
        $this->assertArrayNotHasKey('altitude', $result['position']);
    }

    public function test_set_managing_organization()
    {
        $builder = new PayloadBuilderLocation;
        $org = new Reference('Organization/org-001', 'RS Sehat');
        $result = $builder->setManagingOrganization($org)->build();
        $this->assertSame('Organization/org-001', $result['managingOrganization']['reference']);
    }

    public function test_set_part_of()
    {
        $builder = new PayloadBuilderLocation;
        $parent = new Reference('Location/loc-parent', 'Gedung A');
        $result = $builder->setPartOf($parent)->build();
        $this->assertSame('Location/loc-parent', $result['partOf']['reference']);
    }

    public function test_add_endpoint()
    {
        $builder = new PayloadBuilderLocation;
        $endpoint = new Reference('Endpoint/ep-001', 'HL7 Endpoint');
        $result = $builder->addEndpoint($endpoint)->build();
        $this->assertSame('Endpoint/ep-001', $result['endpoint'][0]['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->addExtension('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', 'custom-value')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', $result['extension'][0]['url']);
        $this->assertSame('custom-value', $result['extension'][0]['valueString']);
    }

    public function test_add_extension_with_type()
    {
        $builder = new PayloadBuilderLocation;
        $result = $builder->addExtension('https://example.org/ext', 123, 'integer')->build();
        $this->assertSame(123, $result['extension'][0]['valueInteger']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderLocation;
        $builder->setId('loc-002')
            ->setName('Ruang Operasi')
            ->setStatus('active');

        $this->assertIsArray($builder->build());
        $this->assertSame('loc-002', $builder->build()['id']);
    }
}

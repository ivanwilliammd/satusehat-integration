<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderGroup;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class GroupTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderGroup;
        $this->assertSame('Group', $builder->build()['resourceType']);
    }

    public function test_set_meta_profile()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Group')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/Group', $result['meta/profile'][0]);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setId('group-001')->build();
        $this->assertSame('group-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderGroup;
        $id = new Identifier('http://sys-ids.kemkes.go.id/group', 'G-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('http://sys-ids.kemkes.go.id/group', $result['identifier'][0]['system']);
        $this->assertSame('G-001', $result['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setActive(true)->build();
        $this->assertTrue($result['active']);

        $result2 = $builder->setActive(false)->build();
        $this->assertFalse($result2['active']);
    }

    public function test_set_type()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setType('person')->build();
        $this->assertSame('person', $result['type']);
    }

    public function test_set_actual()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setActual(true)->build();
        $this->assertTrue($result['actual']);
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderGroup;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://snomed.info/sct', '721915009', 'Procedure group'));
        $result = $builder->setCode($code)->build();
        $this->assertSame('721915009', $result['code']['coding'][0]->code);
    }

    public function test_set_name()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setName('Kelompok Pasien COVID')->build();
        $this->assertSame('Kelompok Pasien COVID', $result['name']);
    }

    public function test_set_quantity()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->setQuantity(25)->build();
        $this->assertSame(25, $result['quantity']);
    }

    public function test_set_managing_entity()
    {
        $builder = new PayloadBuilderGroup;
        $entity = new Reference('Organization/org-001', 'RS Sehat');
        $result = $builder->setManagingEntity($entity)->build();
        $this->assertSame('Organization/org-001', $result['managingEntity']['reference']);
    }

    public function test_add_member_basic()
    {
        $builder = new PayloadBuilderGroup;
        $ref = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->addMember($ref)->build();
        $this->assertSame('Patient/100000030009', $result['member'][0]['entity']['reference']);
    }

    public function test_add_member_with_period()
    {
        $builder = new PayloadBuilderGroup;
        $ref = new Reference('Patient/100000030009');
        $period = ['start' => '2024-01-01', 'end' => '2024-12-31'];
        $result = $builder->addMember($ref, $period)->build();
        $this->assertSame('2024-01-01', $result['member'][0]['period']['start']);
    }

    public function test_add_member_with_inactive()
    {
        $builder = new PayloadBuilderGroup;
        $ref = new Reference('Patient/100000030009');
        $result = $builder->addMember($ref, null, true)->build();
        $this->assertTrue($result['member'][0]['inactive']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->addExtension('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', 'custom-value')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', $result['extension'][0]['url']);
        $this->assertSame('custom-value', $result['extension'][0]['valueString']);
    }

    public function test_add_extension_with_type()
    {
        $builder = new PayloadBuilderGroup;
        $result = $builder->addExtension('https://example.org/ext', 'test@example.com', 'string')->build();
        $this->assertSame('test@example.com', $result['extension'][0]['valueString']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderGroup;
        $builder->setId('group-002')
            ->setName('Test Group')
            ->setActive(true);

        $this->assertIsArray($builder->build());
        $this->assertSame('group-002', $builder->build()['id']);
    }
}

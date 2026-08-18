<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderPractitioner;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;

class PractitionerTest extends TestCase
{
    public function test_constructor_sets_resource_type(): void
    {
        $payload = (new PayloadBuilderPractitioner)->build();
        $this->assertSame('Practitioner', $payload['resourceType']);
    }

    public function test_set_id(): void
    {
        $payload = (new PayloadBuilderPractitioner)->setId('prac-001')->build();
        $this->assertSame('prac-001', $payload['id']);
    }

    public function test_set_active(): void
    {
        $payload = (new PayloadBuilderPractitioner)->setActive(true)->build();
        $this->assertTrue($payload['active']);
    }

    public function test_add_identifier(): void
    {
        $payload = (new PayloadBuilderPractitioner)
            ->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/practitioner', 'N10000001'))
            ->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('N10000001', $payload['identifier'][0]['value']);
    }

    public function test_add_name(): void
    {
        $name = new HumanName('Rahayu', ['Siti'], 'official', 'Dr. Siti Rahayu');
        $payload = (new PayloadBuilderPractitioner)->addName($name)->build();

        $this->assertArrayHasKey('name', $payload);
        $this->assertSame('Rahayu', $payload['name'][0]['family']);
        $this->assertSame('Dr. Siti Rahayu', $payload['name'][0]['text']);
    }

    public function test_add_telecom(): void
    {
        $telecom = new ContactPoint('phone', '0213456789', 'work');
        $payload = (new PayloadBuilderPractitioner)->addTelecom($telecom)->build();

        $this->assertArrayHasKey('telecom', $payload);
        $this->assertSame('0213456789', $payload['telecom'][0]['value']);
        $this->assertSame('work', $payload['telecom'][0]['use']);
    }

    public function test_add_address(): void
    {
        $addr = new Address();
        $addr->use = 'work';
        $addr->line[] = 'Jl. HR Rasuna Said';
        $addr->city = 'Jakarta';

        $payload = (new PayloadBuilderPractitioner)->addAddress($addr)->build();

        $this->assertArrayHasKey('address', $payload);
        $this->assertSame('Jakarta', $payload['address'][0]['city']);
        $this->assertSame('work', $payload['address'][0]['use']);
    }

    public function test_set_gender(): void
    {
        $payload = (new PayloadBuilderPractitioner)->setGender('female')->build();
        $this->assertSame('female', $payload['gender']);
    }

    public function test_set_birth_date(): void
    {
        $payload = (new PayloadBuilderPractitioner)->setBirthDate('1990-05-15')->build();
        $this->assertSame('1990-05-15', $payload['birthDate']);
    }

    public function test_fluent_returns_self(): void
    {
        $b = new PayloadBuilderPractitioner;
        $this->assertSame($b, $b->setId('prac-1'));
        $this->assertSame($b, $b->setActive(true));
    }

    public function test_add_qualification(): void
    {
        $cc = new CodeableConcept();
        $cc->addCoding(new \Satusehat\Integration\DataType\Coding(
            'http://terminology.kemkes.go.id/CodeSystem/v3',
            'STR',
            'Surat Tanda Registrasi'
        ));
        $id = new \Satusehat\Integration\DataType\Identifier(
            'http://example.org/qual',
            '12345/2019'
        );
        $payload = (new PayloadBuilderPractitioner)
            ->addQualification($id, $cc, '2019-01-01', null)
            ->build();

        $this->assertArrayHasKey('qualification', $payload);
    }
}

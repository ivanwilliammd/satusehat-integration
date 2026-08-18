<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderPractitioner;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class PractitionerTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderPractitioner;

        $payload = $builder->build();

        $this->assertSame('Practitioner', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->setId('prac-123');

        $payload = $builder->build();

        $this->assertSame('prac-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/practitioner', 'N10000001');
        $builder = new PayloadBuilderPractitioner;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('N10000001', $payload['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->setActive(true);

        $payload = $builder->build();

        $this->assertTrue($payload['active']);
    }

    public function test_add_name()
    {
        $name = new HumanName('Dr. Siti Rahayu');
        $builder = new PayloadBuilderPractitioner;
        $builder->addName($name);

        $payload = $builder->build();

        $this->assertArrayHasKey('name', $payload);
        $this->assertSame('Dr. Siti Rahayu', $payload['name'][0]['text']);
    }

    public function test_add_telecom()
    {
        $telecom = new ContactPoint('phone', 'work', '0213456789');
        $builder = new PayloadBuilderPractitioner;
        $builder->addTelecom($telecom);

        $payload = $builder->build();

        $this->assertArrayHasKey('telecom', $payload);
        $this->assertSame('0213456789', $payload['telecom'][0]['value']);
    }

    public function test_add_address()
    {
        $address = new Address('work');
        $address->addLine('Jl. HR Rasuna Said');
        $address->setCity('Jakarta');

        $builder = new PayloadBuilderPractitioner;
        $builder->addAddress($address);

        $payload = $builder->build();

        $this->assertArrayHasKey('address', $payload);
        $this->assertSame('Jakarta', $payload['address'][0]['city']);
    }

    public function test_set_gender()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->setGender('female');

        $payload = $builder->build();

        $this->assertSame('female', $payload['gender']);
    }

    public function test_set_birth_date()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->setBirthDate('1980-03-20');

        $payload = $builder->build();

        $this->assertSame('1980-03-20', $payload['birthDate']);
    }

    public function test_add_photo()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->addPhoto('https://example.com/photo.jpg', 'image/jpeg');

        $payload = $builder->build();

        $this->assertArrayHasKey('photo', $payload);
        $this->assertSame('https://example.com/photo.jpg', $payload['photo'][0]['url']);
        $this->assertSame('image/jpeg', $payload['photo'][0]['contentType']);
    }

    public function test_add_photo_without_content_type()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->addPhoto('https://example.com/photo.jpg');

        $payload = $builder->build();

        $this->assertArrayNotHasKey('contentType', $payload['photo'][0]);
    }

    public function test_add_qualification()
    {
        $identifier = new Identifier('http://example.com/qual', 'SIP001');
        $coding = new Coding('http://terminology.hl7.org/CodeSystem/v2-0360', 'MD', 'Doctor of Medicine');
        $code = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderPractitioner;
        $builder->addQualification($identifier, $code, '2020-01-01');

        $payload = $builder->build();

        $this->assertArrayHasKey('qualification', $payload);
        $this->assertSame('SIP001', $payload['qualification'][0]['identifier'][0]['value']);
        $this->assertSame('MD', $payload['qualification'][0]['code']['coding'][0]['code']);
        $this->assertSame('2020-01-01', $payload['qualification'][0]['period']['start']);
    }

    public function test_add_qualification_with_issuer()
    {
        $identifier = new Identifier('http://example.com/qual', 'SIP002');
        $coding = new Coding('http://terminology.hl7.org/CodeSystem/v2-0360', 'MD', null);
        $code = (new CodeableConcept())->addCoding($coding);
        $issuer = new Reference('Organization/org-123');

        $builder = new PayloadBuilderPractitioner;
        $builder->addQualification($identifier, $code, null, $issuer);

        $payload = $builder->build();

        $this->assertSame('Organization/org-123', $payload['qualification'][0]['issuer']['reference']);
    }

    public function test_add_communication()
    {
        $coding = new Coding('urn:ietf:bcp:47', 'id-ID', 'Indonesian');
        $language = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderPractitioner;
        $builder->addCommunication($language, true);

        $payload = $builder->build();

        $this->assertArrayHasKey('communication', $payload);
        $this->assertSame('id-ID', $payload['communication'][0]['language']['coding'][0]['code']);
        $this->assertTrue($payload['communication'][0]['preferred']);
    }

    public function test_add_communication_without_preferred()
    {
        $coding = new Coding('urn:ietf:bcp:47', 'en-US', 'English');
        $language = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderPractitioner;
        $builder->addCommunication($language);

        $payload = $builder->build();

        $this->assertArrayNotHasKey('preferred', $payload['communication'][0]);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_integer()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->addExtension('http://example.com/ext', 42, 'integer');

        $payload = $builder->build();

        $this->assertSame(42, $payload['extension'][0]['valueInteger']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderPractitioner;
        $result = $builder->setId('prac-1')
                          ->setActive(true)
                          ->setGender('female')
                          ->addName(new HumanName('Dr. X'));

        $this->assertInstanceOf(PayloadBuilderPractitioner::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderPractitioner;
        $builder->setId('prac-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('active', $payload);
    }
}

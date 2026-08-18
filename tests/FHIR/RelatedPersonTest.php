<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderRelatedPerson;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class RelatedPersonTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderRelatedPerson;

        $payload = $builder->build();

        $this->assertSame('RelatedPerson', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setId('relper-123');

        $payload = $builder->build();

        $this->assertSame('relper-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/relatedperson', 'RP001');
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('RP001', $payload['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setActive(true);

        $payload = $builder->build();

        $this->assertTrue($payload['active']);
    }

    public function test_set_patient()
    {
        $patient = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setPatient($patient);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['patient']['reference']);
    }

    public function test_add_relationship()
    {
        $relationship = new CodeableConcept;
        $relationship->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v2-0131', 'MTH', 'Mother'));

        $builder = new PayloadBuilderRelatedPerson;
        $builder->addRelationship($relationship);

        $payload = $builder->build();

        $this->assertArrayHasKey('relationship', $payload);
        $this->assertSame('MTH', $payload['relationship'][0]['coding'][0]['code']);
    }

    public function test_add_name()
    {
        $name = new HumanName(null, [], null, 'Siti Aminah');
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addName($name);

        $payload = $builder->build();

        $this->assertArrayHasKey('name', $payload);
        $this->assertSame('Siti Aminah', $payload['name'][0]['text']);
    }

    public function test_add_telecom()
    {
        $telecom = new ContactPoint('phone', '081234999999', 'home');
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addTelecom($telecom);

        $payload = $builder->build();

        $this->assertArrayHasKey('telecom', $payload);
        $this->assertSame('081234999999', $payload['telecom'][0]['value']);
    }

    public function test_set_gender()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setGender('female');

        $payload = $builder->build();

        $this->assertSame('female', $payload['gender']);
    }

    public function test_set_birth_date()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setBirthDate('1970-03-15');

        $payload = $builder->build();

        $this->assertSame('1970-03-15', $payload['birthDate']);
    }

    public function test_add_address()
    {
        $address = new Address();
        $address->use = 'home';
        $address->line[] = 'Jl. Merdeka No. 5';
        $address->city = 'Bandung';
        $address->postalCode = '40111';

        $builder = new PayloadBuilderRelatedPerson;
        $builder->addAddress($address);

        $payload = $builder->build();

        $this->assertArrayHasKey('address', $payload);
        $this->assertSame('Bandung', $payload['address'][0]['city']);
        $this->assertSame('40111', $payload['address'][0]['postalCode']);
    }

    public function test_add_communication_default_preferred()
    {
        $language = new CodeableConcept;
        $language->addCoding(new Coding('urn:ietf:bcp:47', 'id-ID', 'Indonesian'));

        $builder = new PayloadBuilderRelatedPerson;
        $builder->addCommunication($language);

        $payload = $builder->build();

        $this->assertArrayHasKey('communication', $payload);
        $this->assertSame('id-ID', $payload['communication'][0]['language']['coding'][0]['code']);
        $this->assertTrue($payload['communication'][0]['preferred']);
    }

    public function test_add_communication_explicit_preferred()
    {
        $language = new CodeableConcept;
        $language->addCoding(new Coding('urn:ietf:bcp:47', 'en-US', 'English'));

        $builder = new PayloadBuilderRelatedPerson;
        $builder->addCommunication($language, false);

        $payload = $builder->build();

        $this->assertFalse($payload['communication'][0]['preferred']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_boolean()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addExtension('http://example.com/ext', true, 'boolean');

        $payload = $builder->build();

        $this->assertTrue($payload['extension'][0]['valueBoolean']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $result = $builder->setId('rp-1')
                          ->setActive(true)
                          ->setPatient(new Reference('Patient/1'))
                          ->setGender('female');

        $this->assertInstanceOf(PayloadBuilderRelatedPerson::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderRelatedPerson;
        $builder->setId('rp-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('active', $payload);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderPatient;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class PatientTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderPatient;

        $payload = $builder->build();

        $this->assertSame('Patient', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setId('patient-123');

        $payload = $builder->build();

        $this->assertSame('patient-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/patient', '100000030009');
        $builder = new PayloadBuilderPatient;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('100000030009', $payload['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setActive(true);

        $payload = $builder->build();

        $this->assertTrue($payload['active']);
    }

    public function test_add_name()
    {
        $name = new HumanName(null, [], null, 'Budi Santoso');
        $builder = new PayloadBuilderPatient;
        $builder->addName($name);

        $payload = $builder->build();

        $this->assertArrayHasKey('name', $payload);
        $this->assertSame('Budi Santoso', $payload['name'][0]['text']);
    }

    public function test_set_gender()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setGender('male');

        $payload = $builder->build();

        $this->assertSame('male', $payload['gender']);
    }

    public function test_set_birth_date()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setBirthDate('1990-05-15');

        $payload = $builder->build();

        $this->assertSame('1990-05-15', $payload['birthDate']);
    }

    public function test_set_deceased_boolean()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setDeceasedBoolean(false);

        $payload = $builder->build();

        $this->assertFalse($payload['deceasedBoolean']);
    }

    public function test_set_deceased_date_time()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setDeceasedDateTime('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['deceasedDateTime']);
    }

    public function test_set_multiple_birth_boolean()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setMultipleBirthBoolean(true);

        $payload = $builder->build();

        $this->assertTrue($payload['multipleBirthBoolean']);
    }

    public function test_set_multiple_birth_integer()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setMultipleBirthInteger(3);

        $payload = $builder->build();

        $this->assertSame(3, $payload['multipleBirthInteger']);
    }

    public function test_add_address()
    {
        $address = new Address;
        $address->use = 'home';
        $address->line[] = 'Jl. Sudirman No. 123';
        $address->city = 'Jakarta';
        $address->postalCode = '10220';

        $builder = new PayloadBuilderPatient;
        $builder->addAddress($address);

        $payload = $builder->build();

        $this->assertArrayHasKey('address', $payload);
        $this->assertSame('Jakarta', $payload['address'][0]['city']);
    }

    public function test_add_telecom()
    {
        $telecom = new ContactPoint('phone', '081234567890', 'home');
        $builder = new PayloadBuilderPatient;
        $builder->addTelecom($telecom);

        $payload = $builder->build();

        $this->assertArrayHasKey('telecom', $payload);
        $this->assertSame('081234567890', $payload['telecom'][0]['value']);
    }

    public function test_set_marital_status()
    {
        $coding = new Coding('http://terminology.hl7.org/CodeSystem/v3-MaritalStatus', 'M', 'Married');
        $maritalStatus = (new CodeableConcept)->addCoding($coding);

        $builder = new PayloadBuilderPatient;
        $builder->setMaritalStatus($maritalStatus);

        $payload = $builder->build();

        $this->assertSame('M', $payload['maritalStatus']['coding'][0]['code']);
    }

    public function test_add_communication()
    {
        $coding = new Coding('urn:ietf:bcp:47', 'id-ID', 'Indonesian');
        $language = (new CodeableConcept)->addCoding($coding);

        $builder = new PayloadBuilderPatient;
        $builder->addCommunication($language, true);

        $payload = $builder->build();

        $this->assertArrayHasKey('communication', $payload);
        $this->assertSame('id-ID', $payload['communication'][0]['language']['coding'][0]['code']);
        $this->assertTrue($payload['communication'][0]['preferred']);
    }

    public function test_add_communication_not_preferred()
    {
        $coding = new Coding('urn:ietf:bcp:47', 'en-US', 'English');
        $language = (new CodeableConcept)->addCoding($coding);

        $builder = new PayloadBuilderPatient;
        $builder->addCommunication($language, false);

        $payload = $builder->build();

        $this->assertFalse($payload['communication'][0]['preferred']);
    }

    public function test_add_contact()
    {
        $relCoding = new Coding('http://terminology.hl7.org/CodeSystem/v2-0131', 'C', 'Emergency Contact');
        $relationship = (new CodeableConcept)->addCoding($relCoding);
        $name = new HumanName(null, [], null, 'Ani Wijaya');
        $telecom = new ContactPoint('phone', '081234999999', 'home');

        $builder = new PayloadBuilderPatient;
        $builder->addContact($relationship, $name, $telecom);

        $payload = $builder->build();

        $this->assertArrayHasKey('contact', $payload);
        $this->assertSame('Ani Wijaya', $payload['contact'][0]['name']['text']);
        $this->assertSame('C', $payload['contact'][0]['relationship'][0]['coding'][0]['code']);
    }

    public function test_add_contact_with_address()
    {
        $relCoding = new Coding('http://terminology.hl7.org/CodeSystem/v2-0131', 'C', 'Emergency Contact');
        $relationship = (new CodeableConcept)->addCoding($relCoding);
        $name = new HumanName(null, [], null, 'Ani Wijaya');
        $telecom = new ContactPoint('phone', '081234999999', 'home');
        $address = new Address;
        $address->use = 'home';
        $address->line[] = 'Jl. Merdeka No. 5';
        $address->city = 'Bandung';

        $builder = new PayloadBuilderPatient;
        $builder->addContact($relationship, $name, $telecom, $address);

        $payload = $builder->build();

        $this->assertArrayHasKey('address', $payload['contact'][0]);
        $this->assertSame('Bandung', $payload['contact'][0]['address']['city']);
    }

    public function test_add_contact_with_organization()
    {
        $relCoding = new Coding('http://terminology.hl7.org/CodeSystem/v2-0131', 'C', 'Emergency Contact');
        $relationship = (new CodeableConcept)->addCoding($relCoding);
        $name = new HumanName(null, [], null, 'Ani Wijaya');
        $telecom = new ContactPoint('phone', '081234999999', 'home');
        $organization = new Reference('Organization/org-1', 'Klinik Sehat');

        $builder = new PayloadBuilderPatient;
        $builder->addContact($relationship, $name, $telecom, null, $organization);

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['contact'][0]['organization']['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderPatient;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_boolean()
    {
        $builder = new PayloadBuilderPatient;
        $builder->addExtension('http://example.com/ext', true, 'boolean');

        $payload = $builder->build();

        $this->assertTrue($payload['extension'][0]['valueBoolean']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderPatient;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderPatient;
        $result = $builder->setId('p-1')
                          ->setActive(true)
                          ->setGender('male')
                          ->setBirthDate('1990-01-01');

        $this->assertInstanceOf(PayloadBuilderPatient::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderPatient;
        $builder->setId('patient-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('active', $payload);
    }
}

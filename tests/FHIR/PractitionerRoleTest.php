<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderPractitionerRole;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

class PractitionerRoleTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderPractitionerRole;

        $payload = $builder->build();

        $this->assertSame('PractitionerRole', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->setId('prole-123');

        $payload = $builder->build();

        $this->assertSame('prole-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/practitioner-role', 'PR001');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('PR001', $payload['identifier'][0]['value']);
    }

    public function test_set_active()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->setActive(true);

        $payload = $builder->build();

        $this->assertTrue($payload['active']);
    }

    public function test_set_practitioner()
    {
        $practitioner = new Reference('Practitioner/N10000001', 'Dr. Siti Rahayu');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->setPractitioner($practitioner);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['practitioner']['reference']);
    }

    public function test_set_organization()
    {
        $organization = new Reference('Organization/org-123', 'RS Sehat');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->setOrganization($organization);

        $payload = $builder->build();

        $this->assertSame('Organization/org-123', $payload['organization']['reference']);
    }

    public function test_add_code()
    {
        $code = new CodeableConcept;
        $code->addCoding('http://terminology.hl7.org/CodeSystem/v2-0360', 'MD', 'Doctor of Medicine');

        $builder = new PayloadBuilderPractitionerRole;
        $builder->addCode($code);

        $payload = $builder->build();

        $this->assertArrayHasKey('code', $payload);
        $this->assertSame('MD', $payload['code'][0]['coding'][0]['code']);
    }

    public function test_add_specialty()
    {
        $specialty = new CodeableConcept;
        $specialty->addCoding('http://snomed.info/sct', '394814009', 'General Practice');

        $builder = new PayloadBuilderPractitionerRole;
        $builder->addSpecialty($specialty);

        $payload = $builder->build();

        $this->assertArrayHasKey('specialty', $payload);
        $this->assertSame('394814009', $payload['specialty'][0]['coding'][0]['code']);
    }

    public function test_add_location()
    {
        $location = new Reference('Location/loc-123', 'Ruang 101');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addLocation($location);

        $payload = $builder->build();

        $this->assertArrayHasKey('location', $payload);
        $this->assertSame('Location/loc-123', $payload['location'][0]['reference']);
    }

    public function test_add_healthcare_service()
    {
        $service = new Reference('HealthcareService/hcs-1');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addHealthcareService($service);

        $payload = $builder->build();

        $this->assertArrayHasKey('healthcareService', $payload);
        $this->assertSame('HealthcareService/hcs-1', $payload['healthcareService'][0]['reference']);
    }

    public function test_add_telecom()
    {
        $telecom = new ContactPoint('phone', 'work', '0213456789');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addTelecom($telecom);

        $payload = $builder->build();

        $this->assertArrayHasKey('telecom', $payload);
        $this->assertSame('0213456789', $payload['telecom'][0]['value']);
    }

    public function test_add_available_time_full()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addAvailableTime(['mon', 'tue', 'wed'], '08:00', '17:00', 'Senin-Jumat kerja');

        $payload = $builder->build();

        $this->assertArrayHasKey('availableTime', $payload);
        $this->assertSame(['mon', 'tue', 'wed'], $payload['availableTime'][0]['daysOfWeek']);
        $this->assertSame('08:00', $payload['availableTime'][0]['availableStartTime']);
        $this->assertSame('17:00', $payload['availableTime'][0]['availableEndTime']);
        $this->assertSame('Senin-Jumat kerja', $payload['availableTime'][0]['description']);
    }

    public function test_add_available_time_partial()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addAvailableTime(['sat', 'sun']);

        $payload = $builder->build();

        $this->assertArrayHasKey('availableTime', $payload);
        $this->assertSame(['sat', 'sun'], $payload['availableTime'][0]['daysOfWeek']);
    }

    public function test_add_not_available()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addNotAvailable('Cuti tahunan');

        $payload = $builder->build();

        $this->assertArrayHasKey('notAvailable', $payload);
        $this->assertSame('Cuti tahunan', $payload['notAvailable'][0]['description']);
    }

    public function test_add_not_available_with_during()
    {
        $during = new Period('2024-12-25', '2025-01-05');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addNotAvailable('Libur Natal dan Tahun Baru', $during);

        $payload = $builder->build();

        $this->assertSame('2024-12-25', $payload['notAvailable'][0]['during']['start']);
    }

    public function test_add_endpoint()
    {
        $endpoint = new Reference('Endpoint/ep-1');
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addEndpoint($endpoint);

        $payload = $builder->build();

        $this->assertArrayHasKey('endpoint', $payload);
        $this->assertSame('Endpoint/ep-1', $payload['endpoint'][0]['reference']);
    }

    public function test_add_availability_exceptions()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addAvailabilityExceptions('Hari Libur Nasional tutup');

        $payload = $builder->build();

        $this->assertSame('Hari Libur Nasional tutup', $payload['availabilityExceptions']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addExtension('http://example.com/ext', 'some-value', 'string');

        $payload = $builder->build();

        $this->assertArrayHasKey('extension', $payload);
        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('some-value', $payload['extension'][0]['valueString']);
    }

    public function test_add_extension_with_boolean()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addExtension('http://example.com/ext', false, 'boolean');

        $payload = $builder->build();

        $this->assertFalse($payload['extension'][0]['valueBoolean']);
    }

    public function test_add_extension_default_value_type()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->addExtension('http://example.com/ext', 'default-string');

        $payload = $builder->build();

        $this->assertSame('default-string', $payload['extension'][0]['valueString']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $result = $builder->setId('prole-1')
                          ->setActive(true)
                          ->setPractitioner(new Reference('Practitioner/N10000001'))
                          ->setOrganization(new Reference('Organization/org-1'));

        $this->assertInstanceOf(PayloadBuilderPractitionerRole::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderPractitionerRole;
        $builder->setId('prole-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('active', $payload);
    }
}

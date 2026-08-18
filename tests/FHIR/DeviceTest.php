<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderDevice;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class DeviceTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderDevice;

        $payload = $builder->build();

        $this->assertSame('Device', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderDevice;
        $builder->setId('dev-123');

        $payload = $builder->build();

        $this->assertSame('dev-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/device', 'DEV001');
        $builder = new PayloadBuilderDevice;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('DEV001', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderDevice;
        $builder->setStatus('active');

        $payload = $builder->build();

        $this->assertSame('active', $payload['status']);
    }

    public function test_set_manufacturer()
    {
        $builder = new PayloadBuilderDevice;
        $builder->setManufacturer('Medtronic');

        $payload = $builder->build();

        $this->assertSame('Medtronic', $payload['manufacturer']);
    }

    public function test_add_device_name()
    {
        $builder = new PayloadBuilderDevice;
        $builder->addDeviceName('Glucose Monitor Pro', 'model-name');

        $payload = $builder->build();

        $this->assertArrayHasKey('deviceName', $payload);
        $this->assertSame('Glucose Monitor Pro', $payload['deviceName'][0]['name']);
        $this->assertSame('model-name', $payload['deviceName'][0]['type']);
    }

    public function test_add_device_name_default_type()
    {
        $builder = new PayloadBuilderDevice;
        $builder->addDeviceName('My Monitor');

        $payload = $builder->build();

        $this->assertSame('user-friendly-name', $payload['deviceName'][0]['type']);
    }

    public function test_add_multiple_device_names()
    {
        $builder = new PayloadBuilderDevice;
        $builder->addDeviceName('Model X1', 'model-name')
                ->addDeviceName('Model X1 Pro', 'manufacturer-name');

        $payload = $builder->build();

        $this->assertCount(2, $payload['deviceName']);
    }

    public function test_set_type()
    {
        $coding = new Coding('http://snomed.info/sct', 'device-type-1', 'Blood pressure monitor');
        $type = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderDevice;
        $builder->setType($type);

        $payload = $builder->build();

        $this->assertSame('Blood pressure monitor', $payload['type']['coding'][0]['display']);
    }

    public function test_set_patient()
    {
        $patient = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderDevice;
        $builder->setPatient($patient);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['patient']['reference']);
        $this->assertSame('Budi Santoso', $payload['patient']['display']);
    }

    public function test_set_owner()
    {
        $owner = new Reference('Organization/org-1', 'RS Umum Sehat');
        $builder = new PayloadBuilderDevice;
        $builder->setOwner($owner);

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['owner']['reference']);
    }

    public function test_set_location()
    {
        $location = new Reference('Location/loc-1', 'Ruang ICU');
        $builder = new PayloadBuilderDevice;
        $builder->setLocation($location);

        $payload = $builder->build();

        $this->assertSame('Location/loc-1', $payload['location']['reference']);
    }

    public function test_set_serial_number()
    {
        $builder = new PayloadBuilderDevice;
        $builder->setSerialNumber('SN-2024-001');

        $payload = $builder->build();

        $this->assertSame('SN-2024-001', $payload['serialNumber']);
    }

    public function test_add_note()
    {
        $note = new Annotation('Practitioner/N10000001', 'Kalibrasi dilakukan bulan lalu', '2024-01-15T10:00:00+00:00');
        $builder = new PayloadBuilderDevice;
        $builder->addNote($note);

        $payload = $builder->build();

        $this->assertArrayHasKey('note', $payload);
        $this->assertSame('Kalibrasi dilakukan bulan lalu', $payload['note'][0]['text']);
    }

    public function test_add_multiple_notes()
    {
        $builder = new PayloadBuilderDevice;
        $builder->addNote(new Annotation('Practitioner/N1', 'Note 1'))
                ->addNote(new Annotation('Practitioner/N2', 'Note 2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['note']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderDevice;
        $result = $builder->setId('dev-1')
                          ->setStatus('active')
                          ->setManufacturer('Acme Corp')
                          ->addDeviceName('Heart Rate Monitor')
                          ->setSerialNumber('SN123')
                          ->setPatient(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderDevice::class, $result);
    }

    public function test_full_device_payload()
    {
        $builder = new PayloadBuilderDevice;
        $builder->setId('dev-dm-1')
                ->setStatus('active')
                ->setManufacturer('Medtronic')
                ->addDeviceName('Insulin Pump X1', 'model-name')
                ->addDeviceName('Medtronic Insulin Pump', 'manufacturer-name')
                ->setType((new CodeableConcept())->addCoding(new Coding(null, 'insulin-pump', 'Insulin Pump')))
                ->setPatient(new Reference('Patient/100000030009', 'Budi Santoso'))
                ->setOwner(new Reference('Organization/org-1'))
                ->setLocation(new Reference('Location/loc-1', 'Ruang Rawat Inap'))
                ->setSerialNumber('SN-MDT-2024-001')
                ->addNote(new Annotation('Device calibrated on 2024-01-01'));

        $payload = $builder->build();

        $this->assertSame('Device', $payload['resourceType']);
        $this->assertSame('dev-dm-1', $payload['id']);
        $this->assertSame('active', $payload['status']);
        $this->assertSame('Medtronic', $payload['manufacturer']);
        $this->assertCount(2, $payload['deviceName']);
        $this->assertSame('Patient/100000030009', $payload['patient']['reference']);
        $this->assertSame('SN-MDT-2024-001', $payload['serialNumber']);
        $this->assertCount(1, $payload['note']);
    }
}

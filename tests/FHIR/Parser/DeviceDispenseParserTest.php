<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\DeviceDispenseParser;

/**
 * DeviceDispenseParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class DeviceDispenseParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/DeviceDispense.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('DeviceDispense', $data['resourceType']);

        $parser = new DeviceDispenseParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['basedOn'], $parser->getBasedOn(), 'elemen basedOn');
        $this->assertSame($data['device'], $parser->getDevice(), 'elemen device');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['receiver'], $parser->getReceiver(), 'elemen receiver');
        $this->assertSame($data['performer'], $parser->getPerformer(), 'elemen performer');
        $this->assertSame($data['whenPrepared'], $parser->getWhenPrepared(), 'elemen whenPrepared');
        $this->assertSame($data['whenHandedOver'], $parser->getWhenHandedOver(), 'elemen whenHandedOver');
        $this->assertSame($data['destination'], $parser->getDestination(), 'elemen destination');
        $this->assertSame($data['note'], $parser->getNote(), 'elemen note');
    }
}

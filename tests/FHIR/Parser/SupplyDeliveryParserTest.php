<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\SupplyDeliveryParser;

/**
 * SupplyDeliveryParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class SupplyDeliveryParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/SupplyDelivery.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('SupplyDelivery', $data['resourceType']);

        $parser = new SupplyDeliveryParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['basedOn'], $parser->getBasedOn(), 'elemen basedOn');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['suppliedItem'], $parser->getSuppliedItem(), 'elemen suppliedItem');
        $this->assertSame($data['occurrenceDateTime'], $parser->getOccurrenceDateTime(), 'elemen occurrenceDateTime');
        $this->assertSame($data['supplier'], $parser->getSupplier(), 'elemen supplier');
        $this->assertSame($data['destination'], $parser->getDestination(), 'elemen destination');
        $this->assertSame($data['receiver'], $parser->getReceiver(), 'elemen receiver');
    }
}

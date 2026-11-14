<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\MedicationParser;

/**
 * MedicationParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class MedicationParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Medication.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Medication', $data['resourceType']);

        $parser = new MedicationParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['meta'], $parser->getMeta(), 'elemen meta');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['code'], $parser->getCode(), 'elemen code');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['manufacturer'], $parser->getManufacturer(), 'elemen manufacturer');
        $this->assertSame($data['form'], $parser->getForm(), 'elemen form');
        $this->assertSame($data['ingredient'], $parser->getIngredient(), 'elemen ingredient');
        $this->assertSame($data['batch'], $parser->getBatch(), 'elemen batch');
        $this->assertSame($data['extension'], $parser->getExtension(), 'elemen extension');
    }
}

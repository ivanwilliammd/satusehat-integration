<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ImmunizationParser;

/**
 * ImmunizationParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ImmunizationParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Immunization.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Immunization', $data['resourceType']);

        $parser = new ImmunizationParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['vaccineCode'], $parser->getVaccineCode(), 'elemen vaccineCode');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['occurrenceDateTime'], $parser->getOccurrenceDateTime(), 'elemen occurrenceDateTime');
        $this->assertSame($data['recorded'], $parser->getRecorded(), 'elemen recorded');
        $this->assertSame($data['reaction'], $parser->getReaction(), 'elemen reaction');
        $this->assertSame($data['primarySource'], $parser->getPrimarySource(), 'elemen primarySource');
        $this->assertSame($data['location'], $parser->getLocation(), 'elemen location');
        $this->assertSame($data['lotNumber'], $parser->getLotNumber(), 'elemen lotNumber');
        $this->assertSame($data['expirationDate'], $parser->getExpirationDate(), 'elemen expirationDate');
        $this->assertSame($data['route'], $parser->getRoute(), 'elemen route');
        $this->assertSame($data['doseQuantity'], $parser->getDoseQuantity(), 'elemen doseQuantity');
        $this->assertSame($data['performer'], $parser->getPerformer(), 'elemen performer');
        $this->assertSame($data['reasonCode'], $parser->getReasonCode(), 'elemen reasonCode');
        $this->assertSame($data['protocolApplied'], $parser->getProtocolApplied(), 'elemen protocolApplied');
    }
}

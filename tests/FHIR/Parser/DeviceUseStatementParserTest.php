<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\DeviceUseStatementParser;

/**
 * DeviceUseStatementParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class DeviceUseStatementParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/DeviceUseStatement.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('DeviceUseStatement', $data['resourceType']);

        $parser = new DeviceUseStatementParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['device'], $parser->getDevice(), 'elemen device');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['timingPeriod'], $parser->getTimingPeriod(), 'elemen timingPeriod');
        $this->assertSame($data['recordedOn'], $parser->getRecordedOn(), 'elemen recordedOn');
        $this->assertSame($data['source'], $parser->getSource(), 'elemen source');
        $this->assertSame($data['reasonCode'], $parser->getReasonCode(), 'elemen reasonCode');
        $this->assertSame($data['note'], $parser->getNote(), 'elemen note');
    }
}

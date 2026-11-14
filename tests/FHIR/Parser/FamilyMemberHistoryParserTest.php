<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\FamilyMemberHistoryParser;

/**
 * FamilyMemberHistoryParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class FamilyMemberHistoryParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/FamilyMemberHistory.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('FamilyMemberHistory', $data['resourceType']);

        $parser = new FamilyMemberHistoryParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['relationship'], $parser->getRelationship(), 'elemen relationship');
        $this->assertSame($data['deceasedBoolean'], $parser->getDeceasedBoolean(), 'elemen deceasedBoolean');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['date'], $parser->getDate(), 'elemen date');
        $this->assertSame($data['condition'], $parser->getCondition(), 'elemen condition');
    }
}

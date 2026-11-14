<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\AllergyIntoleranceParser;

/**
 * AllergyIntoleranceParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class AllergyIntoleranceParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/AllergyIntolerance.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('AllergyIntolerance', $data['resourceType']);

        $parser = new AllergyIntoleranceParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['clinicalStatus'], $parser->getClinicalStatus(), 'elemen clinicalStatus');
        $this->assertSame($data['verificationStatus'], $parser->getVerificationStatus(), 'elemen verificationStatus');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['code'], $parser->getCode(), 'elemen code');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['recordedDate'], $parser->getRecordedDate(), 'elemen recordedDate');
        $this->assertSame($data['recorder'], $parser->getRecorder(), 'elemen recorder');
    }
}

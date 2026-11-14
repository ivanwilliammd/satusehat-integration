<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\MedicationStatementParser;

/**
 * MedicationStatementParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class MedicationStatementParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/MedicationStatement.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('MedicationStatement', $data['resourceType']);

        $parser = new MedicationStatementParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['contained'], $parser->getContained(), 'elemen contained');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['medicationReference'], $parser->getMedicationReference(), 'elemen medicationReference');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['dosage'], $parser->getDosage(), 'elemen dosage');
        $this->assertSame($data['effectiveDateTime'], $parser->getEffectiveDateTime(), 'elemen effectiveDateTime');
        $this->assertSame($data['dateAsserted'], $parser->getDateAsserted(), 'elemen dateAsserted');
        $this->assertSame($data['informationSource'], $parser->getInformationSource(), 'elemen informationSource');
        $this->assertSame($data['context'], $parser->getContext(), 'elemen context');
    }
}

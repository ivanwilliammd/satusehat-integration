<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\MedicationAdministrationParser;

/**
 * MedicationAdministrationParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class MedicationAdministrationParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/MedicationAdministration.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('MedicationAdministration', $data['resourceType']);

        $parser = new MedicationAdministrationParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['contained'], $parser->getContained(), 'elemen contained');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['medicationReference'], $parser->getMedicationReference(), 'elemen medicationReference');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['context'], $parser->getContext(), 'elemen context');
        $this->assertSame($data['effectivePeriod'], $parser->getEffectivePeriod(), 'elemen effectivePeriod');
        $this->assertSame($data['performer'], $parser->getPerformer(), 'elemen performer');
        $this->assertSame($data['reasonCode'], $parser->getReasonCode(), 'elemen reasonCode');
        $this->assertSame($data['request'], $parser->getRequest(), 'elemen request');
        $this->assertSame($data['dosage'], $parser->getDosage(), 'elemen dosage');
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ClinicalImpressionParser;

/**
 * ClinicalImpressionParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ClinicalImpressionParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/ClinicalImpression.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('ClinicalImpression', $data['resourceType']);

        $parser = new ClinicalImpressionParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['code'], $parser->getCode(), 'elemen code');
        $this->assertSame($data['description'], $parser->getDescription(), 'elemen description');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['effectiveDateTime'], $parser->getEffectiveDateTime(), 'elemen effectiveDateTime');
        $this->assertSame($data['date'], $parser->getDate(), 'elemen date');
        $this->assertSame($data['assessor'], $parser->getAssessor(), 'elemen assessor');
        $this->assertSame($data['problem'], $parser->getProblem(), 'elemen problem');
        $this->assertSame($data['investigation'], $parser->getInvestigation(), 'elemen investigation');
        $this->assertSame($data['summary'], $parser->getSummary(), 'elemen summary');
        $this->assertSame($data['finding'], $parser->getFinding(), 'elemen finding');
        $this->assertSame($data['prognosisCodeableConcept'], $parser->getPrognosisCodeableConcept(), 'elemen prognosisCodeableConcept');
    }
}

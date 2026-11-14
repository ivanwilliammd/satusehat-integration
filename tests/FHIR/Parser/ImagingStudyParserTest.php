<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ImagingStudyParser;

/**
 * ImagingStudyParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ImagingStudyParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/ImagingStudy.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('ImagingStudy', $data['resourceType']);

        $parser = new ImagingStudyParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['id'], $parser->getId(), 'elemen id');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['modality'], $parser->getModality(), 'elemen modality');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['started'], $parser->getStarted(), 'elemen started');
        $this->assertSame($data['basedOn'], $parser->getBasedOn(), 'elemen basedOn');
        $this->assertSame($data['numberOfSeries'], $parser->getNumberOfSeries(), 'elemen numberOfSeries');
        $this->assertSame($data['numberOfInstances'], $parser->getNumberOfInstances(), 'elemen numberOfInstances');
        $this->assertSame($data['description'], $parser->getDescription(), 'elemen description');
        $this->assertSame($data['series'], $parser->getSeries(), 'elemen series');
    }
}

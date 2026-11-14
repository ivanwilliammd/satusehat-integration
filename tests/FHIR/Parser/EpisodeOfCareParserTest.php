<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\EpisodeOfCareParser;

/**
 * EpisodeOfCareParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class EpisodeOfCareParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/EpisodeOfCare.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('EpisodeOfCare', $data['resourceType']);

        $parser = new EpisodeOfCareParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['id'], $parser->getId(), 'elemen id');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['statusHistory'], $parser->getStatusHistory(), 'elemen statusHistory');
        $this->assertSame($data['type'], $parser->getType(), 'elemen type');
        $this->assertSame($data['diagnosis'], $parser->getDiagnosis(), 'elemen diagnosis');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['managingOrganization'], $parser->getManagingOrganization(), 'elemen managingOrganization');
        $this->assertSame($data['period'], $parser->getPeriod(), 'elemen period');
        $this->assertSame($data['careManager'], $parser->getCareManager(), 'elemen careManager');
    }
}

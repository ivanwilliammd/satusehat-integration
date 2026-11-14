<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\CarePlanParser;

/**
 * CarePlanParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class CarePlanParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/CarePlan.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('CarePlan', $data['resourceType']);

        $parser = new CarePlanParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['title'], $parser->getTitle(), 'elemen title');
        $this->assertSame($data['description'], $parser->getDescription(), 'elemen description');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['intent'], $parser->getIntent(), 'elemen intent');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['period'], $parser->getPeriod(), 'elemen period');
        $this->assertSame($data['created'], $parser->getCreated(), 'elemen created');
        $this->assertSame($data['author'], $parser->getAuthor(), 'elemen author');
        $this->assertSame($data['activity'], $parser->getActivity(), 'elemen activity');
    }
}

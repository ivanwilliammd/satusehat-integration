<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\QuestionnaireResponseParser;

/**
 * QuestionnaireResponseParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class QuestionnaireResponseParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/QuestionnaireResponse.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('QuestionnaireResponse', $data['resourceType']);

        $parser = new QuestionnaireResponseParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['questionnaire'], $parser->getQuestionnaire(), 'elemen questionnaire');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['authored'], $parser->getAuthored(), 'elemen authored');
        $this->assertSame($data['author'], $parser->getAuthor(), 'elemen author');
        $this->assertSame($data['source'], $parser->getSource(), 'elemen source');
        $this->assertSame($data['item'], $parser->getItem(), 'elemen item');
    }
}

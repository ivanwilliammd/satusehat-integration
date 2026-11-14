<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\CommunicationRequestParser;

/**
 * CommunicationRequestParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class CommunicationRequestParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/CommunicationRequest.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('CommunicationRequest', $data['resourceType']);

        $parser = new CommunicationRequestParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['payload'], $parser->getPayload(), 'elemen payload');
        $this->assertSame($data['about'], $parser->getAbout(), 'elemen about');
        $this->assertSame($data['sender'], $parser->getSender(), 'elemen sender');
        $this->assertSame($data['recipient'], $parser->getRecipient(), 'elemen recipient');
    }
}

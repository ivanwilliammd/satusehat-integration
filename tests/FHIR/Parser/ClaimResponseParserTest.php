<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ClaimResponseParser;

/**
 * ClaimResponseParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ClaimResponseParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/ClaimResponse.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('ClaimResponse', $data['resourceType']);

        $parser = new ClaimResponseParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['type'], $parser->getType(), 'elemen type');
        $this->assertSame($data['use'], $parser->getUse(), 'elemen use');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['created'], $parser->getCreated(), 'elemen created');
        $this->assertSame($data['insurer'], $parser->getInsurer(), 'elemen insurer');
        $this->assertSame($data['requestor'], $parser->getRequestor(), 'elemen requestor');
        $this->assertSame($data['request'], $parser->getRequest(), 'elemen request');
        $this->assertSame($data['outcome'], $parser->getOutcome(), 'elemen outcome');
        $this->assertSame($data['payeeType'], $parser->getPayeeType(), 'elemen payeeType');
        $this->assertSame($data['adjudication'], $parser->getAdjudication(), 'elemen adjudication');
        $this->assertSame($data['item'], $parser->getItem(), 'elemen item');
        $this->assertSame($data['total'], $parser->getTotal(), 'elemen total');
        $this->assertSame($data['payment'], $parser->getPayment(), 'elemen payment');
    }
}

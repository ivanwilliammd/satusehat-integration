<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\SupplyRequestParser;

/**
 * SupplyRequestParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class SupplyRequestParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/SupplyRequest.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('SupplyRequest', $data['resourceType']);

        $parser = new SupplyRequestParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['itemReference'], $parser->getItemReference(), 'elemen itemReference');
        $this->assertSame($data['quantity'], $parser->getQuantity(), 'elemen quantity');
        $this->assertSame($data['authoredOn'], $parser->getAuthoredOn(), 'elemen authoredOn');
        $this->assertSame($data['requester'], $parser->getRequester(), 'elemen requester');
        $this->assertSame($data['supplier'], $parser->getSupplier(), 'elemen supplier');
        $this->assertSame($data['reasonCode'], $parser->getReasonCode(), 'elemen reasonCode');
    }
}

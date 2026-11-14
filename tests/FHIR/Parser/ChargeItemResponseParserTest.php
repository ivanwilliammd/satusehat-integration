<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ChargeItemResponseParser;

/**
 * ChargeItemResponseParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ChargeItemResponseParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/ChargeItemResponse.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('ChargeItemResponse', $data['resourceType']);

        $parser = new ChargeItemResponseParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['id'], $parser->getId(), 'elemen id');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['chargeItem'], $parser->getChargeItem(), 'elemen chargeItem');
        $this->assertSame($data['coverage'], $parser->getCoverage(), 'elemen coverage');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['payor'], $parser->getPayor(), 'elemen payor');
        $this->assertSame($data['quantity'], $parser->getQuantity(), 'elemen quantity');
        $this->assertSame($data['unitPriceOverride'], $parser->getUnitPriceOverride(), 'elemen unitPriceOverride');
        $this->assertSame($data['factorOverride'], $parser->getFactorOverride(), 'elemen factorOverride');
        $this->assertSame($data['overrideReason'], $parser->getOverrideReason(), 'elemen overrideReason');
        $this->assertSame($data['total'], $parser->getTotal(), 'elemen total');
        $this->assertSame($data['excess'], $parser->getExcess(), 'elemen excess');
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ChargeItemParser;

/**
 * ChargeItemParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ChargeItemParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/ChargeItem.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('ChargeItem', $data['resourceType']);

        $parser = new ChargeItemParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['code'], $parser->getCode(), 'elemen code');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['context'], $parser->getContext(), 'elemen context');
        $this->assertSame($data['occurrencePeriod'], $parser->getOccurrencePeriod(), 'elemen occurrencePeriod');
        $this->assertSame($data['performer'], $parser->getPerformer(), 'elemen performer');
        $this->assertSame($data['quantity'], $parser->getQuantity(), 'elemen quantity');
        $this->assertSame($data['account'], $parser->getAccount(), 'elemen account');
        $this->assertSame($data['extension'], $parser->getExtension(), 'elemen extension');
    }
}

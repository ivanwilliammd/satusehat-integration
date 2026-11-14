<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\CoverageParser;

/**
 * CoverageParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class CoverageParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Coverage.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Coverage', $data['resourceType']);

        $parser = new CoverageParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['type'], $parser->getType(), 'elemen type');
        $this->assertSame($data['subscriberId'], $parser->getSubscriberId(), 'elemen subscriberId');
        $this->assertSame($data['beneficiary'], $parser->getBeneficiary(), 'elemen beneficiary');
        $this->assertSame($data['payor'], $parser->getPayor(), 'elemen payor');
        $this->assertSame($data['class'], $parser->getClass(), 'elemen class');
        $this->assertSame($data['extension'], $parser->getExtension(), 'elemen extension');
    }
}

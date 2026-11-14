<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\AccountParser;

/**
 * AccountParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class AccountParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Account.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Account', $data['resourceType']);

        $parser = new AccountParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['type'], $parser->getType(), 'elemen type');
        $this->assertSame($data['name'], $parser->getName(), 'elemen name');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['servicePeriod'], $parser->getServicePeriod(), 'elemen servicePeriod');
        $this->assertSame($data['owner'], $parser->getOwner(), 'elemen owner');
        $this->assertSame($data['guarantor'], $parser->getGuarantor(), 'elemen guarantor');
        $this->assertSame($data['description'], $parser->getDescription(), 'elemen description');
    }
}

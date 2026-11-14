<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\CoverageEligibilityRequestParser;

/**
 * CoverageEligibilityRequestParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class CoverageEligibilityRequestParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/CoverageEligibilityRequest.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('CoverageEligibilityRequest', $data['resourceType']);

        $parser = new CoverageEligibilityRequestParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['priority'], $parser->getPriority(), 'elemen priority');
        $this->assertSame($data['purpose'], $parser->getPurpose(), 'elemen purpose');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['servicedDate'], $parser->getServicedDate(), 'elemen servicedDate');
        $this->assertSame($data['created'], $parser->getCreated(), 'elemen created');
        $this->assertSame($data['enterer'], $parser->getEnterer(), 'elemen enterer');
        $this->assertSame($data['provider'], $parser->getProvider(), 'elemen provider');
        $this->assertSame($data['insurer'], $parser->getInsurer(), 'elemen insurer');
        $this->assertSame($data['facility'], $parser->getFacility(), 'elemen facility');
        $this->assertSame($data['insurance'], $parser->getInsurance(), 'elemen insurance');
        $this->assertSame($data['supportingInfo'], $parser->getSupportingInfo(), 'elemen supportingInfo');
        $this->assertSame($data['contained'], $parser->getContained(), 'elemen contained');
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\ClaimParser;

/**
 * ClaimParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class ClaimParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Claim.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Claim', $data['resourceType']);

        $parser = new ClaimParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['type'], $parser->getType(), 'elemen type');
        $this->assertSame($data['use'], $parser->getUse(), 'elemen use');
        $this->assertSame($data['patient'], $parser->getPatient(), 'elemen patient');
        $this->assertSame($data['billablePeriod'], $parser->getBillablePeriod(), 'elemen billablePeriod');
        $this->assertSame($data['created'], $parser->getCreated(), 'elemen created');
        $this->assertSame($data['enterer'], $parser->getEnterer(), 'elemen enterer');
        $this->assertSame($data['insurer'], $parser->getInsurer(), 'elemen insurer');
        $this->assertSame($data['provider'], $parser->getProvider(), 'elemen provider');
        $this->assertSame($data['priority'], $parser->getPriority(), 'elemen priority');
        $this->assertSame($data['payee'], $parser->getPayee(), 'elemen payee');
        $this->assertSame($data['facility'], $parser->getFacility(), 'elemen facility');
        $this->assertSame($data['supportingInfo'], $parser->getSupportingInfo(), 'elemen supportingInfo');
        $this->assertSame($data['diagnosis'], $parser->getDiagnosis(), 'elemen diagnosis');
        $this->assertSame($data['procedure'], $parser->getProcedure(), 'elemen procedure');
        $this->assertSame($data['insurance'], $parser->getInsurance(), 'elemen insurance');
        $this->assertSame($data['total'], $parser->getTotal(), 'elemen total');
        $this->assertSame($data['extension'], $parser->getExtension(), 'elemen extension');
    }
}

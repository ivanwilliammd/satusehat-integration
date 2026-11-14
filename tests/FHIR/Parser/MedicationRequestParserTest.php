<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\MedicationRequestParser;

/**
 * MedicationRequestParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class MedicationRequestParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/MedicationRequest.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('MedicationRequest', $data['resourceType']);

        $parser = new MedicationRequestParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['contained'], $parser->getContained(), 'elemen contained');
        $this->assertSame($data['identifier'], $parser->getIdentifier(), 'elemen identifier');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['intent'], $parser->getIntent(), 'elemen intent');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['priority'], $parser->getPriority(), 'elemen priority');
        $this->assertSame($data['medicationReference'], $parser->getMedicationReference(), 'elemen medicationReference');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['encounter'], $parser->getEncounter(), 'elemen encounter');
        $this->assertSame($data['authoredOn'], $parser->getAuthoredOn(), 'elemen authoredOn');
        $this->assertSame($data['requester'], $parser->getRequester(), 'elemen requester');
        $this->assertSame($data['courseOfTherapyType'], $parser->getCourseOfTherapyType(), 'elemen courseOfTherapyType');
        $this->assertSame($data['dosageInstruction'], $parser->getDosageInstruction(), 'elemen dosageInstruction');
        $this->assertSame($data['dispenseRequest'], $parser->getDispenseRequest(), 'elemen dispenseRequest');
    }
}

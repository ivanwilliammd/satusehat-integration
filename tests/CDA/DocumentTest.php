<?php

namespace Satusehat\Integration\Tests\CDA;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\CDA\Document;

final class DocumentTest extends TestCase
{
    private function fixture(): string
    {
        return (string) file_get_contents(__DIR__ . '/fixtures/clinical_document.xml');
    }

    private function document(): Document
    {
        return Document::parse($this->fixture());
    }

    public function testReadsTheHeader(): void
    {
        $doc = $this->document();

        self::assertSame('Resume Medis Rawat Inap', $doc->title());
        self::assertSame('34133-9', $doc->typeCode());
        self::assertSame('20261118103000', $doc->effectiveTime());
        self::assertSame('RS Umum Contoh', $doc->custodian());
        self::assertSame(
            '2.16.840.1.113883.3.3157.1004.1|DOC-20261118-0001',
            $doc->documentId()
        );
    }

    public function testReadsPatientIdentifiersWithTheirRoots(): void
    {
        $patient = $this->document()->patient();

        self::assertSame('Siti ANGGRAENI', $patient->name());
        self::assertSame('F', $patient->administrativeGender);
        self::assertSame('19850312', $patient->birthTime);
        self::assertCount(2, $patient->identifiers);
        self::assertSame(
            '3171234567890001',
            $patient->identifier('2.16.840.1.113883.3.3157.1003.1')
        );
        self::assertSame('MRN-0098123', $patient->identifier('1.2.840.114350.1.13.1.1'));
        self::assertSame('', $patient->identifier('9.9.9'));
    }

    public function testReadsAuthors(): void
    {
        self::assertSame(['Ivan HARSONO'], $this->document()->authors());
    }

    public function testReadsNestedSectionsAndFindsByLoinc(): void
    {
        $sections = $this->document()->sections();
        self::assertCount(2, $sections);
        self::assertSame('Riwayat Penyakit Sekarang', $sections[0]->title);

        $diagnosis = $this->document()->section('29548-5');
        self::assertNotNull($diagnosis);
        self::assertCount(1, $diagnosis->children);

        self::assertNotNull($this->document()->section('11450-4'));
        self::assertNull($this->document()->section('00000-0'));
    }

    public function testSectionTextIsPlainTextWithParagraphBreaks(): void
    {
        $text = $this->document()->sectionText('10164-2');

        self::assertStringContainsString('demam tiga hari', $text);
        self::assertStringContainsString("demam tiga hari.\nRiwayat perjalanan", $text);
        self::assertStringNotContainsString('<paragraph>', $text);
    }

    public function testValidateAcceptsACompleteDocument(): void
    {
        self::assertSame([], $this->document()->validate());
    }

    public function testValidateReportsWhatIsMissing(): void
    {
        $broken = '<ClinicalDocument xmlns="urn:hl7-org:v3"><title>Kosong</title></ClinicalDocument>';
        $problems = Document::parse($broken)->validate();

        self::assertContains('ClinicalDocument/id wajib ada.', $problems);
        self::assertContains('recordTarget wajib ada.', $problems);
        self::assertContains('custodian wajib ada.', $problems);
        self::assertContains('author wajib ada.', $problems);
        self::assertContains('structuredBody wajib berisi minimal satu section.', $problems);
    }

    public function testBuildProducesADocumentThatValidates(): void
    {
        $doc = Document::build([
            'id' => 'DOC-TEST-1',
            'title' => 'Resume Medis',
            'custodian' => 'RS Contoh',
            'author' => 'Dokter Contoh',
            'patientId' => '3171234567890001',
            'patientName' => 'ANGGRAENI',
            'gender' => 'F',
            'birthTime' => '19850312',
            'sections' => [
                ['loinc' => '10164-2', 'title' => 'Riwayat', 'text' => '<paragraph>Demam.</paragraph>'],
            ],
        ]);

        self::assertSame([], $doc->validate());
        self::assertSame('Resume Medis', $doc->title());
        self::assertSame('ANGGRAENI', $doc->patient()->name());
        self::assertSame('3171234567890001', $doc->patient()->identifier('2.16.840.1.113883.3.3157.1003.1'));
        self::assertSame("Demam.", $doc->sectionText('10164-2'));
    }

    public function testRoundTripPreservesStructureTheSdkDoesNotModel(): void
    {
        $xml = $this->document()->toXml();
        $again = Document::parse($xml);

        // componentOf/encompassingEncounter is not modelled, and must survive anyway.
        self::assertNotNull($again->first('//hl7:encompassingEncounter'));
        self::assertSame('ENC-556677', $again->first('//hl7:encompassingEncounter/hl7:id')?->getAttribute('extension'));
        self::assertSame($this->document()->title(), $again->title());
        self::assertCount(2, $again->sections());
    }

    public function testRefusesXmlThatIsNotAClinicalDocument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Document::parse('<Patient xmlns="http://hl7.org/fhir"><id value="1"/></Patient>');
    }

    public function testRefusesMalformedXml(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Document::parse('<ClinicalDocument><unclosed>');
    }
}

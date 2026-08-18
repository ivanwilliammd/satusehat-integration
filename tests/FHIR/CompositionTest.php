<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderComposition;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class CompositionTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderComposition;

        $payload = $builder->build();

        $this->assertSame('Composition', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderComposition;
        $builder->setId('comp-123');

        $payload = $builder->build();

        $this->assertSame('comp-123', $payload['id']);
    }

    public function test_add_identifier()
    {
        $identifier = new Identifier('http://sys-ids.kemkes.go.id/composition', 'COMP001');
        $builder = new PayloadBuilderComposition;
        $builder->addIdentifier($identifier);

        $payload = $builder->build();

        $this->assertArrayHasKey('identifier', $payload);
        $this->assertSame('COMP001', $payload['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderComposition;
        $builder->setStatus('preliminary');

        $payload = $builder->build();

        $this->assertSame('preliminary', $payload['status']);
    }

    public function test_set_type()
    {
        $coding = new Coding('http://loinc.org', '34117-2', 'History and physical note');
        $type = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderComposition;
        $builder->setType($type);

        $payload = $builder->build();

        $this->assertSame('34117-2', $payload['type']['coding'][0]['code']);
    }

    public function test_add_category()
    {
        $coding = new Coding('http://snomed.info/sct', 'category-1', 'Clinical note');
        $category = (new CodeableConcept())->addCoding($coding);

        $builder = new PayloadBuilderComposition;
        $builder->addCategory($category);

        $payload = $builder->build();

        $this->assertArrayHasKey('category', $payload);
    }

    public function test_set_title()
    {
        $builder = new PayloadBuilderComposition;
        $builder->setTitle('Ringkasan Pasien');

        $payload = $builder->build();

        $this->assertSame('Ringkasan Pasien', $payload['title']);
    }

    public function test_set_date()
    {
        $builder = new PayloadBuilderComposition;
        $builder->setDate('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['date']);
    }

    public function test_add_author()
    {
        $author = new Reference('Practitioner/N10000001', 'Dr. Jane Doe');
        $builder = new PayloadBuilderComposition;
        $builder->addAuthor($author);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['author'][0]['reference']);
    }

    public function test_add_multiple_authors()
    {
        $builder = new PayloadBuilderComposition;
        $builder->addAuthor(new Reference('Practitioner/N1'))
                ->addAuthor(new Reference('Practitioner/N2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['author']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderComposition;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderComposition;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_custodian()
    {
        $custodian = new Reference('Organization/org-1', 'RS Umum');
        $builder = new PayloadBuilderComposition;
        $builder->setCustodian($custodian);

        $payload = $builder->build();

        $this->assertSame('Organization/org-1', $payload['custodian']['reference']);
    }

    public function test_add_attester()
    {
        $party = new Reference('Practitioner/N10000001');
        $builder = new PayloadBuilderComposition;
        $builder->addAttester('official', $party);

        $payload = $builder->build();

        $this->assertArrayHasKey('attester', $payload);
        $this->assertSame('official', $payload['attester'][0]['mode']);
        $this->assertSame('Practitioner/N10000001', $payload['attester'][0]['party']['reference']);
    }

    public function test_add_multiple_attesters()
    {
        $builder = new PayloadBuilderComposition;
        $builder->addAttester('official', new Reference('Practitioner/N1'))
                ->addAttester('personal', new Reference('Patient/1'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['attester']);
    }

    public function test_add_section()
    {
        $section = [
            'title' => 'Chief Complaint',
            'code' => ['coding' => [['code' => 'CC']]],
            'text' => ['div' => '<div>Patient complains of...</div>'],
        ];
        $builder = new PayloadBuilderComposition;
        $builder->addSection($section);

        $payload = $builder->build();

        $this->assertArrayHasKey('section', $payload);
        $this->assertSame('Chief Complaint', $payload['section'][0]['title']);
    }

    public function test_add_multiple_sections()
    {
        $builder = new PayloadBuilderComposition;
        $builder->addSection(['title' => 'Section 1'])
                ->addSection(['title' => 'Section 2']);

        $payload = $builder->build();

        $this->assertCount(2, $payload['section']);
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderComposition;
        $result = $builder->setId('comp-1')
                          ->setStatus('preliminary')
                          ->setTitle('Dokumen Klinis')
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderComposition::class, $result);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderDocumentReference;
use Satusehat\Integration\DataType\Attachment;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;

class DocumentReferenceTest extends TestCase
{
    public function test_build_returns_valid_fhir_payload()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://loinc.org', '34117-2', 'History and physical note');

        $category = new CodeableConcept();
        $category->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v3-ActCode', 'DOC', 'Document');

        $builder = new PayloadBuilderDocumentReference();
        $builder->setId('doc-001');
        $builder->addIdentifier(new Identifier('http://sys.com', '12345'));
        $builder->setMasterIdentifier(new Identifier('http://master.com', 'master-001'));
        $builder->setStatus('current');
        $builder->setDocStatus('preliminary');
        $builder->addType($type);
        $builder->addCategory($category);
        $builder->setSubject(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setDate('2022-06-14T10:00:00+00:00');
        $builder->addAuthor(new Reference('Practitioner/N10000001', 'Dr. Smith'));
        $builder->setCustodian(new Reference('Organization/org-001'));
        $builder->setDescription('Patient history document');

        $payload = $builder->build();

        $this->assertSame('DocumentReference', $payload['resourceType']);
        $this->assertSame('doc-001', $payload['id']);
        $this->assertSame('current', $payload['status']);
        $this->assertSame('preliminary', $payload['docStatus']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setId('custom-doc-id');

        $payload = $builder->build();

        $this->assertSame('custom-doc-id', $payload['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->addIdentifier(new Identifier('http://sys.com', 'val123'));
        $builder->addIdentifier(new Identifier('http://sys2.com', 'val456'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['identifier']);
        $this->assertSame('val123', $payload['identifier'][0]['value']);
    }

    public function test_set_master_identifier()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setMasterIdentifier(new Identifier('http://master.com', 'master-val'));

        $payload = $builder->build();

        $this->assertSame('master-val', $payload['masterIdentifier']['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setStatus('superseded');

        $payload = $builder->build();

        $this->assertSame('superseded', $payload['status']);
    }

    public function test_set_doc_status()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setDocStatus('final');

        $payload = $builder->build();

        $this->assertSame('final', $payload['docStatus']);
    }

    public function test_add_type()
    {
        $type = new CodeableConcept();
        $type->coding[] = new Coding('http://loinc.org', '34117-2', 'History and physical');
        $type->text = 'History and Physical';
        
        $builder = new PayloadBuilderDocumentReference();
        $builder->addType($type);

        $payload = $builder->build();

        $this->assertArrayHasKey('type', $payload);
        $this->assertSame('History and Physical', $payload['type'][0]['text']);
    }

    public function test_add_multiple_types()
    {
        $builder = new PayloadBuilderDocumentReference();
        $type1 = new CodeableConcept();
        $type1->coding[] = new Coding('http://test.com', 'TYPE1', 'Type1');
        $type2 = new CodeableConcept();
        $type2->coding[] = new Coding('http://test.com', 'TYPE2', 'Type2');
        $builder->addType($type1);
        $builder->addType($type2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['type']);
    }

    public function test_add_category()
    {
        $cat = new CodeableConcept();
        $cat->coding[] = new Coding('http://test.com', 'CAT', 'Category');
        
        $builder = new PayloadBuilderDocumentReference();
        $builder->addCategory($cat);

        $payload = $builder->build();

        $this->assertArrayHasKey('category', $payload);
        $this->assertNotEmpty($payload['category']);
    }

    public function test_set_subject()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setSubject(new Reference('Patient/123', 'Test Patient'));

        $payload = $builder->build();

        $this->assertSame('Patient/123', $payload['subject']['reference']);
        $this->assertSame('Test Patient', $payload['subject']['display']);
    }

    public function test_set_date()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setDate('2022-06-14T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2022-06-14T10:00:00+00:00', $payload['date']);
    }

    public function test_add_author()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->addAuthor(new Reference('Practitioner/prac-1', 'Dr. Smith'));
        $builder->addAuthor(new Reference('Organization/org-1'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['author']);
        $this->assertSame('Dr. Smith', $payload['author'][0]['display']);
    }

    public function test_set_custodian()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setCustodian(new Reference('Organization/cust-1', 'Health Org'));

        $payload = $builder->build();

        $this->assertSame('Organization/cust-1', $payload['custodian']['reference']);
    }

    public function test_add_relates_to()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->addRelatesTo('replaces', new Reference('DocumentReference/doc-old-1'));

        $payload = $builder->build();

        $this->assertSame('replaces', $payload['relatesTo'][0]['code']);
        $this->assertSame('DocumentReference/doc-old-1', $payload['relatesTo'][0]['target']['reference']);
    }

    public function test_add_multiple_relates_to()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->addRelatesTo('replaces', new Reference('DocumentReference/doc-1'));
        $builder->addRelatesTo('appends', new Reference('DocumentReference/doc-2'));

        $payload = $builder->build();

        $this->assertCount(2, $payload['relatesTo']);
    }

    public function test_set_description()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setDescription('Patient discharge summary');

        $payload = $builder->build();

        $this->assertSame('Patient discharge summary', $payload['description']);
    }

    public function test_add_security_label()
    {
        $label = new CodeableConcept();
        $label->coding[] = new Coding('http://terminology.hl7.org/CodeSystem/v3-ActCode', 'R', 'Restricted');
        
        $builder = new PayloadBuilderDocumentReference();
        $builder->addSecurityLabel($label);

        $payload = $builder->build();

        $this->assertArrayHasKey('securityLabel', $payload);
        $this->assertNotEmpty($payload['securityLabel']);
    }

    public function test_add_content_with_attachment()
    {
        $attachment = new Attachment('application/pdf', null, 'http://example.com/doc.pdf');
        $attachment->title = 'Test Document';

        $builder = new PayloadBuilderDocumentReference();
        $builder->addContent($attachment);

        $payload = $builder->build();

        $this->assertSame('application/pdf', $payload['content'][0]['attachment']['contentType']);
        $this->assertSame('http://example.com/doc.pdf', $payload['content'][0]['attachment']['url']);
    }

    public function test_add_content_with_format()
    {
        $attachment = new Attachment('application/pdf', null, 'http://example.com/doc.pdf');

        $format = new CodeableConcept();
        $format->coding[] = new Coding('http://ihe.net/fhir/ValueSet/IHEFormatCodes', 'application/pdf', 'PDF');

        $builder = new PayloadBuilderDocumentReference();
        $builder->addContent($attachment, $format);

        $payload = $builder->build();

        $this->assertSame('application/pdf', $payload['content'][0]['attachment']['contentType']);
        $this->assertArrayHasKey('format', $payload['content'][0]);
    }

    public function test_add_multiple_content()
    {
        $att1 = new Attachment('application/pdf', null, 'http://example.com/doc1.pdf');
        $att2 = new Attachment('text/plain', null, 'http://example.com/doc2.txt');

        $builder = new PayloadBuilderDocumentReference();
        $builder->addContent($att1);
        $builder->addContent($att2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['content']);
    }

    public function test_set_context()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setContext([
            'encounter' => ['reference' => 'Encounter/enc-1'],
            'event' => [['coding' => [['code' => 'HEALTH']]]]
        ]);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-1', $payload['context']['encounter']['reference']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->addExtension('http://example.com/ext', 'test-value');

        $payload = $builder->build();

        $this->assertSame('http://example.com/ext', $payload['extension'][0]['url']);
        $this->assertSame('test-value', $payload['extension'][0]['valueString']);
    }

    public function test_fluent_interface()
    {
        $builder = (new PayloadBuilderDocumentReference())
            ->setId('doc-123')
            ->setStatus('current')
            ->setSubject(new Reference('Patient/123'));

        $payload = $builder->build();

        $this->assertSame('doc-123', $payload['id']);
        $this->assertSame('current', $payload['status']);
        $this->assertSame('Patient/123', $payload['subject']['reference']);
    }

    public function test_json_returns_array()
    {
        $builder = new PayloadBuilderDocumentReference();
        $builder->setStatus('current');
        $builder->setSubject(new Reference('Patient/123'));

        $json = $builder->json();

        $this->assertIsArray($json);
        $this->assertSame('DocumentReference', $json['resourceType']);
    }
}

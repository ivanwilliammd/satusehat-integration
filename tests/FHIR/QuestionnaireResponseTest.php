<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderQuestionnaireResponse;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Reference;

class QuestionnaireResponseTest extends TestCase
{
    public function test_constructor_sets_resource_type()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;

        $payload = $builder->build();

        $this->assertSame('QuestionnaireResponse', $payload['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setId('qr-123');

        $payload = $builder->build();

        $this->assertSame('qr-123', $payload['id']);
    }

    public function test_set_status_defaults_to_completed()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        // status not explicitly set; build() applies default 'completed'

        $payload = $builder->build();

        $this->assertSame('completed', $payload['status']);
    }

    public function test_set_status_valid_value()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setStatus('in-progress');

        $payload = $builder->build();

        $this->assertSame('in-progress', $payload['status']);
    }

    public function test_set_status_uppercase_normalized_to_lowercase()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setStatus('AMENDED');

        $payload = $builder->build();

        $this->assertSame('amended', $payload['status']);
    }

    public function test_set_status_throws_on_invalid_status()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setStatus('invalid-status');
    }

    public function test_set_status_throws_on_unknown_value()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setStatus('draft');
    }

    public function test_set_questionnaire()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setQuestionnaire('Questionnaire/questionnaire-1');

        $payload = $builder->build();

        $this->assertSame('Questionnaire/questionnaire-1', $payload['questionnaire']);
    }

    public function test_set_subject()
    {
        $subject = new Reference('Patient/100000030009', 'Budi Santoso');
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setSubject($subject);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['subject']['reference']);
    }

    public function test_set_encounter()
    {
        $encounter = new Reference('Encounter/enc-123');
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setEncounter($encounter);

        $payload = $builder->build();

        $this->assertSame('Encounter/enc-123', $payload['encounter']['reference']);
    }

    public function test_set_authored()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setAuthored('2024-06-14T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-06-14T10:00:00+00:00', $payload['authored']);
    }

    public function test_set_author()
    {
        $author = new Reference('Practitioner/N10000001', 'Dr. Siti Rahayu');
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setAuthor($author);

        $payload = $builder->build();

        $this->assertSame('Practitioner/N10000001', $payload['author']['reference']);
    }

    public function test_set_source()
    {
        $source = new Reference('Patient/100000030009');
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setSource($source);

        $payload = $builder->build();

        $this->assertSame('Patient/100000030009', $payload['source']['reference']);
    }

    public function test_add_item()
    {
        $answer = new CodeableConcept;
        $answer->addCoding(new Coding('http://snomed.info/sct', '373066001', 'Yes'));

        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->addItem('q1', 'Apakah Anda merasa sehat?', $answer);

        $payload = $builder->build();

        $this->assertArrayHasKey('item', $payload);
        $this->assertSame('q1', $payload['item'][0]['linkId']);
        $this->assertSame('Apakah Anda merasa sehat?', $payload['item'][0]['text']);
        $this->assertSame('373066001', $payload['item'][0]['answer'][0]['coding'][0]['code']);
    }

    public function test_add_item_without_text()
    {
        $answer = new CodeableConcept;
        $answer->addCoding(new Coding('http://snomed.info/sct', '373066001', 'Yes'));

        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->addItem('q1', null, $answer);

        $payload = $builder->build();

        $this->assertArrayNotHasKey('text', $payload['item'][0]);
    }

    public function test_add_multiple_items()
    {
        $answer1 = (new CodeableConcept)->addCoding(new Coding('http://snomed.info/sct', '373066001', 'Yes'));
        $answer2 = (new CodeableConcept)->addCoding(new Coding('http://snomed.info/sct', '373067005', 'No'));

        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->addItem('q1', 'Q1', $answer1);
        $builder->addItem('q2', 'Q2', $answer2);

        $payload = $builder->build();

        $this->assertCount(2, $payload['item']);
        $this->assertSame('q1', $payload['item'][0]['linkId']);
        $this->assertSame('q2', $payload['item'][1]['linkId']);
    }

    public function test_status_explicit_overrides_default()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setStatus('stopped');

        $payload = $builder->build();

        $this->assertSame('stopped', $payload['status']);
        $this->assertSame('stopped', $payload['status']); // verify default not re-applied
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $result = $builder->setId('qr-1')
                          ->setStatus('completed')
                          ->setSubject(new Reference('Patient/1'));

        $this->assertInstanceOf(PayloadBuilderQuestionnaireResponse::class, $result);
    }

    public function test_build_returns_filtered_array()
    {
        $builder = new PayloadBuilderQuestionnaireResponse;
        $builder->setId('qr-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('resourceType', $payload);
        $this->assertArrayHasKey('id', $payload);
        $this->assertArrayNotHasKey('questionnaire', $payload);
    }
}

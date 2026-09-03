<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderCondition;
use Satusehat\Integration\Builder\PayloadBuilderObservation;

/**
 * Terminology castable on array — "System:Code" notation auto-resolves
 * to a CodeableConcept with the correct system URL.
 */
class TerminologyCastableTest extends TestCase
{
    public function test_condition_set_code_with_icd10_notation(): void
    {
        $payload = (new PayloadBuilderCondition())
            ->setCode('ICD10:A00')
            ->build();

        $this->assertSame('http://hl7.org/fhir/sid/icd-10', $payload['code']['coding'][0]['system']);
        $this->assertSame('A00', $payload['code']['coding'][0]['code']);
    }

    public function test_observation_set_code_with_loinc_notation(): void
    {
        $payload = (new PayloadBuilderObservation())
            ->setCode('LOINC:2951-2')
            ->build();

        $this->assertSame('http://loinc.org', $payload['code']['coding'][0]['system']);
        $this->assertSame('2951-2', $payload['code']['coding'][0]['code']);
    }

    public function test_observation_add_category_with_plain_text(): void
    {
        $payload = (new PayloadBuilderObservation())
            ->addCategory('vital-signs')
            ->build();

        $this->assertSame('vital-signs', $payload['category'][0]['text']);
    }

    public function test_observation_add_category_with_snomed(): void
    {
        $payload = (new PayloadBuilderObservation())
            ->addCategory('SNOMED:386053000')
            ->build();

        $this->assertSame('http://snomed.info/sct', $payload['category'][0]['coding'][0]['system']);
        $this->assertSame('386053000', $payload['category'][0]['coding'][0]['code']);
    }

    public function test_condition_set_code_preserves_typed_codeable_concept(): void
    {
        $cc = (new \Satusehat\Integration\DataType\CodeableConcept())
            ->addCoding(new \Satusehat\Integration\DataType\Coding('http://example.com', 'X', 'X'));
        $payload = (new PayloadBuilderCondition())
            ->setCode($cc)
            ->build();

        $this->assertSame('http://example.com', $payload['code']['coding'][0]['system']);
        $this->assertSame('X', $payload['code']['coding'][0]['code']);
    }

    public function test_condition_set_severity_castable(): void
    {
        $payload = (new PayloadBuilderCondition())
            ->setSeverity('SNOMED:24484000')
            ->build();

        $this->assertSame('http://snomed.info/sct', $payload['severity']['coding'][0]['system']);
        $this->assertSame('24484000', $payload['severity']['coding'][0]['code']);
    }
}

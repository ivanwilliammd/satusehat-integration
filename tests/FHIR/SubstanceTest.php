<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderSubstance;

class SubstanceTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderSubstance;
        $this->assertSame('Substance', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderSubstance;
        $result = $builder->setId('sub-001')->build();
        $this->assertSame('sub-001', $result['id']);
    }

    public function test_set_status_valid_values()
    {
        $validStatuses = ['active', 'inactive', 'entered-in-error'];
        
        foreach ($validStatuses as $status) {
            $builder = new PayloadBuilderSubstance;
            $result = $builder->setStatus($status)->build();
            $this->assertSame($status, $result['status'], "Status '$status' should be accepted");
        }
    }

    public function test_set_status_invalid_value_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status: unknown');

        $builder = new PayloadBuilderSubstance;
        $builder->setStatus('unknown');
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderSubstance;
        $result = $builder->setCode(
            'http://snomed.info/sct',
            '387458008',
            'Metformin 500mg'
        )->build();
        
        $this->assertSame('http://snomed.info/sct', $result['code']['coding'][0]['system']);
        $this->assertSame('387458008', $result['code']['coding'][0]['code']);
        $this->assertSame('Metformin 500mg', $result['code']['coding'][0]['display']);
    }

    public function test_set_text()
    {
        $builder = new PayloadBuilderSubstance;
        $result = $builder->setText('generated', '<div xmlns="http://www.w3.org/1999/xhtml">Metformin 500mg tablet</div>')->build();
        
        $this->assertSame('generated', $result['text']['status']);
        $this->assertStringContainsString('Metformin 500mg', $result['text']['div']);
    }

    public function test_full_substance_payload()
    {
        $builder = new PayloadBuilderSubstance;
        $builder->setId('sub-002')
            ->setStatus('active')
            ->setCode('http://snomed.info/sct', '387458008', 'Metformin 500mg')
            ->setText('generated', '<div xmlns="http://www.w3.org/1999/xhtml">Metformin 500mg tablet</div>');
        
        $result = $builder->build();
        
        $this->assertSame('Substance', $result['resourceType']);
        $this->assertSame('sub-002', $result['id']);
        $this->assertSame('active', $result['status']);
        $this->assertSame('387458008', $result['code']['coding'][0]['code']);
    }

    public function test_chaining_build_returns_array()
    {
        $builder = new PayloadBuilderSubstance;
        $builder->setId('sub-003')
            ->setStatus('active')
            ->setCode('http://example.org', 'SUB001', 'Test Substance');

        $this->assertIsArray($builder->build());
        $this->assertSame('Substance', $builder->build()['resourceType']);
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderImagingStudy;
use Satusehat\Integration\FHIR\ImagingStudy;

/**
 * SRD T-06: series.bodySite adalah CodeableConcept, bukan Coding.
 * @link https://hl7.org/fhir/R4/imagingstudy.html (ImagingStudy.series.bodySite: CodeableConcept 0..1)
 */
class ImagingStudyTest extends TestCase
{
    public function test_builder_series_body_site_is_codeable_concept()
    {
        $builder = new PayloadBuilderImagingStudy;
        $builder->addSeries('1.2.3', 'http://dicom.nema.org/resources/ontology/DCM', 'CT', 'Computed Tomography');
        $builder->setSeriesBodySite(0, 'http://snomed.info/sct', '51185008', 'Thorax');

        $payload = $builder->build();

        $this->assertArrayNotHasKey('system', $payload['series'][0]['bodySite'], 'Coding mentah bukan CodeableConcept');
        $this->assertSame('51185008', $payload['series'][0]['bodySite']['coding'][0]['code']);
        $this->assertSame('http://snomed.info/sct', $payload['series'][0]['bodySite']['coding'][0]['system']);
        $this->assertSame('Thorax', $payload['series'][0]['bodySite']['coding'][0]['display']);
    }

    public function test_builder_series_body_site_omits_empty_display()
    {
        $builder = new PayloadBuilderImagingStudy;
        $builder->addSeries('1.2.3', 'sys', 'CT', 'CT');
        $builder->setSeriesBodySite(0, 'http://snomed.info/sct', '51185008');

        $payload = $builder->build();

        $this->assertArrayNotHasKey('display', $payload['series'][0]['bodySite']['coding'][0]);
        $this->assertNull($payload['series'][0]['bodySite']['text'] ?? null);
    }

    public function test_fhir_series_body_site_is_codeable_concept()
    {
        $study = new ImagingStudy;
        $study->addSeries('1.2.3', 'http://dicom.nema.org/resources/ontology/DCM', 'CT', 'CT');
        $study->setSeriesBodySite(0, 'http://snomed.info/sct', '51185008', 'Thorax');

        $this->assertSame('51185008', $study->data['series'][0]['bodySite']['coding'][0]['code']);
        $this->assertArrayNotHasKey('system', $study->data['series'][0]['bodySite']);
    }

    public function test_fhir_series_body_site_ignores_missing_series()
    {
        $study = new ImagingStudy;
        $study->setSeriesBodySite(3, 'http://snomed.info/sct', '51185008');

        $this->assertSame([], $study->data['series'] ?? []);
    }
}

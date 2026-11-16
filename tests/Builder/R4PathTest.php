<?php

namespace Satusehat\Integration\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderClaim;
use Satusehat\Integration\Builder\PayloadBuilderClaimResponse;
use Satusehat\Integration\Builder\PayloadBuilderCoverageEligibilityRequest;
use Satusehat\Integration\Builder\PayloadBuilderCoverageEligibilityResponse;
use Satusehat\Integration\Builder\PayloadBuilderMedicinalProduct;
use Satusehat\Integration\Builder\PayloadBuilderSpecimenDefinition;

/**
 * Uji bentuk payload untuk elemen yang di R4 berada di jalur BERBEDA dari yang dulu ditulis.
 *
 * Setiap kasus berasal dari triase: builder menulis elemen di tingkat atas padahal R4
 * menempatkannya di dalam elemen lain. Uji ini mengunci jalur yang benar supaya perbaikannya tidak
 * diam-diam kembali ke bentuk lama.
 */
class R4PathTest extends TestCase
{
    public function test_claim_coverage_berada_di_insurance(): void
    {
        $payload = (new PayloadBuilderClaim())->addCoverage('Coverage/1', true)->build();

        $this->assertArrayNotHasKey('coverage', $payload, 'Claim.coverage tidak ada di R4');
        $this->assertSame('Coverage/1', $payload['insurance'][0]['coverage']['reference']);
        $this->assertSame(1, $payload['insurance'][0]['sequence']);
        $this->assertTrue($payload['insurance'][0]['focal']);
    }

    public function test_claim_response_coverage_berada_di_insurance(): void
    {
        $payload = (new PayloadBuilderClaimResponse())->addCoverage(1, 'Coverage/1')->build();

        $this->assertArrayNotHasKey('coverage', $payload);
        $this->assertSame(1, $payload['insurance'][0]['sequence']);
        $this->assertSame('Coverage/1', $payload['insurance'][0]['coverage']['reference']);
    }

    public function test_coverage_eligibility_request_memakai_insurance_dengan_focal_wajib(): void
    {
        $payload = (new PayloadBuilderCoverageEligibilityRequest())
            ->addCoverage('Coverage/1')
            ->addCoverage('Coverage/2')
            ->build();

        $this->assertArrayNotHasKey('coverage', $payload);
        $this->assertTrue($payload['insurance'][0]['focal'], 'cakupan pertama wajib focal');
        $this->assertFalse($payload['insurance'][1]['focal']);
    }

    public function test_coverage_eligibility_response_memakai_insurance(): void
    {
        $payload = (new PayloadBuilderCoverageEligibilityResponse())->addCoverage('Coverage/1')->build();

        $this->assertArrayNotHasKey('coverage', $payload);
        $this->assertSame('Coverage/1', $payload['insurance'][0]['coverage']['reference']);
    }

    public function test_specimen_definition_menulis_type_dan_preparation_di_type_tested(): void
    {
        $payload = (new PayloadBuilderSpecimenDefinition())
            ->setType('http://snomed.info/sct', '119297000', 'Blood specimen')
            ->setPreparation('http://snomed.info/sct', '118693000')
            ->build();

        $this->assertArrayNotHasKey('type', $payload);
        $this->assertArrayNotHasKey('preparation', $payload);
        $this->assertSame('119297000', $payload['typeTested'][0]['type']['coding'][0]['code']);
        $this->assertSame('118693000', $payload['typeTested'][0]['container']['preparation']['coding'][0]['code']);
    }

    public function test_medicinal_product_manufacturer_di_manufacturing_business_operation(): void
    {
        $payload = (new PayloadBuilderMedicinalProduct())->setManufacturer('Organization/1')->build();

        $this->assertArrayNotHasKey('manufacturer', $payload);
        $this->assertSame('Organization/1', $payload['manufacturingBusinessOperation'][0]['manufacturer']['reference']);
    }
}

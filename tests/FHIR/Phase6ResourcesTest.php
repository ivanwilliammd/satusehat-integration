<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\{
    PayloadBuilderActivityDefinition,
    PayloadBuilderCapabilityStatement,
    PayloadBuilderCatalogEntry,
    PayloadBuilderDeviceMetric,
    PayloadBuilderDocumentManifest,
    PayloadBuilderEnrollmentResponse,
    PayloadBuilderExplanationOfBenefit,
    PayloadBuilderHealthcareService,
    PayloadBuilderInsurancePlan,
    PayloadBuilderMedicationKnowledge,
    PayloadBuilderMedicinalProduct,
    PayloadBuilderMedicinalProductAuthorization,
    PayloadBuilderMedicinalProductContraindication,
    PayloadBuilderMedicinalProductIndication,
    PayloadBuilderMedicinalProductIngredient,
    PayloadBuilderMedicinalProductInteraction,
    PayloadBuilderMedicinalProductManufactured,
    PayloadBuilderMedicinalProductPackaged,
    PayloadBuilderMedicinalProductPharmaceutical,
    PayloadBuilderMedicinalProductUndesirableEffect,
    PayloadBuilderObservationDefinition,
    PayloadBuilderOrganizationAffiliation,
    PayloadBuilderResearchStudy,
    PayloadBuilderResourceGuide,
    PayloadBuilderSpecimenDefinition,
    PayloadBuilderSubstanceReferenceInformation,
};

/**
 * @coversNothing
 */
class Phase6ResourcesTest extends TestCase
{
    /**
     * @dataProvider builderProvider
     */
    public function test_builder_builds_valid_payload(string $class, string $resourceType): void
    {
        $builder = new $class();
        $this->assertInstanceOf($class, $builder);

        $payload = $builder
            ->setId('ph6-' . strtolower($resourceType))
            ->setStatus('active')
            ->build();

        $this->assertSame($resourceType, $payload['resourceType']);
        $this->assertSame('ph6-' . strtolower($resourceType), $payload['id']);
        $this->assertSame('active', $payload['status']);
    }

    public static function builderProvider(): array
    {
        $builders = [
            PayloadBuilderActivityDefinition::class,
            PayloadBuilderCapabilityStatement::class,
            PayloadBuilderCatalogEntry::class,
            PayloadBuilderDeviceMetric::class,
            PayloadBuilderDocumentManifest::class,
            PayloadBuilderEnrollmentResponse::class,
            PayloadBuilderExplanationOfBenefit::class,
            PayloadBuilderHealthcareService::class,
            PayloadBuilderInsurancePlan::class,
            PayloadBuilderMedicationKnowledge::class,
            PayloadBuilderMedicinalProduct::class,
            PayloadBuilderMedicinalProductAuthorization::class,
            PayloadBuilderMedicinalProductContraindication::class,
            PayloadBuilderMedicinalProductIndication::class,
            PayloadBuilderMedicinalProductIngredient::class,
            PayloadBuilderMedicinalProductInteraction::class,
            PayloadBuilderMedicinalProductManufactured::class,
            PayloadBuilderMedicinalProductPackaged::class,
            PayloadBuilderMedicinalProductPharmaceutical::class,
            PayloadBuilderMedicinalProductUndesirableEffect::class,
            PayloadBuilderObservationDefinition::class,
            PayloadBuilderOrganizationAffiliation::class,
            PayloadBuilderResearchStudy::class,
            PayloadBuilderResourceGuide::class,
            PayloadBuilderSpecimenDefinition::class,
            PayloadBuilderSubstanceReferenceInformation::class,
        ];

        return array_map(
            function (string $c): array {
                $ref = new \ReflectionClass($c);
                $prop = $ref->getProperty('resourceType');
                $builder = new $c();
                $resourceType = $prop->getValue($builder);
                return [$c, $resourceType];
            },
            $builders
        );
    }

    public function test_organization_affiliation_has_typed_fields(): void
    {
        $payload = (new PayloadBuilderOrganizationAffiliation())
            ->setOrganization('org-1', 'RSCM')
            ->setCode('http://terminology.hl7.org/CodeSystem/organization-role', 'provider', 'Provider')
            ->build();

        $this->assertSame('org-1', $payload['organization']['reference'] ?? null);
        $this->assertSame('provider', $payload['code']['coding'][0]['code'] ?? null);
    }
}

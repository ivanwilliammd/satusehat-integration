<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderImmunization;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;

class ImmunizationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderImmunization;
        $this->assertSame('Immunization', $builder->build()['resourceType']);
    }

    public function test_set_meta_profile()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Immunization')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/Immunization', $result['meta/profile'][0]);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setId('imm-001')->build();
        $this->assertSame('imm-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderImmunization;
        $id = new Identifier('http://sys-ids.kemkes.go.id/immunization', 'IMM-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('IMM-001', $result['identifier'][0]['value']);
    }

    public function test_set_status_valid()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setStatus('completed')->build();
        $this->assertSame('completed', $result['status']);
    }

    public function test_set_status_invalid_throws()
    {
        $this->expectException(FHIRInvalidPropertyValue::class);
        $builder = new PayloadBuilderImmunization;
        $builder->setStatus('invalid-status');
    }

    public function test_set_vaccine_code()
    {
        $builder = new PayloadBuilderImmunization;
        $vaccineCode = new CodeableConcept();
        $vaccineCode->addCoding(new Coding('http://snomed.info/sct', '93001282', 'COVID-19 Vaccine'));
        $result = $builder->setVaccineCode($vaccineCode)->build();
        $this->assertSame('93001282', $result['vaccineCode']['coding'][0]['code']);
    }

    public function test_set_vaccine_code_from_code()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setVaccineCodeFromCode('93001282')->build();
        $this->assertArrayHasKey('coding', $result['vaccineCode']);
    }

    public function test_set_vaccine_code_from_code_invalid_throws()
    {
        $this->expectException(FHIRInvalidPropertyValue::class);
        $builder = new PayloadBuilderImmunization;
        $builder->setVaccineCodeFromCode('INVALID-CODE');
    }

    public function test_set_patient()
    {
        $builder = new PayloadBuilderImmunization;
        $patient = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setPatient($patient)->build();
        $this->assertSame('Patient/100000030009', $result['patient']['reference']);
        $this->assertSame('Budi Santoso', $result['patient']['display']);
    }

    public function test_set_patient_with_display_override()
    {
        $builder = new PayloadBuilderImmunization;
        $patient = new Reference('Patient/100000030009');
        $result = $builder->setPatient($patient, 'Budi S.')->build();
        $this->assertSame('Budi S.', $result['patient']['display']);
    }

    public function test_set_occurrence_date_time()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setOccurrenceDateTime('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['occurrenceDateTime']);
    }

    public function test_add_performer()
    {
        $builder = new PayloadBuilderImmunization;
        $actor = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->addPerformer($actor)->build();
        $this->assertSame('Practitioner/N10000001', $result['performer'][0]['actor']['reference']);
    }

    public function test_add_performer_with_function()
    {
        $builder = new PayloadBuilderImmunization;
        $actor = new Reference('Practitioner/N10000001');
        $func = new CodeableConcept();
        $func->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v2-0443', 'AP', 'Administering Provider'));
        $result = $builder->addPerformer($actor, $func)->build();
        $this->assertArrayHasKey('function', $result['performer'][0]);
    }

    public function test_set_dose_quantity()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setDoseQuantity(0.5, 'mL')->build();
        $this->assertSame(0.5, $result['doseQuantity']['value']);
        $this->assertSame('mL', $result['doseQuantity']['unit']);
    }

    public function test_set_dose_quantity_custom_system()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setDoseQuantity(1, 'tablet', 'http://example.org', 'tbl')->build();
        $this->assertSame('http://example.org', $result['doseQuantity']['system']);
        $this->assertSame('tbl', $result['doseQuantity']['code']);
    }

    public function test_set_location()
    {
        $builder = new PayloadBuilderImmunization;
        $loc = new Reference('Location/loc-001', 'Ruang Tindakan');
        $result = $builder->setLocation($loc)->build();
        $this->assertSame('Location/loc-001', $result['location']['reference']);
    }

    public function test_set_location_with_display_override()
    {
        $builder = new PayloadBuilderImmunization;
        $loc = new Reference('Location/loc-001');
        $result = $builder->setLocation($loc, 'Ruang Immunisasi')->build();
        $this->assertSame('Ruang Immunisasi', $result['location']['display']);
    }

    public function test_set_lot_number()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setLotNumber('LOT123456')->build();
        $this->assertSame('LOT123456', $result['lotNumber']);
    }

    public function test_set_recorded()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setRecorded('2024-01-15T10:30:00+00:00')->build();
        $this->assertSame('2024-01-15T10:30:00+00:00', $result['recorded']);
    }

    public function test_set_primary_source()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setPrimarySource(true)->build();
        $this->assertTrue($result['primarySource']);

        $result2 = $builder->setPrimarySource(false)->build();
        $this->assertFalse($result2['primarySource']);
    }

    public function test_add_protocol_applied()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->addProtocolApplied(1)->build();
        $this->assertSame(1, $result['protocolApplied'][0]['doseNumberPositiveInt']);
    }

    public function test_add_protocol_applied_with_series()
    {
        $builder = new PayloadBuilderImmunization;
        $series = new CodeableConcept();
        $series->addCoding(new Coding(null, '1 of 3', 'Dose 1 of 3'));
        $result = $builder->addProtocolApplied(1, $series)->build();
        $this->assertArrayHasKey('seriesDosesPositiveInt', $result['protocolApplied'][0]);
    }

    public function test_add_reason_code()
    {
        $builder = new PayloadBuilderImmunization;
        $reason = new CodeableConcept();
        $reason->addCoding(new Coding('http://snomed.info/sct', '429060002', 'No drug allergy'));
        $result = $builder->addReasonCode($reason)->build();
        $this->assertSame('429060002', $result['reasonCode'][0]['coding'][0]['code']);
    }

    public function test_set_route()
    {
        $builder = new PayloadBuilderImmunization;
        $route = new CodeableConcept();
        $route->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v3-RouteOfAdministration', 'IM', 'Intramuscular'));
        $result = $builder->setRoute($route)->build();
        $this->assertSame('IM', $result['route']['coding'][0]['code']);
    }

    public function test_set_route_from_code()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->setRouteFromCode('IM', 'Intramuscular')->build();
        $this->assertSame('IM', $result['route']['coding'][0]['code']);
        $this->assertSame('Intramuscular', $result['route']['coding'][0]['display']);
    }

    public function test_add_extension()
    {
        $builder = new PayloadBuilderImmunization;
        $result = $builder->addExtension('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', 'value')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/custom', $result['extension'][0]['url']);
        $this->assertSame('value', $result['extension'][0]['valueString']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderImmunization;
        $builder->setId('imm-002')
            ->setStatus('completed')
            ->setOccurrenceDateTime('2024-01-15');

        $this->assertIsArray($builder->build());
        $this->assertSame('imm-002', $builder->build()['id']);
    }
}

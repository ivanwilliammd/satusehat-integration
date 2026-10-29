<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderHealthcareService;
use Satusehat\Integration\Builder\PayloadBuilderInsurancePlan;

/**
 * Uji tapak (cardinality) medan `type` per resource FHIR R4.
 *
 * Sebabnya nyata: dua builder menulis `type` sebagai satu objek padahal tapaknya 0..*,
 * sehingga payload sah menurut kode tetapi TIDAK sah menurut spesifikasi — dan karena
 * itu ditolak server SATUSEHAT tanpa menyebut kolom mana yang salah.
 *
 * Daftar kasus sengaja SEMPIT: hanya medan yang tapaknya 0..* DAN punya setter yang
 * menulis objek tunggal. Medan bertapak 0..1/1..1 (Account, AllergyIntolerance, Coverage,
 * Device, DocumentManifest, Invoice, MolecularSequence, Specimen, SpecimenDefinition,
 * Bundle, Claim, ClaimResponse, Composition, DeviceMetric, ExplanationOfBenefit, Group,
 * PaymentReconciliation) memang BENAR sebagai objek — jangan ditambahkan ke sini.
 *
 * Rujukan tapak: https://hl7.org/fhir/R4/ (diverifikasi lewat MCP fhir-r4, bukan ingatan).
 */
class ResourceTypeCardinalityTest extends TestCase
{
    /**
     * @return array<string, array{0: class-string, 1: string}>
     */
    public static function listTypeProvider(): array
    {
        return [
            'HealthcareService.type' => [PayloadBuilderHealthcareService::class, 'http://hl7.org/fhir/ValueSet/service-type'],
            'InsurancePlan.type' => [PayloadBuilderInsurancePlan::class, 'http://hl7.org/fhir/ValueSet/insuranceplan-type'],
        ];
    }

    /**
     * @dataProvider listTypeProvider
     */
    public function test_type_is_a_list_and_repeated_calls_accumulate(string $kelas, string $system): void
    {
        $payload = (new $kelas())
            ->setType($system, 'KODE-A', 'Jenis A')
            ->setType($system, 'KODE-B', 'Jenis B')
            ->json();

        $this->assertIsArray($payload['type'] ?? null, $kelas.' — type harus daftar (0..*)');
        $this->assertArrayHasKey(0, $payload['type'], $kelas.' — type harus berupa daftar berindeks');
        $this->assertCount(2, $payload['type'], $kelas.' — dua panggilan setType harus MENAMBAH, bukan menimpa');
        $this->assertSame('KODE-A', $payload['type'][0]['coding'][0]['code'] ?? null, $kelas.' — type[0] salah');
        $this->assertSame('KODE-B', $payload['type'][1]['coding'][0]['code'] ?? null, $kelas.' — type[1] salah');
        $this->assertSame($system, $payload['type'][0]['coding'][0]['system'] ?? null, $kelas.' — system type[0] salah');
    }

    /**
     * Penjaga bentuk: `type` tidak boleh berupa objek CodeableConcept tunggal.
     * Ditulis terpisah supaya pesan galatnya menyebut sebabnya, bukan hanya "bukan daftar".
     *
     * @dataProvider listTypeProvider
     */
    public function test_type_is_never_a_single_codeable_concept_object(string $kelas, string $system): void
    {
        $payload = (new $kelas())->setType($system, 'KODE-A', 'Jenis A')->json();

        $this->assertArrayNotHasKey('coding', $payload['type'], $kelas.' — type ditulis sebagai OBJEK, tapaknya 0..*');
    }
}

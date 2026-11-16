<?php

namespace Satusehat\Integration\Tests\Terminology;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEncounter;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\Terminology\SatusehatRules;

/**
 * Uji aturan wajib SATUSEHAT yang bisa diputuskan dari payload.
 *
 * Sumbernya bukan ingatan: `data/satusehat/required-rules.json` memuat aturan beserta URL dokumentasi
 * tiap butir, dan uji terakhir memastikan kelas hanya menjalankan butir yang ada di berkas itu.
 */
class SatusehatRulesTest extends TestCase
{
    /** @return array<string, array<int, array<string, mixed>>> */
    private function aturan(): array
    {
        $isi = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/required-rules.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return array_filter($isi, 'is_array') + [];
    }

    public function test_payload_builder_encounter_lolos(): void
    {
        $encounter = (new PayloadBuilderEncounter())
            ->setId('enc-1')
            ->setStatus('finished')
            ->setClass(new Coding(SatusehatRules::ACT_CODE, 'AMB', 'ambulatory'))
            ->build();

        $this->assertSame([], SatusehatRules::violations($encounter));
    }

    public function test_system_kelas_lain_ditolak(): void
    {
        $encounter = (new PayloadBuilderEncounter())
            ->setId('enc-1')
            ->setStatus('finished')
            ->setClass(new Coding('http://contoh.local/class', 'AMB', 'ambulatory'))
            ->build();

        $pelanggaran = SatusehatRules::violations($encounter);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('v3-ActCode', $pelanggaran[0]);
    }

    public function test_status_di_luar_daftar_ditolak(): void
    {
        $encounter = (new PayloadBuilderEncounter())
            ->setId('enc-1')
            ->setStatus('selesai')
            ->setClass(new Coding(SatusehatRules::ACT_CODE, 'AMB', 'ambulatory'))
            ->build();

        $this->assertStringContainsString('bukan kode yang sah', SatusehatRules::violations($encounter)[0]);
    }

    public function test_observation_lengkap_lolos(): void
    {
        $this->assertSame([], SatusehatRules::violations([
            'resourceType' => 'Observation',
            'status' => 'final',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8867-4']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ]));
    }

    public function test_observation_tanpa_encounter_menyebut_alasan_r4(): void
    {
        $pelanggaran = SatusehatRules::violations([
            'resourceType' => 'Observation',
            'status' => 'final',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8867-4']]],
            'subject' => ['reference' => 'Patient/1000000001'],
        ]);

        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('opsional di R4', $pelanggaran[0]);
    }

    public function test_subject_bukan_patient_ditolak(): void
    {
        $pelanggaran = SatusehatRules::violations([
            'resourceType' => 'Condition',
            'code' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/icd-10', 'code' => 'E11.9']]],
            'subject' => ['reference' => 'Group/1'],
            'encounter' => ['reference' => 'Encounter/2'],
        ]);

        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('Patient', $pelanggaran[0]);
    }

    public function test_patient_wajib_punya_nama(): void
    {
        $pelanggaran = SatusehatRules::violations([
            'resourceType' => 'Patient',
            'identifier' => [['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => '3273010101900001']],
        ]);

        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('name', $pelanggaran[0]);
    }

    public function test_diagnostic_report_lengkap_lolos(): void
    {
        $this->assertSame([], SatusehatRules::violations([
            'resourceType' => 'DiagnosticReport',
            'status' => 'final',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '58410-2']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ]));
    }

    public function test_immunization_tanpa_occurrence_ditolak(): void
    {
        $pelanggaran = SatusehatRules::violations([
            'resourceType' => 'Immunization',
            'status' => 'completed',
            'vaccineCode' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '08']]],
            'patient' => ['reference' => 'Patient/1000000001'],
        ]);

        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('occurrenceDateTime', $pelanggaran[0]);
    }

    public function test_immunization_dengan_occurrencestring_lolos(): void
    {
        $this->assertSame([], SatusehatRules::violations([
            'resourceType' => 'Immunization',
            'status' => 'completed',
            'vaccineCode' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/cvx', 'code' => '08']]],
            'patient' => ['reference' => 'Patient/1000000001'],
            'occurrenceString' => '2026-11-16',
        ]));
    }

    public function test_medication_request_intent_wajib(): void
    {
        $pelanggaran = SatusehatRules::violations([
            'resourceType' => 'MedicationRequest',
            'status' => 'active',
            'subject' => ['reference' => 'Patient/1000000001'],
        ]);

        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('intent', $pelanggaran[0]);
    }

    public function test_resource_yang_belum_dipetakan_tidak_diperiksa(): void
    {
        // Daftarnya dibaca dari berkas aturan, supaya uji ini tidak perlu disunting setiap kali cakupan
        // bertambah. Uji yang harus disunting terus adalah uji yang akhirnya dilonggarkan orang lain.
        $belum = null;
        foreach (['AuditEvent', 'CoverageEligibilityRequest', 'ImagingStudy'] as $calon) {
            if (! isset($this->aturan()[$calon])) {
                $belum = $calon;
                break;
            }
        }
        $this->assertNotNull($belum, 'semua calon sudah dipetakan — pilih resource lain');

        $this->assertSame([], SatusehatRules::violations(['resourceType' => $belum, 'id' => 'x1']));
    }

    public function test_tanpa_resource_type_dilaporkan(): void
    {
        $this->assertSame(['resourceType tidak ada'], SatusehatRules::violations(['status' => 'finished']));
    }

    public function test_setiap_butir_menyebut_sumber_dan_jenis_dikenal(): void
    {
        $jenis = ['kode', 'isi', 'ada', 'referensi', 'sistem', 'salah_satu'];

        foreach ($this->aturan() as $resource => $butir) {
            if (str_starts_with($resource, '_')) {
                continue;
            }
            $this->assertNotEmpty($butir, "{$resource} tanpa aturan");
            foreach ($butir as $satu) {
                $this->assertArrayHasKey('sumber', $satu, "{$resource} tanpa sumber");
                $this->assertContains($satu['jenis'], $jenis, "{$resource} jenis tak dikenal");
            }
        }
    }

    public function test_konstanta_sama_dengan_berkas_aturan(): void
    {
        $aturan = $this->aturan();
        $kode = static function (string $resource, string $elemen) use ($aturan): array {
            foreach ($aturan[$resource] as $butir) {
                if ($butir['elemen'] === $elemen && $butir['jenis'] === 'kode') {
                    return $butir['kode'];
                }
            }

            return [];
        };

        $this->assertSame(SatusehatRules::ENCOUNTER_STATUS, $kode('Encounter', 'status'));
        $this->assertSame(SatusehatRules::OBSERVATION_STATUS, $kode('Observation', 'status'));
        $this->assertSame(SatusehatRules::PROCEDURE_STATUS, $kode('Procedure', 'status'));
    }
}

<?php

namespace Satusehat\Integration\Tests\Terminology;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEncounter;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\Terminology\SatusehatRules;

/**
 * Uji aturan wajib SATUSEHAT yang bisa diputuskan dari payload.
 *
 * Sumbernya dokumentasi SATUSEHAT untuk resource Encounter: `class` wajib Coding dengan sistem
 * v3-ActCode, `status` wajib salah satu kode EncounterStatus. Keduanya tidak diperiksa audit R4 —
 * payload dengan sistem lain tetap lolos R4 dan tetap ditolak platform.
 */
class SatusehatRulesTest extends TestCase
{
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

    public function test_status_di_luar_kode_ditolak(): void
    {
        $encounter = (new PayloadBuilderEncounter())
            ->setId('enc-1')
            ->setStatus('selesai')
            ->setClass(new Coding(SatusehatRules::ACT_CODE, 'AMB', 'ambulatory'))
            ->build();

        $pelanggaran = SatusehatRules::violations($encounter);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('EncounterStatus', $pelanggaran[0]);
    }

    public function test_resource_yang_belum_dipetakan_tidak_diperiksa(): void
    {
        // Resource yang belum punya aturan wajib tidak diperiksa: menuduhnya melanggar tanpa dasar
        // lebih buruk daripada tidak memeriksa sama sekali. Daftarnya dibaca dari berkas aturan,
        // supaya uji ini tidak perlu diperbarui setiap kali cakupan bertambah.
        $aturan = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/required-rules.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $belum = null;
        foreach (['AuditEvent', 'Immunization', 'DiagnosticReport'] as $calon) {
            if (! isset($aturan[$calon])) {
                $belum = $calon;
                break;
            }
        }
        $this->assertNotNull($belum, 'semua calon sudah dipetakan — pilih resource lain untuk uji ini');
        $this->assertSame([], SatusehatRules::violations(['resourceType' => $belum, 'id' => 'x1']));
    }

    public function test_tanpa_resource_type_dilaporkan(): void
    {
        $this->assertSame(['resourceType tidak ada'], SatusehatRules::violations(['status' => 'finished']));
    }

    public function test_berkas_aturan_menyebut_sumber_yang_sama(): void
    {
        $aturan = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/required-rules.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertNotEmpty($aturan['_sumber'] ?? null);

        $kelas = $aturan['Encounter'][0];
        $this->assertStringContainsString('v3-ActCode', $kelas['aturan']);
        $this->assertNotEmpty($kelas['sumber']);

        $status = $aturan['Encounter'][1];
        $this->assertSame(SatusehatRules::ENCOUNTER_STATUS, $status['kode']);
    }

    public function test_observation_lengkap_lolos(): void
    {
        $obs = [
            'resourceType' => 'Observation',
            'status' => 'final',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8867-4']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ];

        $this->assertSame([], SatusehatRules::violations($obs));
    }

    public function test_observation_tanpa_encounter_dilaporkan_sebagai_aturan_satusehat(): void
    {
        $obs = [
            'resourceType' => 'Observation',
            'status' => 'final',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8867-4']]],
            'subject' => ['reference' => 'Patient/1000000001'],
        ];

        $pelanggaran = SatusehatRules::violations($obs);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('R4', $pelanggaran[0], 'alasan wajibnya harus disebut');
    }

    public function test_observation_status_di_luar_kode_ditolak(): void
    {
        $obs = [
            'resourceType' => 'Observation',
            'status' => 'selesai',
            'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8867-4']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ];

        $pelanggaran = SatusehatRules::violations($obs);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('observation-status', $pelanggaran[0]);
    }

    public function test_patient_wajib_punya_nama(): void
    {
        $pasien = [
            'resourceType' => 'Patient',
            'identifier' => [['system' => 'https://fhir.kemkes.go.id/id/nik', 'value' => '3273010101900001']],
        ];

        $pelanggaran = SatusehatRules::violations($pasien);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('name', $pelanggaran[0]);
    }

    public function test_berkas_aturan_memuat_observation_dan_patient(): void
    {
        $aturan = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/required-rules.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertArrayHasKey('Observation', $aturan);
        $this->assertArrayHasKey('Patient', $aturan);

        foreach (['Encounter', 'Observation', 'Patient'] as $resource) {
            foreach ($aturan[$resource] as $butir) {
                $this->assertNotEmpty($butir['sumber'], "{$resource} tanpa sumber");
            }
        }
    }

    public function test_condition_lengkap_lolos(): void
    {
        $kondisi = [
            'resourceType' => 'Condition',
            'code' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/icd-10', 'code' => 'E11.9']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ];

        $this->assertSame([], SatusehatRules::violations($kondisi));
    }

    public function test_condition_tanpa_encounter_dilaporkan(): void
    {
        $kondisi = [
            'resourceType' => 'Condition',
            'code' => ['coding' => [['system' => 'http://hl7.org/fhir/sid/icd-10', 'code' => 'E11.9']]],
            'subject' => ['reference' => 'Patient/1000000001'],
        ];

        $pelanggaran = SatusehatRules::violations($kondisi);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('encounter', $pelanggaran[0]);
    }

    public function test_procedure_status_di_luar_kode_ditolak(): void
    {
        $tindakan = [
            'resourceType' => 'Procedure',
            'status' => 'selesai',
            'code' => ['coding' => [['system' => 'http://snomed.info/sct', 'code' => '80146002']]],
            'subject' => ['reference' => 'Patient/1000000001'],
            'encounter' => ['reference' => 'Encounter/1000000002'],
        ];

        $pelanggaran = SatusehatRules::violations($tindakan);
        $this->assertCount(1, $pelanggaran);
        $this->assertStringContainsString('procedure-status', $pelanggaran[0]);
    }

    public function test_berkas_aturan_memuat_condition_dan_procedure(): void
    {
        $aturan = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/required-rules.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach (['Condition', 'Procedure'] as $resource) {
            $this->assertArrayHasKey($resource, $aturan);
            foreach ($aturan[$resource] as $butir) {
                $this->assertNotEmpty($butir['sumber'], "{$resource} tanpa sumber");
            }
        }

        $this->assertSame(SatusehatRules::PROCEDURE_STATUS, $aturan['Procedure'][0]['kode']);
    }
}

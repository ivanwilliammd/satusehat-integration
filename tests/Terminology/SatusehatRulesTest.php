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

    public function test_resource_lain_tidak_diperiksa(): void
    {
        $this->assertSame([], SatusehatRules::violations(['resourceType' => 'Patient', 'active' => true]));
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
}

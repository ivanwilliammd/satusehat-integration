<?php

namespace Satusehat\Integration\Tests\Docs;

use PHPUnit\Framework\TestCase;

/**
 * Uji ratchet untuk triase konformansi R4.
 *
 * docs/R4_CONFLICT_TRIAGE.md dihasilkan dari audit, jadi ia bisa basi dengan dua cara: builder
 * berubah dan dokumen tidak dibuat ulang, atau seseorang menyunting dokumen itu dengan tangan
 * sehingga ia berhenti mencerminkan kenyataan. Keduanya sama berbahayanya karena dokumen ini
 * dipakai untuk memutuskan mana yang cacat dan mana yang quirk.
 */
class R4TriageTest extends TestCase
{
    public function test_dokumen_triase_sinkron_dengan_audit(): void
    {
        $keluaran = [];
        $kode = 0;
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            .' && python3 scripts/audit-r4-conformance.py --triage --check 2>&1',
            $keluaran,
            $kode
        );

        $this->assertSame(
            0,
            $kode,
            "docs/R4_CONFLICT_TRIAGE.md tidak sinkron dengan builder:\n".implode("\n", $keluaran)
        );
    }

    public function test_setiap_builder_di_luar_r4_ditandai_deprecated(): void
    {
        $keluaran = [];
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            .' && python3 scripts/audit-r4-conformance.py --json 2>/dev/null',
            $keluaran
        );
        $rows = json_decode(implode("\n", $keluaran), true);
        $this->assertIsArray($rows, 'audit tidak menghasilkan JSON');

        $tanpaTanda = [];
        foreach ($rows as $row) {
            if ($row['in_r4']) {
                continue;
            }
            $berkas = __DIR__.'/../../src/Builder/'.$row['builder'].'.php';
            if (! str_contains((string) file_get_contents($berkas), '@deprecated')) {
                $tanpaTanda[] = $row['builder'];
            }
        }

        $this->assertSame(
            [],
            $tanpaTanda,
            'builder untuk resource non-R4 belum ditandai @deprecated: '.implode(', ', $tanpaTanda)
        );
    }
}

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

    public function test_setiap_setter_bocor_ditandai_deprecated(): void
    {
        $keluaran = [];
        $kode = 0;
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            .' && python3 scripts/mark_generic_leaks.py --check 2>&1',
            $keluaran,
            $kode
        );

        $this->assertSame(
            0,
            $kode,
            "setter yang menulis elemen non-R4 belum ditandai:\n".implode("\n", $keluaran)
        );
    }

    public function test_setiap_setter_kategori_b_ditandai(): void
    {
        $keluaran = [];
        $kode = 0;
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            .' && python3 scripts/mark_category_b.py --check 2>&1',
            $keluaran,
            $kode
        );

        $this->assertSame(0, $kode, implode("\n", $keluaran));
    }

    public function test_keputusan_kategori_b_tidak_basi(): void
    {
        $berkas = __DIR__.'/../../docs/r4_category_b_decisions.json';
        $this->assertFileExists($berkas, 'keputusan kategori B belum dibuat');

        $keputusan = json_decode((string) file_get_contents($berkas), true);
        $this->assertIsArray($keputusan);

        $tanpaAlasan = [];
        foreach ($keputusan as $builder => $elemen) {
            foreach ($elemen as $nama => $isi) {
                if (empty($isi['alasan']) || empty($isi['status'])) {
                    $tanpaAlasan[] = "{$builder}.{$nama}";
                }
            }
        }
        $this->assertSame([], $tanpaAlasan, 'keputusan tanpa status/alasan: '.implode(', ', $tanpaAlasan));
    }

    public function test_setiap_builder_di_luar_r4_ditandai_deprecated(): void
    {
        $keluaran = [];
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            // Tanpa `2>/dev/null`: itu sintaks shell Unix dan membuat perintah gagal di cmd.exe,
            // sehingga JSON tidak pernah terbaca dan uji gagal dengan pesan yang menyesatkan.
            .' && python3 scripts/audit-r4-conformance.py --json',
            $keluaran
        );
        $mentah = implode("\n", $keluaran);
        $rows = json_decode($mentah, true);
        $this->assertIsArray($rows, 'audit tidak menghasilkan JSON: '.substr($mentah, 0, 300));

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

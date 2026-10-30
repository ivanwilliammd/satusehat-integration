<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Penjaga angka wiki terhadap kode: indeks PayloadBuilder di wiki dibangkitkan oleh
 * docs/scripts/build-wiki-builder-index.php, jadi keluarannya harus SELALU sama dengan isi
 * src/Builder — bukan angka yang ditulis tangan.
 */
class WikiBuilderIndexTest extends TestCase
{
    private static function script(): string
    {
        return dirname(__DIR__, 2).'/docs/scripts/build-wiki-builder-index.php';
    }

    /** @return array{0:string,1:int} */
    private static function generate(): array
    {
        $root = dirname(__DIR__, 2);
        $out = shell_exec(escapeshellarg(PHP_BINARY).' '.escapeshellarg(self::script()).' 2>&1');
        self::assertIsString($out, 'skrip indeks wiki gagal dijalankan');

        $jumlah = count(glob($root.'/src/Builder/PayloadBuilder*.php') ?: []);

        return [$out, $jumlah];
    }

    public function test_jumlah_kelas_di_indeks_sama_dengan_src_builder(): void
    {
        [$out, $jumlah] = self::generate();

        $this->assertStringContainsString(
            "**{$jumlah} kelas PayloadBuilder**",
            $out,
            'angka kelas di indeks wiki tidak sama dengan jumlah berkas PayloadBuilder*.php'
        );
    }

    public function test_setiap_kelas_punya_baris_tabel(): void
    {
        [$out, $jumlah] = self::generate();

        preg_match_all('/^\| (\d+) \| ([A-Za-z0-9]+) \| PayloadBuilder\2 \| /m', $out, $m);

        $this->assertCount($jumlah, $m[0], 'jumlah baris tabel tidak sama dengan jumlah kelas');
        $this->assertSame(1, (int) $m[1][0], 'penomoran tabel harus mulai dari 1');
        $this->assertSame($jumlah, (int) end($m[1]), 'nomor terakhir harus sama dengan jumlah kelas');
    }

    public function test_pembangun_non_fhir_ditandai_dari_docblock(): void
    {
        [$out] = self::generate();

        $this->assertMatchesRegularExpression(
            '/\d+ pembangun NON-FHIR khusus SATUSEHAT \(BillingStatus, PurificationDecision\)/',
            $out,
            'BillingStatus dan PurificationDecision harus terdeteksi NON-FHIR dari docblock kelasnya'
        );
    }

    public function test_penanda_wajib_ada_di_berkas_sasaran(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'wiki').'.md';
        file_put_contents($tmp, "# halaman tanpa penanda\n");

        exec(
            escapeshellarg(PHP_BINARY).' '.escapeshellarg(self::script())
            .' --splice='.escapeshellarg($tmp).' 2>&1',
            $keluaran,
            $kode
        );

        $this->assertSame(1, $kode, 'berkas tanpa penanda harus ditolak dengan kode keluar 1');
        $this->assertStringContainsString('PENANDA tidak ada', implode("\n", $keluaran));

        @unlink($tmp);
    }

    public function test_readme_menyebut_jumlah_kelas_yang_sama(): void
    {
        [, $jumlah] = self::generate();

        $readme = (string) file_get_contents(dirname(__DIR__, 2).'/README.md');

        $this->assertStringContainsString(
            "**{$jumlah} PayloadBuilder**",
            $readme,
            'README menyebut jumlah kelas yang berbeda dari src/Builder'
        );
    }
}

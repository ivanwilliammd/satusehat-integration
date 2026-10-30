<?php

use PHPUnit\Framework\TestCase;

/**
 * Kontrak versi PHP: yang DIJANJIKAN `composer.json` + diuji CI harus sama dengan versi yang
 * benar-benar dibutuhkan `src/`.
 *
 * Kenapa ada: `composer.json` menjanjikan `^7.4|^8.0|…` sementara `src/Queue/QueueResult.php`
 * memakai properti `readonly` (8.1) dan delapan berkas memakai `match`/`?->` (8.0). Di PHP 7.4
 * composer memasang paketnya tanpa keluhan, lalu pemakai menerima parse error saat kelas itu
 * dimuat. Cacatnya tidak terlihat dari sisi kita (semua uji hijau di 8.5) — hanya di sisi pemakai.
 *
 * Pemeriksa ini menyapu TOKEN, bukan teks: `match(` di dalam komentar/string tidak dihitung, dan
 * token yang tidak dikenal tidak membuat sapuan hampa (ada lantai jumlah berkas terbaca).
 *
 * Batas jujur: yang dibuktikan adalah LANTAI dari konstruksi yang terdaftar di TOKENS. Bentuk lain
 * yang juga menaikkan lantai (promosi konstruktor, tipe union, `mixed`, argumen bernama) sengaja
 * belum dipetakan; menambahkannya cukup satu baris di peta itu.
 */
class PhpVersionContractTest extends TestCase
{
    /** @var array<int,string> token -> versi minimum yang memperkenalkannya */
    private const TOKENS = [
        T_MATCH => '8.0',                        // match expression
        T_NULLSAFE_OBJECT_OPERATOR => '8.0',     // ?->
        T_ATTRIBUTE => '8.0',                    // #[...]
        T_READONLY => '8.1',                     // properti readonly
        T_ENUM => '8.1',
    ];

    public function test_declared_php_floor_matches_what_src_actually_needs(): void
    {
        $floor = $this->floorFromSource();

        $this->assertNotNull($floor, 'sapuan tidak menemukan satu pun konstruksi bertanda versi — pemeriksa hampa, bukan kode bersih');

        $this->assertSame(
            $floor,
            $this->declaredFloor(),
            "src/ butuh PHP {$floor}, tetapi composer.json masih mengizinkan versi di bawahnya. "
            .'Pemakai di versi itu memasang paketnya lalu mendapat parse error saat kelas dimuat.'
        );
    }

    public function test_the_declared_floor_is_actually_run_by_ci(): void
    {
        $file = dirname(__DIR__, 2).'/.github/workflows/run-tests.yml';

        $this->assertFileExists($file);

        preg_match_all('/php:\s*\[([^\]]*)\]/', (string) file_get_contents($file), $m);

        $matrix = [];

        foreach ($m[1] as $daftar) {
            foreach (explode(',', $daftar) as $versi) {
                $matrix[] = trim($versi, " \t\"'");
            }
        }

        $this->assertNotEmpty($matrix, 'matriks CI tidak terbaca — uji ini menjadi hampa');

        $this->assertContains(
            $this->declaredFloor(),
            $matrix,
            'Lantai versi yang dijanjikan tidak pernah dijalankan CI: janji tanpa bukti. '
            .'Matriks sekarang: '.implode(', ', array_unique($matrix))
        );
    }

    /** Lantai tertinggi dari konstruksi yang ditemukan di `src/`. */
    private function floorFromSource(): ?string
    {
        $akar = dirname(__DIR__, 2).'/src';
        $tertinggi = null;
        $berkas = 0;

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($akar));

        foreach ($iterator as $satu) {
            if (! $satu->isFile() || $satu->getExtension() !== 'php') {
                continue;
            }

            $berkas++;

            foreach (token_get_all((string) file_get_contents($satu->getPathname())) as $token) {
                if (! is_array($token) || ! isset(self::TOKENS[$token[0]])) {
                    continue;
                }

                $versi = self::TOKENS[$token[0]];

                if ($tertinggi === null || version_compare($versi, $tertinggi, '>')) {
                    $tertinggi = $versi;
                }
            }
        }

        // Lantai cakupan: sapuan yang berhenti membaca berkas akan melaporkan "tidak ada
        // konstruksi" dan lulus — persis pola hijau-palsu yang sudah tiga kali menggigit.
        $this->assertGreaterThanOrEqual(150, $berkas, "hanya {$berkas} berkas php terbaca di src/");

        return $tertinggi;
    }

    /** Versi terendah yang masih diizinkan `composer.json` → `require.php`. */
    private function declaredFloor(): string
    {
        $composer = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/composer.json'), true);

        $this->assertIsArray($composer, 'composer.json tidak terbaca');
        $this->assertArrayHasKey('php', $composer['require'] ?? [], 'require.php tidak ada');

        preg_match_all('/(\d+\.\d+)/', (string) $composer['require']['php'], $m);

        $this->assertNotEmpty($m[1], 'batas versi php tidak terbaca dari '.$composer['require']['php']);

        usort($m[1], 'version_compare');

        return $m[1][0];
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;

/**
 * Penjaga CAKUPAN jalur berjenjang, bukan sekadar enam pemanggil yang dulu salah.
 *
 * `NestedPushPathTest` membuktikan perilaku enam builder. Uji ini menutup celah yang membuat cacat
 * itu bisa kembali: setiap `push('<a>/<b>', …)` di `src/` harus punya padanan dinamis di
 * `NestedPushPathTest`, dan tidak satu pun boleh menulis kunci literal ke `$this->data`.
 * Builder baru yang memakai jalur berjenjang tanpa uji dinamis akan tampil sebagai GAP.
 *
 * Sapuan pertama versi ini SALAH hijau: ia hanya membangun setiap builder tanpa argumen lalu
 * memeriksa payload kosong — dan payload kosong tidak pernah memuat jalur berjenjang. Yang benar
 * adalah memindai TITIK PEMANGGILAN, bukan keluaran kosong.
 */
class NestedPathSweepTest extends TestCase
{
    private const SRC = __DIR__.'/../../src';
    private const PARITY_TEST = __DIR__.'/NestedPushPathTest.php';

    /** Buang komentar lebih dulu — komentar yang MENJELASKAN larangan tidak boleh dihitung. */
    private function codeWithoutComments(string $file): string
    {
        $code = '';
        foreach (token_get_all((string) file_get_contents($file)) as $token) {
            if (is_array($token)) {
                if (in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                    continue;
                }
                $code .= $token[1];

                continue;
            }
            $code .= $token;
        }

        return $code;
    }

    /** @return list<string> */
    private function srcFiles(): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(self::SRC, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        sort($files);

        return $files;
    }

    /**
     * Titik pemanggilan `push('a/b', …)` → daftar [berkas, jalur bertitik].
     *
     * @return list<array{0: string, 1: string}>
     */
    private function nestedCallSites(): array
    {
        $sites = [];
        foreach ($this->srcFiles() as $file) {
            $code = $this->codeWithoutComments($file);
            if (preg_match_all('/push\(\s*([\'"])([^\'"]*\/[^\'"]*)\1/', $code, $m)) {
                foreach ($m[2] as $path) {
                    $sites[] = [basename($file), str_replace('/', '.', $path)];
                }
            }
        }

        return $sites;
    }

    public function test_every_nested_call_site_has_a_dynamic_counterpart(): void
    {
        $sites = $this->nestedCallSites();
        $this->assertGreaterThanOrEqual(7, count($sites), 'Sapuan hanya menemukan '.count($sites).' titik pemanggilan');

        $parity = (string) file_get_contents(self::PARITY_TEST);
        $gaps = [];
        $interpolated = [];

        foreach ($sites as [$file, $path]) {
            if (str_contains($path, '{')) {
                // Jalur yang disusun dari variabel (mis. `item/{$idx}/location`) tidak bisa
                // dicocokkan sebagai literal; ia dihitung terpisah supaya TITIK BARU terlihat
                // di diff angka, bukan lolos diam-diam.
                $interpolated[] = $file.' → '.$path;

                continue;
            }
            // Padanan dinamis ditulis sebagai jalur bertitik pada kasus uji paritas.
            if (! str_contains($parity, "'".$path."'")) {
                $gaps[] = $file.' → '.$path;
            }
        }

        $this->assertSame([], $gaps, 'Jalur berjenjang tanpa uji dinamis: '.implode(' | ', $gaps));
        $this->assertCount(
            4,
            $interpolated,
            'Jumlah jalur berjenjang ber-variabel berubah (sekarang '.count($interpolated).'): '
            .implode(' | ', $interpolated).' — tinjau, lalu sesuaikan angka ini bila memang disengaja'
        );
    }

    public function test_no_source_writes_a_literal_slash_key(): void
    {
        $hits = [];
        foreach ($this->srcFiles() as $file) {
            $code = $this->codeWithoutComments($file);
            if (preg_match_all('/\$this->data\[\s*[\'"][A-Za-z]+\/[A-Za-z][^\'"]*[\'"]\s*\]/', $code, $m)) {
                foreach ($m[0] as $hit) {
                    $hits[] = basename($file).': '.$hit;
                }
            }
        }

        $this->assertSame([], $hits, 'Penulisan kunci literal berjenjang: '.implode(' | ', $hits));
    }

    public function test_no_test_asserts_a_literal_slash_key(): void
    {
        $hits = [];
        foreach (glob(__DIR__.'/*.php') ?: [] as $file) {
            if (basename($file) === basename(__FILE__)) {
                continue; // berkas ini memuat pola pencarinya sendiri
            }
            $code = $this->codeWithoutComments($file);
            if (preg_match_all('/\[\s*[\'"][A-Za-z]+\/[A-Za-z][^\'"]*[\'"]\s*\]/', $code, $m)) {
                foreach ($m[0] as $hit) {
                    $hits[] = basename($file).': '.$hit;
                }
            }
        }

        $this->assertSame([], $hits, 'Uji mengunci kunci literal berjenjang: '.implode(' | ', $hits));
    }
}

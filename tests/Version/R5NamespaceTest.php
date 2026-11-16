<?php

namespace Satusehat\Integration\Tests\Version;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Uji kesiapan namespace R5.
 *
 * Tujuannya bukan menguji R5 (belum ada perbedaan yang diverifikasi), melainkan menjaga janji
 * yang dibuat ke pemakai: mengganti `use Satusehat\Integration\Builder\X` menjadi
 * `use Satusehat\Integration\R5\Builder\X` tidak boleh mengubah apa pun yang lain — nama kelas,
 * nama method, dan perilaku tetap sama, karena kelas R5 adalah turunan kelas kanon.
 *
 * Kalau suatu saat satu resource benar-benar berbeda di R5, berkas hasil generate untuk resource
 * itu diganti implementasi tulisan tangan dan uji ini akan menuntut perbedaannya DIDAFTARKAN,
 * bukan disembunyikan.
 */
class R5NamespaceTest extends TestCase
{
    private const RUANG = ['Builder', 'DataType'];

    /** @return array<string, array{0:string,1:string}> */
    public static function kelasProvider(): array
    {
        $kasus = [];
        foreach (self::RUANG as $ruang) {
            foreach (glob(__DIR__."/../../src/{$ruang}/*.php") as $berkas) {
                $nama = pathinfo($berkas, PATHINFO_FILENAME);
                if (class_exists("Satusehat\\Integration\\{$ruang}\\{$nama}")) {
                    $kasus["{$ruang}\\{$nama}"] = [$ruang, $nama];
                }
            }
        }
        return $kasus;
    }

    /** @dataProvider kelasProvider */
    public function test_kelas_r5_tersedia(string $ruang, string $nama): void
    {
        $this->assertTrue(
            class_exists("Satusehat\\Integration\\R5\\{$ruang}\\{$nama}"),
            "R5\\{$ruang}\\{$nama} belum ada — jalankan scripts/generate_r5_namespace.py"
        );
    }

    /** @dataProvider kelasProvider */
    public function test_kelas_r5_adalah_turunan_kelas_kanon(string $ruang, string $nama): void
    {
        $kanon = "Satusehat\\Integration\\{$ruang}\\{$nama}";
        $r5 = "Satusehat\\Integration\\R5\\{$ruang}\\{$nama}";
        $this->assertTrue(
            is_subclass_of($r5, $kanon),
            "{$r5} bukan turunan {$kanon}; perbedaan R5 harus dinyatakan sebagai turunan, bukan kelas lepas"
        );
    }

    /** @dataProvider kelasProvider */
    public function test_permukaan_method_r5_sama_dengan_kanon(string $ruang, string $nama): void
    {
        $kanon = new ReflectionClass("Satusehat\\Integration\\{$ruang}\\{$nama}");
        $r5 = new ReflectionClass("Satusehat\\Integration\\R5\\{$ruang}\\{$nama}");

        $publik = static fn (ReflectionClass $c): array => array_values(array_map(
            static fn ($m) => $m->getName(),
            array_filter($c->getMethods(\ReflectionMethod::IS_PUBLIC), static fn ($m) => $m->getDeclaringClass()->getName() !== 'stdClass')
        ));

        $kanon_method = $publik($kanon);
        $r5_method = $publik($r5);
        sort($kanon_method);
        sort($r5_method);

        $this->assertSame(
            $kanon_method,
            $r5_method,
            "permukaan method R5\\{$ruang}\\{$nama} berbeda dari kanon — ganti `use` tidak akan cukup"
        );
    }

    public function test_namespace_r5_sinkron_dengan_kanon(): void
    {
        $keluaran = [];
        $kode = 0;
        exec(
            'cd '.escapeshellarg(__DIR__.'/../..')
            .' && python3 scripts/generate_r5_namespace.py --check 2>&1',
            $keluaran,
            $kode
        );
        $this->assertSame(0, $kode, implode("\n", $keluaran));
    }
}

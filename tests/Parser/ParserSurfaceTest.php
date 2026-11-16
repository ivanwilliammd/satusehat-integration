<?php

namespace Satusehat\Integration\Tests\Parser;

use PHPUnit\Framework\TestCase;

/**
 * Uji RATCHET permukaan parser: setiap builder harus punya parser, dan setiap elemen tingkat atas
 * yang bisa DITULIS builder harus bisa DIBACA kembali oleh parser.
 *
 * Ini bukan uji perilaku — perilaku dijaga golden. Yang dijaga di sini adalah kesimetrian arah:
 * SDK yang bisa mengirim `Encounter.status` tetapi tidak bisa membacanya kembali adalah SDK yang
 * kehilangan separuh kegunaannya, dan tidak ada uji lain yang menangkapnya.
 *
 * Daftar elemen dibaca dari builder, bukan dari parser, supaya uji tidak bisa "lulus" hanya karena
 * parser dan uji sama-sama melewatkan elemen yang sama.
 */
class ParserSurfaceTest extends TestCase
{
    private const BUILDER = __DIR__.'/../../src/Builder';
    private const PARSER = __DIR__.'/../../src/Parser';

    /** @return array<string, array{0:string,1:list<string>}> */
    public static function resourceProvider(): array
    {
        $kasus = [];
        foreach (glob(self::BUILDER.'/PayloadBuilder*.php') as $berkas) {
            $teks = file_get_contents($berkas);
            if (! preg_match("/resourceType\s*=\s*'([^']+)'/", $teks, $m)) {
                continue;
            }
            $elemen = [];
            preg_match_all("/\\\$this->(?:set|push)\(\s*'([^']+)'/", $teks, $k);
            foreach ($k[1] as $jalur) {
                $elemen[] = explode('/', $jalur)[0];
            }
            $elemen = array_values(array_unique(array_diff($elemen, ['resourceType'])));
            $kasus[$m[1]] = [$m[1], $elemen];
        }
        return $kasus;
    }

    /** @dataProvider resourceProvider */
    public function test_setiap_resource_punya_parser(string $resource): void
    {
        $this->assertFileExists(
            self::PARSER."/{$resource}Parser.php",
            "parser untuk {$resource} belum ada — jalankan scripts/generate_parsers.py"
        );
    }

    /** @dataProvider resourceProvider */
    public function test_setiap_elemen_builder_bisa_dibaca(string $resource, array $elemen): void
    {
        $berkas = self::PARSER."/{$resource}Parser.php";
        if (! file_exists($berkas)) {
            $this->fail("parser untuk {$resource} belum ada");
        }
        $teks = file_get_contents($berkas);
        preg_match_all('/function\s+(get[A-Za-z0-9_]*)\s*\(/', $teks, $m);
        $getter = array_flip($m[1]);

        foreach ($elemen as $kunci) {
            $harap = 'get'.ucfirst($kunci);
            $this->assertArrayHasKey(
                $harap,
                $getter,
                "{$resource}Parser tidak punya {$harap}() padahal PayloadBuilder{$resource} menulis '{$kunci}'"
            );
        }
    }

    public function test_parser_hasil_generate_sinkron(): void
    {
        $keluaran = [];
        $kode = 0;
        exec('cd '.escapeshellarg(__DIR__.'/../..').' && python3 scripts/generate_parsers.py --check 2>&1', $keluaran, $kode);
        $this->assertSame(0, $kode, "parser tidak sinkron dengan builder:\n".implode("\n", $keluaran));
    }
}

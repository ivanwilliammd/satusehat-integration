<?php

declare(strict_types=1);

/**
 * Membangun halaman wiki Status-Parity dari SUMBER (src/R5 vs src), supaya angka R5 tidak
 * pernah menyimpang dari kode.
 *
 * Kelas R5 yang sudah punya implementasi sendiri = berkas tanpa penanda `@generated`.
 * Itulah satu-satunya definisi "berbeda dari R4" yang dipakai generator
 * (scripts/generate_r5_namespace.py) — jadi halaman ini membaca penanda yang sama, bukan daftar terpisah.
 *
 * Pakai:
 *   php docs/scripts/build-wiki-status-parity.php                     # cetak ke stdout
 *   php docs/scripts/build-wiki-status-parity.php --splice=FILE.md    # ganti isi di antara penanda
 *   php docs/scripts/build-wiki-status-parity.php --check=FILE.md     # keluar 1 bila berkas basi
 *
 * Penanda di berkas sasaran:
 *   <!-- GENERATED:status-parity:start -->   ...   <!-- GENERATED:status-parity:end -->
 */

$root = dirname(__DIR__, 2);

/** @return list<string> daftar path absolut */
$berkas = static function (string $pola) use ($root): array {
    $daftar = glob($root.'/'.$pola) ?: [];
    sort($daftar);

    return $daftar;
};

// Kelas dasar (Builder.php, DataType.php) bukan resource/datatype: tidak dihitung, sama seperti
// hitungan di PARITY.md.
$tanpaKelasDasar = static fn (array $daftar): array => array_values(array_filter(
    $daftar,
    static fn (string $file): bool => ! in_array(basename($file), ['Builder.php', 'DataType.php'], true)
));

$tanpaPenanda = static function (array $daftar): array {
    $hasil = [];
    foreach ($daftar as $file) {
        if (strpos((string) file_get_contents($file), '@generated') === false) {
            $hasil[] = basename($file, '.php');
        }
    }

    return $hasil;
};

$r4Builder = $tanpaKelasDasar($berkas('src/Builder/PayloadBuilder*.php'));
$r5Builder = $tanpaKelasDasar($berkas('src/R5/Builder/PayloadBuilder*.php'));
$r4DataType = $tanpaKelasDasar($berkas('src/DataType/*.php'));
$r5DataType = $tanpaKelasDasar($berkas('src/R5/DataType/*.php'));
$parser = $berkas('src/Parser/*.php');

$bedaBuilder = $tanpaPenanda($r5Builder);
$bedaDataType = $tanpaPenanda($r5DataType);

$out = [];
$out[] = '<!-- GENERATED:status-parity:start -->';
$out[] = 'Diukur dari `src/` oleh `docs/scripts/build-wiki-status-parity.php` pada '
    .date('Y-m-d').' — jangan disunting tangan.';
$out[] = '';
$out[] = '| bagian | R4 (kanon) | R5 (`src/R5`) | berbeda dari R4 |';
$out[] = '|---|---|---|---|';
$out[] = '| payload builder | '.count($r4Builder).' | '.count($r5Builder).' | '.count($bedaBuilder).' |';
$out[] = '| datatype | '.count($r4DataType).' | '.count($r5DataType).' | '.count($bedaDataType).' |';
$out[] = '| parser | '.count($parser).' | — | — |';
$out[] = '';
$out[] = 'Builder R5 yang implementasinya berbeda dari kanon: '
    .($bedaBuilder === [] ? 'belum ada — semuanya masih turunan apa adanya' : implode(', ', $bedaBuilder)).'.';
$out[] = 'Datatype R5 yang berbeda dari kanon: '
    .($bedaDataType === [] ? 'belum ada' : implode(', ', $bedaDataType)).'.';
$out[] = '<!-- GENERATED:status-parity:end -->';
$block = implode("\n", $out);

$splice = null;
$check = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--splice=')) {
        $splice = substr($arg, 9);
    }
    if (str_starts_with($arg, '--check=')) {
        $check = substr($arg, 8);
    }
}

$pola = '/<!-- GENERATED:status-parity:start -->.*?<!-- GENERATED:status-parity:end -->/s';

if ($splice !== null) {
    $isi = (string) file_get_contents($splice);
    if (! preg_match($pola, $isi)) {
        fwrite(STDERR, "PENANDA tidak ada di {$splice}\n");
        exit(1);
    }
    if (file_put_contents($splice, (string) preg_replace_callback($pola, static fn () => $block, $isi, 1)) === false) {
        exit(1);
    }
    echo "disisipkan: {$splice}\n";
    exit(0);
}

if ($check !== null) {
    $isi = (string) file_get_contents($check);
    preg_match($pola, $isi, $m);
    if (($m[0] ?? '') !== $block) {
        fwrite(STDERR, "BASI: {$check} tidak sama dengan src/R5\n");
        exit(1);
    }
    echo "segar: {$check}\n";
    exit(0);
}

echo $block."\n";

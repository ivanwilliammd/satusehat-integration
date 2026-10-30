<?php

declare(strict_types=1);

/**
 * Membangun indeks PayloadBuilder untuk wiki dari SUMBER (src/Builder), supaya angka
 * dan daftar di wiki tidak pernah menyimpang lagi dari kode.
 *
 * Pakai:
 *   php docs/scripts/build-wiki-builder-index.php                     # cetak ke stdout
 *   php docs/scripts/build-wiki-builder-index.php --splice=FILE.md    # ganti isi di antara penanda
 *   php docs/scripts/build-wiki-builder-index.php --check=FILE.md     # keluar 1 bila berkas basi
 *
 * Penanda di berkas sasaran:
 *   <!-- GENERATED:builder-index:start -->   ...   <!-- GENERATED:builder-index:end -->
 */

$root = dirname(__DIR__, 2);
$files = glob($root.'/src/Builder/PayloadBuilder*.php') ?: [];
sort($files);

$rows = [];
$nonFhir = [];

foreach ($files as $file) {
    $resource = basename($file, '.php');
    $resource = substr($resource, strlen('PayloadBuilder'));
    $source = (string) file_get_contents($file);

    // Judul kelas non-FHIR ditandai di docblock kelasnya ("NON-FHIR"), bukan di daftar terpisah.
    $docblock = substr($source, 0, 1200);
    if (stripos($docblock, 'NON-FHIR') !== false) {
        $nonFhir[] = $resource;
    }

    preg_match_all('/public function ((?:set|add|push|with)[A-Za-z0-9_]+)\s*\(/', $source, $m);
    $methods = array_slice(array_values(array_unique($m[1])), 0, 4);
    $methodText = $methods === [] ? '—' : implode(', ', array_map(static fn ($x) => $x.'()', $methods));

    $slug = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $resource));
    $rows[] = '| '.(count($rows) + 1).' | '.$resource.' | PayloadBuilder'.$resource.' | '.$methodText
        .' | [HL7 FHIR](https://hl7.org/fhir/R4/'.$slug.'.html) |';
}

$total = count($rows);
$r4 = $total - count($nonFhir);
$nonFhirText = $nonFhir === [] ? 'tidak ada' : implode(', ', $nonFhir);

$out = [];
$out[] = '<!-- GENERATED:builder-index:start -->';
$out[] = '**'.$total.' kelas PayloadBuilder** — '.$r4.' resource FHIR R4 + '
    .count($nonFhir).' pembangun NON-FHIR khusus SATUSEHAT ('.$nonFhirText.').';
$out[] = '';
$out[] = 'Indeks di bawah dibangkitkan dari `src/Builder/` oleh '
    .'`docs/scripts/build-wiki-builder-index.php` — jangan disunting tangan.';
$out[] = '';
$out[] = '| # | Resource | Class | Key Methods | FHIR Spec |';
$out[] = '|---|----------|-------|-------------|-----------|';
foreach ($rows as $row) {
    $out[] = $row;
}
$out[] = '<!-- GENERATED:builder-index:end -->';
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

$replace = static function (string $path, string $block): bool {
    $isi = (string) file_get_contents($path);
    $pola = '/<!-- GENERATED:builder-index:start -->.*?<!-- GENERATED:builder-index:end -->/s';
    if (! preg_match($pola, $isi)) {
        fwrite(STDERR, "PENANDA tidak ada di {$path}\n");

        return false;
    }
    $baru = (string) preg_replace_callback($pola, static fn () => $block, $isi, 1);

    return file_put_contents($path, $baru) !== false;
};

if ($splice !== null) {
    if (! $replace($splice, $block)) {
        exit(1);
    }
    echo "disisipkan: {$splice}\n";
    exit(0);
}

if ($check !== null) {
    $isi = (string) file_get_contents($check);
    preg_match('/<!-- GENERATED:builder-index:start -->.*?<!-- GENERATED:builder-index:end -->/s', $isi, $m);
    if (($m[0] ?? '') !== $block) {
        fwrite(STDERR, "BASI: {$check} tidak sama dengan src/Builder\n");
        exit(1);
    }
    echo "segar: {$check}\n";
    exit(0);
}

echo $block."\n";

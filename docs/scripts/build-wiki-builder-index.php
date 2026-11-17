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

$r4Resources = json_decode(
    (string) file_get_contents(__DIR__.'/../../data/r4_elements.json'),
    true
) ?: [];

foreach ($files as $file) {
    $resource = basename($file, '.php');
    $resource = substr($resource, strlen('PayloadBuilder'));
    $source = (string) file_get_contents($file);

    // Keanggotaan R4 diputuskan oleh data resmi, bukan oleh kata kunci di docblock: lima
    // builder (ChargeItemResponse, GenomicStudy, ImagingManifest, ImagingSelection,
    // ResourceGuide) tidak pernah ada di R4 tetapi tidak bertanda NON-FHIR.
    $docblock = substr($source, 0, 1200);
    $namaR4 = $resource === 'ListResource' ? 'List' : $resource;
    $adaDiR4 = isset($r4Resources[$namaR4]);
    if (! $adaDiR4) {
        $nonFhir[] = $resource;
    }

    preg_match_all('/public function ((?:set|add|push|with)[A-Za-z0-9_]+)\s*\(/', $source, $m);
    $methods = array_slice(array_values(array_unique($m[1])), 0, 4);
    $methodText = $methods === [] ? '—' : implode(', ', array_map(static fn ($x) => $x.'()', $methods));

    $rows[] = [
        'resource' => $resource,
        'methods' => $methodText,
        'non_fhir' => ! $adaDiR4,
    ];
}

// Tautan spesifikasi hanya untuk nama yang benar-benar ada di R4; nama NON-FHIR akan
// menghasilkan 404 kalau ditautkan.
$baris = [];
foreach ($rows as $i => $row) {
    $slug = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $row['resource']));
    $spec = $row['non_fhir']
        ? '**not in R4**'
        : '[HL7 FHIR](https://hl7.org/fhir/R4/'.$slug.'.html)';
    $baris[] = '| '.($i + 1).' | '.$row['resource'].' | PayloadBuilder'.$row['resource'].' | '
        .$row['methods'].' | '.$spec.' |';
}

$total = count($rows);
$r4 = $total - count($nonFhir);
$nonFhirText = $nonFhir === [] ? 'none' : implode(', ', $nonFhir);

$out = [];
$out[] = '<!-- GENERATED:builder-index:start -->';
$out[] = '**'.$total.' PayloadBuilder classes** — '.$r4.' FHIR R4 resources + '
    .count($nonFhir).' NON-FHIR builders specific to SATUSEHAT ('.$nonFhirText.').';
$out[] = '';
$out[] = 'The index below is generated from `src/Builder/` by '
    .'`docs/scripts/build-wiki-builder-index.php` — do not edit by hand.';
$out[] = '';
$out[] = '| # | Resource | Class | Key methods | FHIR spec |';
$out[] = '|---|----------|-------|-------------|-----------|';
foreach ($baris as $row) {
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

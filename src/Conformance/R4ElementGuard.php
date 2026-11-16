<?php

declare(strict_types=1);

namespace Satusehat\Integration\Conformance;

use InvalidArgumentException;

/**
 * Penjaga elemen tingkat atas terhadap definisi FHIR R4 resmi.
 *
 * Sebagian builder generik bisa menulis elemen yang tidak ada di resource-nya (mis. `onsetDateTime`
 * pada `Goal`, atau `coverage` langsung di `Claim`). Payload seperti itu tetap lolos validasi
 * struktural karena JSON-nya sah — hanya bukan R4. Kelas ini memeriksa daftar elemen yang benar.
 *
 * Daftar elemen berasal dari `data/r4_elements.json`, yang dihasilkan
 * `scripts/generate_r4_elements.py` dari `profiles-resources.json` HL7 R4 resmi; elemen pilihan
 * (`onset[x]`) sudah diperluas ke nama konkretnya.
 *
 * Pemeriksaan bersifat opt-in (`SATUSEHAT_STRICT_R4=1`) karena mengaktifkannya secara default akan
 * menggagalkan pemanggil yang sudah memakai setter lama — kesalahannya nyata, tetapi menghapus
 * pemanggil produksi bukan tugas pustaka ini.
 */
final class R4ElementGuard
{
    /** Nama resource yang berbeda antara kelas builder dan definisi FHIR. */
    private const ALIAS = ['ListResource' => 'List'];

    /** @var array<string, array<string, true>>|null */
    private static ?array $peta = null;

    /** @return array<int, string> elemen tingkat atas yang tidak ada di R4 untuk resource ini */
    public static function unknown(array $payload): array
    {
        $resourceType = $payload['resourceType'] ?? null;
        if (! is_string($resourceType) || $resourceType === '') {
            return ['resourceType'];
        }

        // `resourceType` tidak muncul di daftar elemen definisi (itu pembeda, bukan elemen isi),
        // dan `ListResource` adalah nama kelas builder untuk resource `List`.
        $sah = self::elements(self::ALIAS[$resourceType] ?? $resourceType);
        $sah['resourceType'] = true;
        if ($sah === ['resourceType' => true]) {
            // Resource yang tidak ada di definisi R4: tidak ada dasar untuk memeriksa.
            return [];
        }

        $asing = [];
        foreach (array_keys($payload) as $kunci) {
            if (! isset($sah[$kunci])) {
                $asing[] = (string) $kunci;
            }
        }
        sort($asing);

        return $asing;
    }

    /** @throws InvalidArgumentException bila ada elemen asing */
    public static function assert(array $payload): void
    {
        $asing = self::unknown($payload);
        if ($asing === []) {
            return;
        }

        $resourceType = $payload['resourceType'] ?? '(tanpa resourceType)';
        throw new InvalidArgumentException(sprintf(
            '%s menulis elemen yang tidak ada di FHIR R4: %s',
            is_string($resourceType) ? $resourceType : '(bukan string)',
            implode(', ', $asing)
        ));
    }

    public static function strictEnabled(): bool
    {
        $nilai = getenv('SATUSEHAT_STRICT_R4');

        return $nilai !== false && ! in_array(strtolower($nilai), ['', '0', 'false', 'off'], true);
    }

    /** @return array<string, true> */
    private static function elements(string $resourceType): array
    {
        if (self::$peta === null) {
            $berkas = __DIR__.'/../../data/r4_elements.json';
            $mentah = is_file($berkas) ? (string) file_get_contents($berkas) : '{}';
            $peta = json_decode($mentah, true);
            self::$peta = [];
            if (is_array($peta)) {
                foreach ($peta as $nama => $daftar) {
                    if (is_array($daftar)) {
                        self::$peta[(string) $nama] = array_fill_keys(array_map('strval', $daftar), true);
                    }
                }
            }
        }

        return self::$peta[$resourceType] ?? [];
    }
}

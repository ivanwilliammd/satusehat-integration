<?php

declare(strict_types=1);

namespace Satusehat\Integration\Terminology;

use InvalidArgumentException;

/**
 * Aturan wajib SATUSEHAT yang bisa diperiksa dari payload saja.
 *
 * Audit R4 menjawab "apakah elemen ini ada di R4". Ia tidak menjawab "apakah isinya bentuk yang
 * diterima SATUSEHAT" — mis. `Encounter.class` wajib memakai sistem v3-ActCode, dan `Encounter`
 * mewajibkan `encounter` pada Observation/Condition/Procedure meski R4 menyebutnya opsional.
 *
 * Aturannya TIDAK ditulis di kelas ini. Berkas `data/satusehat/required-rules.json` memuatnya bersama
 * sumber dokumentasi tiap butir; kelas ini hanya menjalankan butir-butir itu. Menambah resource berarti
 * menambah data, bukan method baru.
 *
 * Yang tidak diklaim: aturan yang butuh data di luar payload (mis. identifier organisasi harus
 * terdaftar di master sarana).
 */
final class SatusehatRules
{
    public const ACT_CODE = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';

    public const ENCOUNTER_STATUS = [
        'planned', 'arrived', 'triaged', 'in-progress', 'onleave',
        'finished', 'cancelled', 'entered-in-error', 'unknown',
    ];

    public const OBSERVATION_STATUS = [
        'registered', 'preliminary', 'final', 'amended', 'corrected',
        'cancelled', 'entered-in-error', 'unknown',
    ];

    public const PROCEDURE_STATUS = [
        'preparation', 'in-progress', 'not-done', 'on-hold', 'stopped',
        'completed', 'entered-in-error', 'unknown',
    ];

    /** @var array<string, array<int, array<string, mixed>>>|null */
    private static ?array $aturan = null;

    /**
     * @param array<string, mixed> $payload
     * @return array<int, string> daftar pelanggaran; kosong berarti lolos
     */
    public static function violations(array $payload): array
    {
        $resourceType = $payload['resourceType'] ?? null;
        if (! is_string($resourceType) || $resourceType === '') {
            return ['resourceType tidak ada'];
        }

        $masalah = [];
        foreach (self::aturan()[$resourceType] ?? [] as $butir) {
            $pesan = self::periksa($payload, $resourceType, $butir);
            if ($pesan !== null) {
                $masalah[] = $pesan;
            }
        }

        return $masalah;
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $butir
     */
    private static function periksa(array $payload, string $resourceType, array $butir): ?string
    {
        $elemen = (string) $butir['elemen'];
        $nilai = self::ambil($payload, str_replace('[x]', '', $elemen));
        $sebut = "{$resourceType}.{$elemen}";
        // SATUSEHAT mewajibkan encounter pada beberapa resource yang di R4 opsional. Alasan itu
        // ditulis di pesan supaya tidak dikira pustaka ini salah membaca spesifikasi.
        $catatanR4 = $elemen === 'encounter' ? ' (SATUSEHAT, meski opsional di R4)' : '';

        return match ((string) $butir['jenis']) {
            'kode' => is_string($nilai) && in_array($nilai, $butir['kode'], true)
                ? null
                : "{$sebut} bukan kode yang sah: ".(is_string($nilai) ? $nilai : '(kosong)'),
            'isi' => is_array($nilai) && $nilai !== []
                ? null
                : "{$sebut} wajib diisi",
            'ada' => $nilai !== null
                ? null
                : "{$sebut} wajib ada{$catatanR4}",
            'referensi' => is_string($nilai['reference'] ?? null)
                && str_starts_with($nilai['reference'], (string) $butir['awalan'])
                ? null
                : "{$sebut} wajib merujuk {$butir['awalan']}",
            'sistem' => ($nilai['system'] ?? $nilai['coding'][0]['system'] ?? null) === $butir['sistem']
                ? null
                : "{$sebut} wajib system {$butir['sistem']}",
            // `occurrence[x]` dan sejenisnya: pilihannya beberapa nama, minimal satu harus ada.
            // Sengaja loop, bukan array_any(): array_any baru ada di PHP 8.4, sedangkan paket ini
            // masih mendukung 8.1 ke atas.
            'salah_satu' => self::salah_satu_ada($payload, $butir['pilihan'])
                ? null
                : "{$sebut} wajib salah satu: ".implode(', ', $butir['pilihan']),
            default => throw new InvalidArgumentException('Jenis aturan tidak dikenal: '.$butir['jenis']),
        };
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<int, string> $pilihan
     */
    private static function salah_satu_ada(array $payload, array $pilihan): bool
    {
        foreach ($pilihan as $kunci) {
            if (isset($payload[$kunci])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function ambil(array $payload, string $jalur): mixed
    {
        $simpul = $payload;
        foreach (explode('.', $jalur) as $kunci) {
            if (! is_array($simpul) || ! array_key_exists($kunci, $simpul)) {
                return null;
            }
            $simpul = $simpul[$kunci];
        }

        return $simpul;
    }

    /** @return array<string, array<int, array<string, mixed>>> */
    private static function aturan(): array
    {
        if (self::$aturan === null) {
            $berkas = __DIR__.'/../../data/satusehat/required-rules.json';
            $isi = json_decode((string) file_get_contents($berkas), true, 512, JSON_THROW_ON_ERROR);
            self::$aturan = array_filter(
                $isi,
                static fn (string $kunci): bool => ! str_starts_with($kunci, '_'),
                ARRAY_FILTER_USE_KEY
            );
        }

        return self::$aturan;
    }
}

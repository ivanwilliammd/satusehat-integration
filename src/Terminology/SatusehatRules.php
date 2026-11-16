<?php

declare(strict_types=1);

namespace Satusehat\Integration\Terminology;

/**
 * Aturan wajib SATUSEHAT yang bisa diperiksa dari payload saja.
 *
 * Audit R4 menjawab "apakah elemen ini ada di R4". Ia tidak menjawab "apakah isinya bentuk yang
 * diterima SATUSEHAT" — mis. `Encounter.class` wajib memakai sistem v3-ActCode; memakai sistem lain
 * tetap lolos R4 tetapi ditolak saat kirim.
 *
 * Yang diperiksa di sini hanya aturan yang dinyatakan dokumentasi SATUSEHAT dan bisa diputuskan tanpa
 * data lain. Aturan yang butuh konteks (mis. identifier organisasi harus terdaftar) tidak diklaim.
 */
final class SatusehatRules
{
    public const ACT_CODE = 'http://terminology.hl7.org/CodeSystem/v3-ActCode';

    /** @var array<int, string> */
    public const ENCOUNTER_STATUS = [
        'planned', 'arrived', 'triaged', 'in-progress', 'onleave',
        'finished', 'cancelled', 'entered-in-error', 'unknown',
    ];

    /**
     * @param array<string, mixed> $payload
     * @return array<int, string> daftar pelanggaran; kosong berarti lolos
     */
    public static function violations(array $payload): array
    {
        $masalah = [];
        $resourceType = $payload['resourceType'] ?? null;

        if (! is_string($resourceType) || $resourceType === '') {
            return ['resourceType tidak ada'];
        }

        if ($resourceType === 'Encounter') {
            $masalah = array_merge($masalah, self::encounter($payload));
        }

        return $masalah;
    }

    /** @param array<string, mixed> $payload @return array<int, string> */
    private static function encounter(array $payload): array
    {
        $masalah = [];

        $kelas = $payload['class'] ?? null;
        if (! is_array($kelas)) {
            $masalah[] = 'Encounter.class wajib ada';
        } else {
            // Encounter.class bertipe Coding (bukan CodeableConcept): sistem ada langsung di
            // `class.system`. Bentuk `coding[0].system` tetap diterima bila pemanggil membungkusnya.
            $coding = $kelas['system'] ?? $kelas['coding'][0]['system'] ?? null;
            if ($coding !== self::ACT_CODE) {
                $masalah[] = 'Encounter.class wajib system '.self::ACT_CODE.', dapat '.(is_string($coding) ? $coding : '(kosong)');
            }
        }

        $status = $payload['status'] ?? null;
        if (! is_string($status) || ! in_array($status, self::ENCOUNTER_STATUS, true)) {
            $masalah[] = 'Encounter.status bukan kode EncounterStatus: '.(is_string($status) ? $status : '(kosong)');
        }

        return $masalah;
    }
}

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

        $masalah = match ($resourceType) {
            'Encounter' => array_merge($masalah, self::encounter($payload)),
            'Observation' => array_merge($masalah, self::observation($payload)),
            'Patient' => array_merge($masalah, self::patient($payload)),
            'Condition' => array_merge($masalah, self::condition($payload)),
            'Procedure' => array_merge($masalah, self::procedure($payload)),
            default => $masalah,
        };

        return $masalah;
    }

    /** @var array<int, string> */
    public const OBSERVATION_STATUS = [
        'registered', 'preliminary', 'final', 'amended', 'corrected',
        'cancelled', 'entered-in-error', 'unknown',
    ];

    /**
     * Dokumentasi SATUSEHAT menandai empat elemen Observation sebagai wajib: status, code, subject,
     * encounter. `encounter` wajib menurut SATUSEHAT meski di R4 opsional — itu perbedaan yang
     * disengaja, bukan kelalaian.
     *
     * @param array<string, mixed> $payload
     * @return array<int, string>
     */
    private static function observation(array $payload): array
    {
        $masalah = [];

        $status = $payload['status'] ?? null;
        if (! is_string($status) || ! in_array($status, self::OBSERVATION_STATUS, true)) {
            $masalah[] = 'Observation.status bukan kode observation-status: '.(is_string($status) ? $status : '(kosong)');
        }

        $coding = $payload['code']['coding'] ?? null;
        if (! is_array($coding) || $coding === []) {
            $masalah[] = 'Observation.code.coding wajib diisi minimal satu';
        }

        $subject = $payload['subject']['reference'] ?? null;
        if (! is_string($subject) || ! str_starts_with($subject, 'Patient/')) {
            $masalah[] = 'Observation.subject wajib merujuk Patient';
        }

        if (! isset($payload['encounter']['reference'])) {
            $masalah[] = 'Observation.encounter wajib ada (SATUSEHAT, meski opsional di R4)';
        }

        return $masalah;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<int, string>
     */
    private static function patient(array $payload): array
    {
        $masalah = [];

        $identifier = $payload['identifier'] ?? null;
        if (! is_array($identifier) || $identifier === []) {
            $masalah[] = 'Patient.identifier wajib diisi minimal satu';
        }

        $nama = $payload['name'] ?? null;
        if (! is_array($nama) || $nama === []) {
            $masalah[] = 'Patient.name wajib diisi minimal satu';
        }

        return $masalah;
    }

    /** @var array<int, string> */
    public const PROCEDURE_STATUS = [
        'preparation', 'in-progress', 'not-done', 'on-hold', 'stopped',
        'completed', 'entered-in-error', 'unknown',
    ];

    /**
     * @param array<string, mixed> $payload
     * @return array<int, string>
     */
    private static function condition(array $payload): array
    {
        $masalah = [];

        if (! is_array($payload['code']['coding'] ?? null) || $payload['code']['coding'] === []) {
            $masalah[] = 'Condition.code.coding wajib diisi minimal satu';
        }
        $masalah = array_merge($masalah, self::subject_dan_encounter('Condition', $payload));

        return $masalah;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<int, string>
     */
    private static function procedure(array $payload): array
    {
        $masalah = [];

        $status = $payload['status'] ?? null;
        if (! is_string($status) || ! in_array($status, self::PROCEDURE_STATUS, true)) {
            $masalah[] = 'Procedure.status bukan kode procedure-status: '.(is_string($status) ? $status : '(kosong)');
        }
        if (! is_array($payload['code']['coding'] ?? null) || $payload['code']['coding'] === []) {
            $masalah[] = 'Procedure.code.coding wajib diisi minimal satu';
        }
        $masalah = array_merge($masalah, self::subject_dan_encounter('Procedure', $payload));

        return $masalah;
    }

    /**
     * Dua elemen yang sama-sama wajib di Condition dan Procedure; dipisah supaya pesannya tidak
     * berbeda untuk dua resource yang aturannya identik.
     *
     * @param array<string, mixed> $payload
     * @return array<int, string>
     */
    private static function subject_dan_encounter(string $resource, array $payload): array
    {
        $masalah = [];

        $subject = $payload['subject']['reference'] ?? null;
        if (! is_string($subject) || ! str_starts_with($subject, 'Patient/')) {
            $masalah[] = "{$resource}.subject wajib merujuk Patient";
        }
        if (! isset($payload['encounter']['reference'])) {
            $masalah[] = "{$resource}.encounter wajib ada (SATUSEHAT, meski opsional di R4)";
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

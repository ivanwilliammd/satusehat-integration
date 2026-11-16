<?php

declare(strict_types=1);

namespace Satusehat\Integration\Terminology;

/**
 * Objek non-standar SATUSEHAT yang dipakai di samping FHIR R4.
 *
 * SATUSEHAT bukan hanya R4: identitas pasien memakai system identifier Kemenkes, dan alamat memakai
 * extension administratif berisi kode wilayah enam tingkat. Semuanya SAH — tetapi tidak ada di
 * definisi FHIR R4, jadi tidak akan tertangkap audit konformansi R4 dan tidak boleh dikarang.
 *
 * Nilainya tidak ditulis ulang di sini: satu-satunya sumber adalah `data/satusehat/nonstandard.json`
 * yang mencantumkan URL rujukannya, dan uji memastikan kelas ini tidak menyimpang dari berkas itu.
 */
final class SatusehatNonStandard
{
    public const NIK = 'https://fhir.kemkes.go.id/id/nik';
    public const PASPORT = 'https://fhir.kemkes.go.id/id/paspor';
    public const KK = 'https://fhir.kemkes.go.id/id/kk';
    public const IHS_NUMBER = 'https://fhir.kemkes.go.id/id/patient-ihs-number';

    public const EXT_ADMINISTRATIVE_CODE = 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode';
    public const EXT_BIRTH_PLACE = 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace';
    public const EXT_CITIZENSHIP_STATUS = 'https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus';

    public const EXT_PATIENT_RELIGION = 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion';
    public const EXT_PATIENT_DISABILITY = 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-disability';
    public const EXT_PATIENT_CITIZENSHIP = 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-citizenship';
    public const EXT_PATIENT_BIRTH_TIME = 'https://fhir.kemkes.go.id/r4/StructureDefinition/patient-birthTime';

    public const PROFILE_PATIENT = 'https://fhir.kemkes.go.id/r4/StructureDefinition/Patient';

    /**
     * Extension bernilai CodeableConcept pada Patient: agama, disabilitas, kewarganegaraan.
     *
     * Ketiganya 0..1, jadi satu nilai per pasien. Nilai yang diberikan pemanggil; yang tetap di sini
     * hanya canonical URL-nya — itu bagian yang tidak boleh dikarang.
     *
     * @param array<string, mixed> $codeable mis. ['coding' => [['system' => ..., 'code' => ..., 'display' => ...]]]
     * @return array<string, mixed>
     */
    public static function codeableExtension(string $canonical, array $codeable): array
    {
        $sah = [self::EXT_PATIENT_RELIGION, self::EXT_PATIENT_DISABILITY, self::EXT_PATIENT_CITIZENSHIP];
        if (! in_array($canonical, $sah, true)) {
            throw new \InvalidArgumentException(
                'Extension bukan CodeableConcept pasien: '.$canonical
            );
        }
        if ($codeable === []) {
            throw new \InvalidArgumentException('Nilai CodeableConcept kosong');
        }

        return ['url' => $canonical, 'valueCodeableConcept' => $codeable];
    }

    /**
     * Extension birthTime pada Patient (dateTime).
     *
     * @return array<string, mixed>
     */
    public static function birthTimeExtension(string $dateTime): array
    {
        return ['url' => self::EXT_PATIENT_BIRTH_TIME, 'valueDateTime' => $dateTime];
    }

    /**
     * Extension administrativeCode untuk Address.
     *
     * Bentuknya bersarang: satu extension induk, lalu sub-extension dengan `url` tetap
     * `province`, `city`, `district`, `village`, `rt`, `rw`. Yang diberikan hanya yang terisi;
     * sub-extension kosong tidak dikirim.
     *
     * @param array<string, string> $kode mis. ['province' => '32', 'city' => '3273', ...]
     * @return array<string, mixed>
     */
    public static function administrativeCodeExtension(array $kode): array
    {
        $sah = ['province', 'city', 'district', 'village', 'rt', 'rw'];
        $asing = array_diff(array_keys($kode), $sah);
        if ($asing) {
            throw new \InvalidArgumentException(
                'Tingkat administrativeCode tidak dikenal: '.implode(', ', $asing).
                '. Yang sah: '.implode(', ', $sah)
            );
        }

        $sub = [];
        foreach ($sah as $tingkat) {
            if (! isset($kode[$tingkat]) || $kode[$tingkat] === '') {
                continue;
            }
            $sub[] = ['url' => $tingkat, 'valueString' => (string) $kode[$tingkat]];
        }

        return ['url' => self::EXT_ADMINISTRATIVE_CODE, 'extension' => $sub];
    }
}

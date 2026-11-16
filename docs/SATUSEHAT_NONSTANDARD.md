# Objek non-standar SATUSEHAT

SATUSEHAT berjalan di FHIR R4, tetapi tidak hanya R4. Ada dua lapisan tambahan yang sah dan wajib
dipakai, dan keduanya **tidak akan tertangkap** audit konformansi R4 karena memang bukan bagian dari
spesifikasi HL7.

## 1. System identifier Kemenkes

| Keperluan | `identifier.system` |
| --- | --- |
| NIK | `https://fhir.kemkes.go.id/id/nik` |
| Paspor | `https://fhir.kemkes.go.id/id/paspor` |
| Kartu Keluarga | `https://fhir.kemkes.go.id/id/kk` |
| IHS Number | `https://fhir.kemkes.go.id/id/patient-ihs-number` |

Sumber: dokumentasi resource Patient SATUSEHAT
(<https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/patient>) — contoh JSON di halaman
itu memakai `use: official` bersama keempat system tersebut.

Memakai `system` lain (mis. `https://fhir.kemkes.go.id/id/nik-pasien`) menghasilkan identifier yang
tidak akan ditemukan saat pencarian pasien, meski payload-nya lolos validasi R4.

## 2. Extension administratif alamat

| Extension | Canonical |
| --- | --- |
| Administrative code | `https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode` |
| Birth place | `https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace` |
| Citizenship status | `https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus` |

`administrativeCode` bersarang: satu extension induk, lalu sub-extension dengan `url` **tetap**
`province`, `city`, `district`, `village`, `rt`, `rw`, dan nilainya `valueString`.

```json
{
  "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
  "extension": [
    { "url": "province", "valueString": "32" },
    { "url": "city",     "valueString": "3273" },
    { "url": "district", "valueString": "327301" },
    { "url": "village",  "valueString": "3273010001" }
  ]
}
```

Sumber: halaman extension AdministrativeCode pada SATUSEHAT FHIR R4 Implementation Guide
(<https://simplifier.net/guide/SATUSEHAT-FHIR-R4-Implementation-Guide/Home/Extensions/AdministrativeCode.page.md>).

## Cara kode ini menjaganya

Nilai-nilai tersebut tidak ditulis lepas di kode. `data/satusehat/nonstandard.json` memuatnya beserta
sumber dan tanggal pengambilan; `src/Terminology/SatusehatNonStandard.php` menyediakan konstanta dan
helper `administrativeCodeExtension()`; dan `tests/Terminology/SatusehatNonStandardTest.php`
memastikan konstanta sama dengan berkas rujukan.

Konsekuensinya: mengganti satu URL karena ingatan yang berbeda akan membuat uji gagal sampai berkas
rujukan ikut diubah — dan perubahan pada rujukan terlihat di diff.

## Batas yang belum ditutup

- Extension lain (mis. `patient-religion`, `patient-disability`, `citizenshipStatus`) baru dicatat
  URL-nya, belum punya helper pembentuk.
- Aturan wajib SATUSEHAT per resource (mis. `Encounter.class` harus memakai sistem v3-ActCode) belum
  dijadikan uji; yang ada baru daftar system identifier dan extension alamat.

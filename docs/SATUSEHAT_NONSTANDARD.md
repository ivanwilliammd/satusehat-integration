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
| Religion | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion` |
| Disability | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-disability` |
| Citizenship (patient) | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-citizenship` |
| Birth time | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-birthTime` |

Religion, disability, and patient-citizenship are `CodeableConcept` (0..1); birthTime is a `dateTime`.
`SatusehatNonStandard::codeableExtension()` and `birthTimeExtension()` build those wrappers and refuse
a canonical that is not one of the listed extensions — a typo here means the extension is silently
ignored by the platform. Sources: the Patient profile page of the SATUSEHAT FHIR R4 Implementation
Guide.

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

## 3. Aturan wajib yang bisa diperiksa dari payload

Audit R4 menjawab "apakah elemen ini ada di R4". Ia tidak menjawab "apakah isinya bentuk yang diterima
SATUSEHAT". `SatusehatRules::violations()` memeriksa yang bisa diputuskan tanpa data lain:

| Resource | Aturan | Sumber |
| --- | --- | --- |
| Encounter | `class` wajib `Coding` dengan sistem `http://terminology.hl7.org/CodeSystem/v3-ActCode` | dokumentasi Encounter SATUSEHAT |
| Encounter | `status` wajib salah satu kode `EncounterStatus` | <http://hl7.org/fhir/encounter-status> |

Payload dengan `class` bersistem lain tetap lolos R4 dan tetap ditolak platform. Aturannya disimpan di
`data/satusehat/required-rules.json` bersama sumbernya, dan uji memastikan kode tidak menyimpang dari
berkas itu.

Catatan tipe: `Encounter.class` adalah `Coding`, bukan `CodeableConcept` — sistemnya ada di
`class.system`. Pemeriksa menerima kedua bentuk, tetapi bentuk yang benar bukan yang kedua.

## Batas yang belum ditutup

- Aturan wajib baru mencakup `Encounter`; resource lain belum dipetakan.
- Aturan yang butuh data luar payload (mis. identifier organisasi harus terdaftar di master sarana)
  tidak bisa diputuskan pustaka ini dan tidak diklaim.


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
SATUSEHAT". `SatusehatRules::violations()` memeriksa yang bisa diputuskan tanpa data lain.

| Resource | Aturan |
| --- | --- |
| Encounter | `class` `Coding` bersistem v3-ActCode; `status` dari EncounterStatus |
| Observation | `status` · `code.coding` · `subject` → Patient · `encounter` |
| Patient | `identifier` · `name` |
| Condition | `code.coding` · `subject` → Patient · `encounter` |
| Procedure | `status` · `code.coding` · `subject` → Patient · `encounter` |
| DiagnosticReport | `status` · `code.coding` · `subject` → Patient · `encounter` |
| Immunization | `status` · `vaccineCode.coding` · `patient` → Patient · salah satu `occurrence[x]` |
| MedicationRequest | `status` · `intent` · `subject` → Patient |

Aturannya **tidak ditulis di kelas**. `data/satusehat/required-rules.json` memuat tiap butir bersama
jenisnya (`kode`, `isi`, `ada`, `referensi`, `sistem`, `salah_satu`) dan URL dokumentasi sumbernya;
`SatusehatRules` hanya menjalankan butir itu. Menambah resource = menambah data, bukan method baru.
Uji memastikan setiap butir punya sumber, jenisnya dikenal, dan konstanta kode tidak menyimpang dari
berkas.

`encounter` wajib pada Observation/Condition/Procedure/DiagnosticReport menurut SATUSEHAT meski R4
menyebutnya opsional. Perbedaan itu disengaja dan pesan pelanggarannya menyebutnya, supaya tidak dikira
bug pustaka ini.

## 4. Kelima port memakai aturan yang sama

| Port | Cara membawa aturan |
| --- | --- |
| PHP | dibaca dari `data/satusehat/required-rules.json` |
| Node | `resolveJsonModule` — JSON disalin `tsc` ke `dist` |
| Python | artefak generate `_rules_generated.py` + SHA |
| Go | `go:embed` |
| Rust | `include_str!` saat kompilasi |

Empat salinan berarti bisa menyimpang. `scripts/check-rules-drift.sh` membandingkan sha256 kelima
berkas (repo yang tidak ada dilewati, jadi aman dijalankan di CI satu repo) dan keluar 1 bila ada yang
berbeda.

Saat ini kelimanya sama: `335561b0a605944d`.

## Batas yang belum ditutup

- Aturan wajib mencakup 8 resource; sisanya belum dipetakan.
- Aturan bersyarat (mis. `Procedure.performer` wajib hanya bila ada tindakan) belum bisa diputuskan
  tanpa konteks, jadi belum diperiksa.
- Aturan yang butuh data luar payload (mis. identifier organisasi harus terdaftar di master sarana)
  tidak bisa diputuskan pustaka ini dan tidak diklaim.


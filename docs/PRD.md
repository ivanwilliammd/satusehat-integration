# PRD — satusehat-integration (PHP SDK SATUSEHAT FHIR R4)

Status: v1.0 · 2026-10-27 · Pemilik produk: Ivan William Harsono
Dokumen teknis pendamping: `docs/SRD.md`. Sumber kebenaran kode: `src/`, `database/`,
`tests/`, `CHANGELOG.md`.

## 1. Ringkasan Eksekutif

`satusehat-integration` adalah pustaka PHP open-source (MIT) untuk membangun payload FHIR R4
dan memanggil SATUSEHAT — platform data kesehatan nasional Kemenkes. Pustaka ini dipakai
sebagai **klien native**: ia menyusun resource, menandatangani permintaan OAuth2, dan membaca
jawaban; ia **bukan** server FHIR dan tidak menyimpan data klinis.

Terukur per 2026-10-27 (hitungan berkas di repo):

| Bagian | Jumlah |
|---|---|
| `src/Builder/` (141 `PayloadBuilder*` + basis `Builder`) | 142 berkas |
| `src/DataType/` (value object FHIR) | 32 kelas |
| `src/FHIR/` (resource lama, jalur kompatibilitas) | 49 kelas |
| `src/Parser/` | 18 kelas |
| `src/Terminology/` | 22 kelas |
| `src/Queue/` (antrean SQLite: worker, DLQ, rate limit) | 8 kelas |
| `src/Console/Commands/` | 3 perintah |
| `database/seeders/csv/*.stub` (master data) | 18 berkas |
| Kelas uji | 45 (`tests/FHIR` 43, `tests/Unit` 1, `ArchTest` 1) |
| Uji yang berjalan | **860 uji, 1380 asersi, 0 gagal** (2026-10-27) |

## 2. Latar Belakang & Masalah

Integrasi SATUSEHAT di rumah sakit Indonesia berulang kali mengulang pekerjaan yang sama:
menyusun JSON FHIR yang lolos validasi Kemenkes, mengelola token OAuth2 yang kedaluwarsa di
tengah kiriman, dan menebak arti kode galat validasi yang hanya berupa angka.

Masalah yang harus dijawab pustaka ini:

1. **Bentuk FHIR mahal ditulis ulang.** Satu `Encounter` yang sah butuh puluhan elemen
   bersarang; menulisnya dengan array tangan menghasilkan galat validasi yang tidak informatif.
2. **Token mengganggu alur.** Token SATUSEHAT berumur pendek; kiriman yang berjalan berjam-jam
   gagal karena token mati di tengah jalan.
3. **Kode galat tidak terbaca.** SATUSEHAT menjawab dengan nomor aturan, bukan kalimat.
4. **Kiriman hilang tanpa jejak.** Kiriman ke fasilitas dengan jaringan buruk perlu antrean,
   percobaan ulang, dan catatan; `try/catch` di controller tidak cukup.
5. **Master data nasional besar.** ICD-10, ICD-9-CM, KFA, KPTL, LOINC, SNOMED, UCUM, Kode
   Wilayah: menyalinnya manual ke setiap aplikasi berarti drift data antar institusi.

## 3. Pengguna & Persona

| Persona | Kebutuhan utama |
|---|---|
| Pengembang aplikasi RS/klinik (PHP/Laravel) | Menyusun resource FHIR sah tanpa hafal spesifikasi |
| Integrator/interop engineer | Kontrol token, retry, dan idempotensi kiriman |
| Analis data / coding | Master data nasional yang dapat di-seed dan dipakai ulang |
| Pengelola produk turunan (v1.emr, fusion, claim) | Satu pustaka yang sama dipakai banyak repo |

## 4. Tujuan & Metrik Keberhasilan

| Tujuan | Metrik |
|---|---|
| Payload sah tanpa menyusun array tangan | Setiap resource punya `PayloadBuilderXxx` dengan `json()`/`build()` |
| Kiriman tahan token mati | `SSRequest` memperbarui token sendiri dan mengulang sekali |
| Galat dapat ditindaklanjuti | Kode aturan dipetakan ke pesan manusia (`SSValidationError`) |
| Tidak ada kiriman hilang | Antrean persisten + DLQ + penghitung percobaan |
| Master data siap pakai | 18 berkas CSV + seeder, satu perintah |
| Paritas lintas bahasa | Versi PHP/Node/Python/Go bergerak bersama (satu nomor rilis) |

## 5. Ruang Lingkup

**Masuk ruang lingkup**
- Pembangun payload FHIR R4 (142 kelas) + `DataType` (32) sebagai nilai tersusun.
- Klien HTTP `SSRequest`/`SSResponse`: GET/POST/PUT/PATCH/DELETE, PATCH FHIRPath, `postBundle`.
- OAuth2 client_credentials ke SATUSEHAT; token disimpan dan diperbarui otomatis.
- Antrean persisten SQLite: `EnqueueSatusehat`, `ProcessSatusehatQueue`, `QueueStatusSatusehat`,
  worker, DLQ, pembatas laju, pengawas.
- Pemetaan 558 kode aturan validasi + 14 bentuk jawaban galat.
- Master data (18 CSV) + seeder + migrasi `.stub` untuk aplikasi yang memakainya.
- `TerminologyClient`: klien ke SmarTerm (`terminology.ivanmd.id`) — Sanctum PAT **atau**
  OIDC client_credentials dengan refresh otomatis.
- `TenantCredentialResolver`: kredensial per tenant (env / array / database) untuk aplikasi
  multitenant.
- `KYC`: pembangkit kunci RSA + enkripsi untuk alur verifikasi identitas SATUSEHAT.

**Di luar ruang lingkup**
- Server FHIR: pustaka ini tidak menyajikan `CapabilityStatement`, tidak menegakkan
  pencarian FHIR (`_include`, `searchset`), dan tidak menyimpan data pasien. Peran itu milik
  `v1.emr`/`terminology` (lihat skill `health-tech-ecosystem`, `references/fhir-conformance-audit.md`).
- Validasi penuh terhadap StructureDefinition resmi (validasi masih di sisi SATUSEHAT).
- Antarmuka web/dashboard: semua lewat CLI atau kode pemanggil.

## 6. Kebutuhan Fungsional

Status diukur dari berkas yang ada di repo, bukan dari niat.

| ID | Kebutuhan | Status |
|---|---|---|
| FR-01 | Setiap resource FHIR punya builder fluent yang menghasilkan array siap kirim | Ada: 141 kelas `PayloadBuilder*` |
| FR-02 | Tipe kompleks FHIR sebagai objek tersusun, `toArray()` rekursif, null dibuang | Ada: 32 kelas `DataType` |
| FR-03 | Builder menerima objek bertipe maupun string biasa; rujukan diberi awalan tipe resource | Ada |
| FR-04 | Nilai `status`/`intent` yang tidak sah ditolak saat dibangun, bukan saat dikirim | Ada (`InvalidArgumentException`) |
| FR-05 | Klien HTTP dengan retry eksponensial, hormat `429`/`Retry-After`, refresh token saat `401` | Ada: `SSRequest` |
| FR-06 | Jawaban dibungkus: sukses, pesan galat, id resource | Ada: `SSResponse` |
| FR-07 | Kode aturan validasi dipetakan ke pesan | Ada: `SSValidationError` (558 aturan, 14 tipe jawaban) |
| FR-08 | Antrean persisten dengan percobaan ulang, DLQ, pembatas laju, pemantauan | Ada: `src/Queue` (8 kelas) + 3 perintah artisan |
| FR-09 | Master data nasional dapat di-seed dari CSV | Ada: 18 CSV + seeder + migrasi `.stub` |
| FR-10 | Klien terminologi (SmarTerm) dengan dua mode auth | Ada: `TerminologyClient` |
| FR-11 | Kredensial per tenant tanpa menyalin kode | Ada: `TenantCredentialResolver` |
| FR-12 | Dukungan Laravel (service provider, config, migrasi) maupun PHP murni | Ada: `SatusehatIntegrationServiceProvider` |
| FR-13 | Semua jalur di atas punya uji otomatis | Sebagian: 45 kelas uji, terpusat di `tests/FHIR` |
| FR-14 | Paritas perilaku dengan SDK Node/Python/Go | Berjalan; dijaga manual per rilis (belum ada uji paritas) |

## 7. Kebutuhan Non-Fungsional

- **Kompatibilitas**: PHP `^8.0`; `illuminate/*` `^8.0`–`^13.0` (satu pustaka dipakai repo yang
  berbeda major Laravel). Menambah dependensi baru wajib punya alasan kuat. Batas bawah ini BUKAN
  selera: `match`/`?->` sudah dipakai di `src/`, dan `tests/Unit/PhpVersionContractTest.php`
  memerah bila janji di `composer.json` turun di bawah konstruksi yang benar-benar dipakai.
- **Tanpa state global**: kredensial dibaca lewat config/env saat dipakai, bukan disimpan di
  properti statis — supaya satu proses dapat melayani beberapa tenant.
- **Kegagalan terlihat**: setiap jalur yang menelan galat wajib mencatatnya (log atau DLQ),
  tidak boleh `catch (\Throwable) {}` kosong.
- **Idempotensi kiriman**: pengiriman ulang tidak boleh membuat resource ganda di SATUSEHAT;
  id yang sudah ada disimpan dan dipakai untuk PUT, bukan POST.
- **Keamanan**: rahasia hanya dari env/config; tidak ada kredensial di repo, contoh `.env`
  hanya berisi placeholder; token tidak dicetak ke log.
- **Keterbacaan kode**: nama simbol Inggris, teks pesan Indonesia.

## 8. Batasan, Asumsi & Ketergantungan

- Bergantung pada ketersediaan SATUSEHAT (DEV/STG/PROD) dan pada `terminology.ivanmd.id` untuk
  jalur terminologi; keduanya bisa mati — pustaka harus gagal dengan pesan jelas, bukan diam.
- Master data berasal dari berkas resmi Kemenkes; versi dataset dilacak per rilis (KPTL v1.7,
  KFA v2, ICD-9-CM e-klaim).
- Pustaka dijual/dibagikan sebagai paket Composer; `vendor/` tidak di-commit di repo ini
  (berbeda dari repo aplikasi Ivan yang zero-build).

## 9. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Perubahan spesifikasi SATUSEHAT memecah builder | Rilis minor sering; `CHANGELOG.md` wajib per perubahan bentuk |
| Master data basi | Berkas `.stub` dilacak, tanggal sumber dicatat di commit |
| Antrean SQLite tidak cocok untuk beban besar | Antrean untuk kiriman asinkron, bukan pengganti pekerja berkapasitas tinggi |
| Dua jalur pembangun resource (`PayloadBuilder` vs `FHIR/*` lama) menyimpang | Jalur baru hanya `PayloadBuilder`; `FHIR/*` dibekukan (46 dari 49 belum memakai builder — utang T-01 di SRD) |
| Uji tidak pernah menyentuh jaringan nyata | Uji memakai stub; verifikasi ke SATUSEHAT dilakukan manual per rilis (dicatat di `CHANGELOG`) |

## 10. Fase

1. **Fase 1–3 (selesai)**: DataType, `SSRequest`/`SSResponse`, 17 builder pertama, migrasi
   bertahap dari `fhirvel-ss`, 782 uji (kini 860).
2. **Fase 4 (berjalan)**: paritas resource penuh (142 builder), antrean persisten, klien
   terminologi, kredensial per tenant.
3. **Fase 5 (rencana)**: uji paritas lintas bahasa otomatis, validasi lokal terhadap
   StructureDefinition, jalur `meta.profile` bawaan.

## 11. Kriteria Penerimaan Rilis

Sebuah rilis boleh naik nomor hanya bila:

- `composer test` hijau pada matriks PHP yang didukung (CI: 8.0 + 8.3, Linux + Windows);
- `CHANGELOG.md` memuat entri untuk versi itu;
- tidak ada perubahan yang memutus API publik tanpa catatan migrasi;
- master data yang berubah disertai berkas `.stub` yang cocok (`cp x.csv x.csv.stub`, `.csv`
  nyata tidak di-commit).

## 12. Daftar Istilah

| Istilah | Arti |
|---|---|
| PayloadBuilder | Kelas pembangun payload FHIR per resource (`PayloadBuilderXxx`); keluaran berupa array |
| DataType | Nilai FHIR bertipe (Coding, HumanName, Reference, …) |
| SSRequest / SSResponse | Klien HTTP dan pembungkus jawaban SATUSEHAT |
| DLQ | Daftar kiriman yang gagal permanen, menunggu tindakan manusia |
| SmarTerm | Layanan terminologi IvanMD (`terminology.ivanmd.id`) |
| Master data | Dataset nasional (ICD, KFA, KPTL, LOINC, SNOMED, UCUM, CVX, Kode Wilayah) |
| KYC | Alur verifikasi identitas SATUSEHAT (kunci RSA + enkripsi) |

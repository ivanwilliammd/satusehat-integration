# SRD — satusehat-integration (PHP SDK SATUSEHAT FHIR R4)

Status: v1.0 · 2026-10-27 · Pendamping: `docs/PRD.md`
Semua angka di bawah **diukur** dari repo (`ls`, `grep`, `composer test`), bukan dari ingatan.
Bila kode berubah, perbarui dokumen ini di commit yang sama.

## 1. Ruang Lingkup Dokumen

Menjelaskan cara kerja internal pustaka: lapisan, kontrak publik, alur token dan antrean,
pemetaan galat, aturan kompatibilitas, dan cara mengujinya. Tidak menjelaskan cara memakai
(SATUSEHAT sendiri di `README.md`) dan tidak menjelaskan produk yang memakainya.

## 2. Prinsip Arsitektur

1. **Bangun dulu, kirim kemudian.** Pembangun payload (`Builder`, `DataType`) tidak menyentuh
   jaringan; pengiriman hanya di `SSRequest`/`Queue\Worker`. Satu arah ketergantungan.
2. **Nilai bertipe, bukan array bebas di jalur baru.** Tipe, `status`, dan rujukan divalidasi
   saat dibangun — galat ditemukan sebelum dikirim.
3. **Jalur lama tetap hidup, tetapi tidak ditumbuhkan.** `src/FHIR/*` (49 kelas) adalah lapisan
   kompatibilitas pemakai versi 3.x. Baru **3** berkas yang memakai `PayloadBuilder`; sisanya
   masih berdiri sendiri — dicatat sebagai utang teknis T-01, bukan pola yang boleh ditiru.
4. **Kegagalan punya tempat.** Setiap kegagalan kiriman berakhir di status `failed` atau `dlq`
   dengan alasan tersimpan; tidak ada `catch` kosong.
5. **Rahasia hanya dari lingkungan.** Tidak ada kredensial di kode, config contoh, atau log.
6. **Satu perilaku, empat bahasa.** Perubahan bentuk payload harus punya padanan di SDK
   Node/Python/Go pada nomor rilis yang sama.

## 3. Arsitektur Sistem

```
pemakai (aplikasi RS / Laravel / skrip)
   │
   ├── Builder/*        susun payload FHIR         (tanpa jaringan)
   │     └── DataType/* nilai bertipe (32)
   │
   ├── SSRequest        kirim sinkron              (guzzle, retry, refresh token)
   │     └── SSResponse jawaban terbungkus
   │     └── SSValidationError 558 aturan + 14 tipe jawaban
   │
   ├── Queue/*          kirim asinkron             (SQLite: pending→processing→success|failed|dlq)
   │     ├── SqliteQueue, Worker, RateLimiter, ErrorClassifier, QueueMonitor, QueueResult
   │     └── Console/Commands: EnqueueSatusehat, ProcessSatusehatQueue, QueueStatusSatusehat
   │
   ├── Publisher/*      kirim ke broker/klien      (Guzzle, PSR-18, Laravel HTTP)
   │
   └── Domain layanan luar
         ├── OAuth2Client   token SATUSEHAT (client_credentials)
         ├── TerminologyClient  SmarTerm (PAT atau OIDC client_credentials)
         ├── Tenancy/TenantCredentialResolver  kredensial per tenant
         └── KYC            kunci RSA + enkripsi (verifikasi identitas)
```

Ketergantungan hanya ke bawah. `Builder` tidak boleh mengimpor `SSRequest`; `DataType` tidak
boleh mengimpor apa pun dari `Queue`.

## 4. Inventaris Kelas (terukur 2026-10-27)

| Direktori | Berkas | Peran |
|---|---|---|
| `src/Builder/` | 142 | 141 `PayloadBuilderXxx` per resource + basis abstrak `Builder` |
| `src/DataType/` | 32 | Coding, CodeableConcept, HumanName, Reference, Quantity, … |
| `src/FHIR/` | 49 | resource gaya lama (kompatibilitas 3.x); **3** di antaranya sudah memakai `PayloadBuilder` |
| `src/Parser/` | 18 | pembaca jawaban SATUSEHAT → objek (Patient, Encounter, Observation, …) |
| `src/Terminology/` | 22 | pendukung klien terminologi |
| `src/Queue/` | 8 | antrean persisten |
| `src/SSResponse/` | 3 | `SSResponse`, `SSValidationError`, pendukung |
| `src/Models/` | 3 | `SatusehatLog`, `SatusehatQueue`, `SatusehatToken` |
| `src/Console/Commands/` | 3 | perintah artisan antrean |
| `src/Tenancy/` | 1 | `TenantCredentialResolver` |
| akar `src/` | — | `OAuth2Client`, `TerminologyClient`, `TerminologyQueryBuilder`, `KYC`, service provider |

## 5. Kontrak Publik

### 5.1 Builder

```php
$b = new PayloadBuilderPatient();                  // 141 kelas PayloadBuilder*
$b->setId('100000000001')
  ->addName(new HumanName(family: 'Harsono', given: ['Ivan']))
  ->setGender('male')
  ->setBirthDate('1990-01-01')
  ->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Patient');
$payload = $b->build();   // array FHIR, null & array kosong dibuang
$payload = $b->json();    // sama; subkelas boleh menambah validasi lalu melempar RuntimeException
```

Basis abstrak `Builder` menyediakan `build()`/`json()`; **tidak ada** `::make()` — kelas dibuat
dengan `new`. Nama kelas selalu berawalan `PayloadBuilder`.

Aturan yang mengikat:

- **Metode tidak seragam antar resource.** Tidak semua resource punya penyetel untuk setiap
  elemen (mis. `PayloadBuilderPatient` belum punya `setManagingOrganization`); periksa kelasnya
  sebelum menulis pemanggilan.
- **Objek bertipe ATAU string** diterima pada metode yang menerima rujukan/CodeableConcept.
  String yang sudah memuat `/`, atau berawalan `urn:`, `http://`, `https://`, **tidak** diberi
  awalan tipe lagi.
- **`status`/`intent` divalidasi**; nilai di luar daftar melempar `InvalidArgumentException`.
- **`build()` mengembalikan array**, bukan objek; `DataType::toArray()` rekursif dan membuang null.

### 5.2 SSRequest

```php
$ss = new SSRequest($oauth2, token: null, baseUrl: null, config: [
    'timeout' => 30, 'maxRetries' => 5, 'headers' => [...],
]);
$ss->get($url, $params); $ss->post($url, $body); $ss->put($url, $body);
$ss->patch($url, $patch); $ss->patchFhirPath($url, $fhirPathPayload);
$ss->delete($url, $body); $ss->postBundle($url, $bundle);
```

Perilaku yang sudah terpasang:

| Keadaan | Tindakan |
|---|---|
| `401` | minta token baru lewat `OAuth2Client`, ulangi **sekali** |
| `429` | tunggu sesuai `Retry-After`, lalu ulangi |
| `5xx` / galat jaringan | percobaan ulang eksponensial `2^n`, batas `maxRetries`, jeda maksimum 60 s |
| `4xx` lain | tidak diulang; galat diteruskan sebagai `SSResponse` |

### 5.3 Antrean

Status: `pending → processing → success | failed → dlq`. `DEFAULT_MAX_ATTEMPTS = 5`.
`QueueInterface` menyediakan `enqueue`, `enqueueBundle`, `dequeue`, `markSuccess`, `markFailed`,
`markDlq`, `reset`, `get`, `pendingCount`, `dlqCount`, `stats`, `pendingJobs`, `dlqJobs`,
`resetAll`, `purgeSuccess($days)`.

`ErrorClassifier` memetakan jawaban menjadi kategori yang menentukan boleh-ulang:

| Kategori | Boleh ulang | Status akhir |
|---|---|---|
| `success` | — | `success` |
| `invalid_request`, `validation_error`, `not_found` | tidak | `dlq` |
| `unauthorized` | ya (token baru) | tetap `pending` |
| `forbidden` | tidak | `dlq` |
| `conflict` (409) | **tidak** — saat ini langsung `dlq` | `dlq` |
| `rate_limited` | ya (jeda) | `pending` |
| `server_error`, `network_error` | ya (`2^n`) | `pending`, `dlq` setelah batas |
| `unexpected` | tidak | `dlq` |

`RateLimiter` membatasi laju kiriman (bawaan 300 permintaan/menit) — satu-satunya tempat
penundaan terpusat, supaya worker paralel tidak menjumlah melampaui kuota SATUSEHAT.

### 5.4 TerminologyClient (SmarTerm)

```php
$tc = new TerminologyClient(baseUrl: null, token: null, config: []);  // env dipakai bila null
$tc->search($q, category: 'all', limit: 20);
$tc->searchSystem('icd10', $q);           // per sistem kode
$tc->kptlCombine($baseCode, $modifiers);  // KPTL berlapis → kode gabungan
$tc->map($source, $target, $code);
$tc->validate($system, $code);
$tc->autoMap($sourceSystem, $sourceCode);
$tc->resolveCondition($code, $systemHint, $category);
$tc->resolveChiefComplaint($code); $tc->resolveObservation($code);
$tc->resolveMedication($code);     $tc->resolveServiceRequest($code, $category);
$tc->valueSets(); $tc->valueSet($bundleId);
$tc->query($text)->…                // TerminologyQueryBuilder (fluent)
```

Auth: **Sanctum PAT** (`TERMINOLOGY_API_TOKEN`) **atau** **OIDC client_credentials**
(`TERMINOLOGY_CLIENT_ID`/`_SECRET`/`_TOKEN_URL`) dengan refresh otomatis. Dua-duanya boleh ada;
PAT dipakai lebih dulu. Terminology **tidak boleh** menjadi titik mati: kegagalan layanan
diteruskan sebagai galat yang jelas, bukan payload kosong yang menyamar sebagai "tidak ada data".

### 5.5 Kredensial per tenant

`TenantCredentialResolver::resolve(?string $teamKey)` memilih sumber kredensial berurutan:
override nonaktif → **env**; override aktif + driver non-database → **array**; override aktif +
driver `database` → **baris tenant**, dengan cadangan array. Kunci cache memuat `teamKey` supaya
satu proses dapat melayani beberapa tenant tanpa saling menimpa.

## 6. Alur

**Kirim sinkron**: susun payload → `SSRequest::post` → `401`? token baru + ulangi → `429`? tunggu
→ `2xx`? bungkus jadi `SSResponse` dengan id resource → selain itu petakan galat lewat
`SSValidationError`.

**Kirim asinkron**: `EnqueueSatusehat` menulis baris `pending` → `ProcessSatusehatQueue` menjalankan
`Worker::process(limit)` → `RateLimiter::wait()` → kirim → `markSuccess` / `markFailed` →
percobaan habis → `markDlq`. `QueueStatusSatusehat` mencetak hitungan per status dan isi DLQ.

**Master data**: berkas `database/seeders/csv/*.csv.stub` (18 berkas) disalin menjadi `.csv` saat
publish, lalu seeder membacanya (pemisah `;`, ada baris header). Migrasi `.stub` disediakan untuk
aplikasi Laravel yang ingin menyimpan dataset secara lokal.

## 7. Aturan Kompatibilitas

- **Jangan hapus atau ubah nama metode publik** tanpa catatan migrasi di `CHANGELOG.md`.
- **Logika baru hanya di `PayloadBuilder`**; menambah perilaku di `src/FHIR/*` dilarang. Berkas
  `FHIR` yang belum memakai builder (46 dari 49) dibiarkan apa adanya sampai dimigrasikan (T-01).
- **`DataType::toArray()` tidak boleh mengembalikan objek**; uji lama mengandalkan array penuh.
- **Dukungan PHP 7.4 wajib dijaga** pada `src/` (tidak ada properti bertipe read-only, tidak ada
  `enum`, tidak ada `match`), walaupun CI utama memakai 8.0/8.3.
- **`illuminate/*` `^8`–`^13`**: jangan memakai API yang hanya ada di satu major.

## 8. Kinerja & Batas

- `SSRequest` sinkron; untuk volume besar pakai antrean.
- Antrean berbasis SQLite: cocok untuk kiriman asinkron satu fasilitas, bukan pengganti broker.
- `RateLimiter` (300 rpm bawaan) adalah pengaman kuota, bukan penjadwal.
- Payload besar (`Bundle`) dikirim lewat `postBundle`, bukan dipecah manual oleh pemanggil.

## 9. Pengujian

- 45 kelas uji: `tests/FHIR` 43, `tests/Unit` 1, `ArchTest` 1. Jalankan `composer test`.
- Terukur 2026-10-30 (`vendor/bin/phpunit`): **879 uji, 1479 asersi, 0 gagal**, 0,43 detik,
  **0 baris `Deprecated`** di keluaran. Deprecation dihitung sebagai gerbang, bukan kebisingan:
  ia satu-satunya tanda bahwa pustaka akan pecah di PHP berikutnya sementara semua uji tetap hijau
  (terukur: 34 baris → 0 setelah 5 parameter implicit-nullable di `src/FHIR/DiagnosticReport.php`
  dan 4 `setAccessible()` di uji yang kini dijaga `PHP_VERSION_ID < 80100`).
- Uji tidak menyentuh jaringan: klien di-stub, token di-inject.
- Uji wajib untuk setiap kontrak di §5: bentuk payload, penolakan `status` tidak sah, awalan
  rujukan, pemetaan galat, transisi status antrean.
- **Matriks CI**: PHP 8.0 + 8.3, Linux + Windows. Uji yang hanya lulus di satu versi adalah cacat.
- Uji paritas lintas bahasa (PHP vs Node/Python/Go) **belum ada** — lihat PRD FR-14.

## 10. Keputusan Arsitektur (ADR)

| # | Keputusan | Alasan | Konsekuensi |
|---|---|---|---|
| ADR-01 | Pembangun payload terpisah dari klien HTTP | Dapat diuji tanpa jaringan; satu sumber bentuk FHIR | Dua kelas untuk satu resource, disengaja |
| ADR-07 | Nama kelas `PayloadBuilder*` (bukan `Builder*`) | Konsisten dengan `DataType` dan nama paket lama | Basis abstrak tetap `Builder`; pencarian nama harus menyertakan awalan |
| ADR-02 | `build()` mengembalikan array, bukan objek | Pemanggil bebas mengubah sebelum kirim; JSON langsung | Kehilangan type-safety setelah `build()` |
| ADR-03 | Antrean SQLite, bukan Redis | Satu berkas, tanpa layanan tambahan di fasilitas kecil | Tidak cocok untuk konkurensi tinggi |
| ADR-04 | Kode galat dipetakan ke kategori, bukan dicocokkan per pesan | Pesan SATUSEHAT berubah; kategori stabil | Perlu menambah kategori saat muncul kelas galat baru |
| ADR-05 | Master data disimpan sebagai `.csv.stub` yang dilacak | Berkas `.csv` hasil publish tidak di-commit | Alur ubah: segarkan `.csv` → `cp` ke `.stub` → hapus `.csv` → commit `.stub` |
| ADR-06 | Kredensial per tenant lewat resolver, bukan properti statis | Satu proses dapat melayani banyak tenant | Setiap jalur harus memanggil resolver, tidak menyimpan hasil di `static` |

## 11. Anti-Pola (jangan diulang)

1. Menyusun payload dengan array tangan di aplikasi pemanggil — lewati validasi tipe.
2. Menaruh logika HTTP di dalam `Builder` — menguji payload jadi butuh jaringan.
3. Menyalin isi `src/FHIR/*` ke `Builder` (atau sebaliknya) — dua sumber bentuk yang akan menyimpang.
4. `catch (\Throwable) {}` pada jalur kirim — kiriman hilang tanpa jejak.
5. Menyimpan token di properti `static` atau di berkas yang di-commit.
6. Memakai `RateLimiter` sebagai penjadwal (menahan proses utama tanpa batas).
7. Mengubah `.csv` hasil publish lalu meng-commit-nya, bukan `.stub`-nya.
8. Menambah `meta.profile` atau klaim FHIR-native lain tanpa memastikan resource-nya memang
   dibangun di sini — pustaka ini klien, bukan server FHIR.
9. Menulis kunci berjenjang sebagai literal (`$this->data['meta/profile']`) alih-alih lewat
   `push('meta/profile', …)`. Payload-nya tetap JSON yang sah sehingga tidak ada galat: yang hilang
   hanya medannya. Penjaganya `tests/FHIR/NestedPathSweepTest.php` (memindai TITIK PEMANGGILAN).
10. Menyusun jalur berjenjang dengan indeks yang tidak menunjuk elemen mana pun
   (`push("addItem/{$idx}/adjudication", …)` saat daftar `addItem` masih kosong → indeks `-1`).
   PHP lalu membuat array ber-kunci rapat dari angka itu dan `json_encode` mengubahnya menjadi
   **objek** (`{"-1": …}`, `{"0": …, "3": …}`) — sedangkan FHIR menuntut **list** di posisi
   tersebut. Tidak ada galat, payload tetap JSON yang sah, server menolak sebagai tipe salah
   tanpa menyebut medan penyebabnya. Aturannya: **segmen numerik hanya boleh menunjuk elemen yang
   sudah dibuat**; `Builder::assertIndexedSegmentExists()` melempar `InvalidArgumentException`
   yang menyebut jalurnya. Penjaganya `tests/FHIR/NestedPathVariablePathTest.php` (4 jalur
   ber-variabel + 4 kontrol negatif).

## 11b. Utang Teknis Terukur

| # | Utang | Bukti |
|---|---|---|
| T-01 | 46 dari 49 kelas `src/FHIR/*` belum memakai `PayloadBuilder` | `grep -l Builder src/FHIR/*.php` → 3 |
| T-02 | README menyebut "51 PayloadBuilder", kenyataannya 141 | `ls src/Builder/PayloadBuilder*.php` |
| T-03 | Uji paritas lintas bahasa (PRD FR-14) belum ada | tidak ada berkas uji paritas di `tests/` |
| T-04 | PHP 7.4 di `require` tetapi tidak diuji CI | `.github/workflows` hanya 8.0 + 8.3 |
| T-06 | **SELESAI 2026-10-30** — `setSeriesBodySite()` kini mengirim **CodeableConcept** (`{coding:[{…}]}`) di `src/Builder/PayloadBuilderImagingStudy.php` **dan** `src/FHIR/ImagingStudy.php`; `display` kosong tidak lagi dikirim sebagai string kosong. Uji MERAH dulu 3/3 di `tests/FHIR/ImagingStudyTest.php` (4 uji/9 asersi). Bentuk lama **tidak mungkin dipakai pemanggil yang berhasil**: `series.bodySite` bertipe Coding ditolak validator FHIR, jadi tidak ada pemanggil sehat yang membaca `.bodySite.code` | `vendor/bin/phpunit tests/FHIR/ImagingStudyTest.php` · sapuan kelas yang sama: 10 kandidat "emit `system` tanpa `coding`" diperiksa, 1 cacat nyata (T-06), sisanya benar menurut spesifikasi (`ImagingStudy.modality`/`series.modality` = Coding, `Location.operationalStatus` = Coding, `MedicationDispense.category` sudah `category/coding`) |
| T-05 | **SELESAI 2026-10-30** — 4 jalur berjenjang ber-variabel kini ber-uji dinamis di `tests/FHIR/NestedPathVariablePathTest.php` (8 uji/26 asersi), dan indeks yang tidak menunjuk elemen ditolak | `vendor/bin/phpunit tests/FHIR/NestedPathVariablePathTest.php` |

## 12. Ketertelusuran PRD → SRD

| PRD | Bagian SRD |
|---|---|
| FR-01, FR-02, FR-03, FR-04 | §5.1, §7 |
| FR-05, FR-06, FR-07 | §5.2, §5.3 (tabel pemetaan galat) |
| FR-08 | §5.3, §6 |
| FR-09 | §6 (master data), ADR-05 |
| FR-10 | §5.4 |
| FR-11 | §5.5, ADR-06 |
| FR-12 | §3, §4 |
| FR-13, FR-14 | §9 |
| NFR kompatibilitas | §7 |
| NFR kegagalan terlihat | §5.3, §11 butir 4 |

## 13. Keputusan yang Harus Diambil Pemilik Produk

1. **Dukungan PHP 7.4**: masih di `composer.json` tetapi CI hanya menguji 8.0/8.3. Pertahankan
   (dan tambah job 7.4) atau cabut di v5.
2. **Uji paritas lintas bahasa**: apakah paritas dijaga manual per rilis (sekarang) atau dibangun
   uji kontrak bersama yang membaca berkas contoh yang sama.
3. **Antrean**: tetap SQLite, atau sediakan antarmuka kedua (Redis/DB Laravel) untuk fasilitas besar.
4. **`src/FHIR/*`**: berapa lama lapisan kompatibilitas dipertahankan sebelum dihapus di major
   berikutnya.

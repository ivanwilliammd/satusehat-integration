# PRD — Ekosistem SDK SATUSEHAT (PHP · Node/TS · Python · Go)

Status: v1.1 · 2026-11-09 · Pemilik produk: Ivan William Harsono
Dokumen ini **satu-satunya** PRD lintas bahasa. SRD per bahasa terpisah:
`docs/SRD.md` (PHP, di repo ini), `docs/SRD.md` di `satusehat-integration-node`,
`-python`, `-go`.

## 1. Tujuan Bersama

Empat SDK, satu perilaku: memasang paket di bahasa apa pun harus memberi kemampuan yang sama
untuk membangun payload FHIR R4 SATUSEHAT, mengirimnya dengan token yang selalu segar, dan
membaca galat validasi dalam bahasa manusia. Nomor versi bergerak bersama; perbedaan yang
tersisa harus **tertulis** di tabel §4, bukan ditemukan oleh pemakai.

## 2. Keadaan Terukur (2026-11-09)

| | PHP | Node/TS | Python | Go |
|---|---|---|---|---|
| Paket | `ivanwilliammd/satusehat-integration` | `@ivanwilliammd/satusehat-integration` | `satusehat-integration` (PyPI) | `github.com/ivanwilliammd/satusehat-integration-go/v4` |
| Versi terakhir di repo | 4.16.0 | 4.16.0 | 4.16.0 | v4.16.0 |
| Registri | Packagist | npm | PyPI | proxy.golang.org |
| Pembangun payload | 141 `PayloadBuilder*` | 141 `*Builder` | 141 `*Builder` | 141 `*Builder` |
| DataType | 32 kelas | 31 kelas | 33 kelas | 33 struct |
| Pengiriman HTTP & OAuth2 | `OAuth2Client` + `SSRequest` | `OAuth2Client` + `SSRequest` | `OAuth2Client` + `SSRequest` | `OAuth2Client` + `SSRequest` |
| Validasi Respons (558 aturan) | `SSResponse`, `BundleResponse`, `SSValidationError` | `SSResponse`, `BundleResponse`, `SSValidationError` | `SSResponse`, `BundleResponse`, `SSValidationError` | `SSResponse`, `BundleResponse`, `SSValidationError` |
| Parser FHIR | 18 kelas `Parser` | 18 kelas `Parser` | 18 kelas `Parser` | 18 struct `Parser` |
| Antrean persisten | 8 modul (`SqliteQueue`, `Worker`, `QueueMonitor`, `RateLimiter`, `ErrorClassifier`, …) | 8 modul (`SqliteQueue`, `Worker`, `QueueMonitor`, `RateLimiter`, `ErrorClassifier`, …) | 8 modul (`SqliteQueue`, `Worker`, `QueueMonitor`, `RateLimiter`, `ErrorClassifier`, …) | 8 modul (`SqliteQueue`, `Worker`, `QueueMonitor`, `RateLimiter`, `ErrorClassifier`, …) |
| Klien terminologi | `TerminologyClient` + 22 modul | `TerminologyClient` + 20 modul | `TerminologyClient` + 27 modul | `TerminologyClient` + 17 modul |
| Berkas uji | 53 kelas (**898 uji / 1539 asersi**) | 8 berkas (**412 uji**) | 9 berkas (**92 uji**) | 7 berkas (**semua paket lulus**) |
| Pelacak paritas | `SYNC_MANIFEST.json` | `SYNC_MANIFEST.json` | `SYNC_MANIFEST.json` | `v4/SYNC_MANIFEST.json` |

## 3. Kebutuhan Fungsional Bersama

| ID | Kebutuhan | PHP | Node | Python | Go |
|---|---|---|---|---|---|
| SDK-01 | Pembangun payload per resource FHIR (`build()`/`json()`) | ya (141) | ya (141) | ya (141) | ya (141) |
| SDK-02 | `DataType` bertipe (Coding, HumanName, Reference, Quantity, …) | ya | ya | ya | ya |
| SDK-03 | Permintaan HTTP: GET/POST/PUT/PATCH/DELETE + Bundle (`SSRequest`) | ya | ya | ya | ya |
| SDK-04 | Token OAuth2 client_credentials, diperbarui otomatis pada 401 (`OAuth2Client`) | ya | ya | ya | ya |
| SDK-05 | Pembungkus jawaban + pemetaan 558 kode galat validasi (`SSResponse`, `BundleResponse`, `SSValidationError`) | ya | ya | ya | ya |
| SDK-06 | Antrean persisten (pending/failed/dlq) + worker + pembatas laju (`SqliteQueue`, `Worker`) | ya (8 modul) | ya (8 modul) | ya (8 modul) | ya (8 modul) |
| SDK-07 | Klien terminologi SmarTerm (PAT / OIDC client_credentials) | ya | ya | ya | ya |
| SDK-08 | Master data CSV + seeder Laravel | **ya (satu-satunya)** | tidak | tidak | tidak |
| SDK-09 | Parser FHIR (18 resource parser) | ya (18) | ya (18) | ya (18) | ya (18) |
| SDK-10 | Pelacak paritas yang benar-benar terbarui (`SYNC_MANIFEST.json`) | ya | ya | ya | ya |

## 4. Perbedaan yang Disengaja (bukan cacat)

| Hal | PHP | Node/TS | Python | Go |
|---|---|---|---|---|
| Gaya pemanggilan | `new PayloadBuilderXxx()` lalu metode rantai | kelas `XxxBuilder` + fungsi pabrik | kelas `XxxBuilder` (snake_case & camelCase) | struct + metode rantai |
| Penamaan berkas | satu berkas per kelas | `src/builder/*.ts` (kebab-case) | `src/builder/*.py` (snake_case) | paket `v4/` per modul |
| Antrean | SQLite PDO (`SqliteQueue` + `Worker`) | SQLite `better-sqlite3` (`SqliteQueue` + `Worker`) | SQLite `sqlite3` (`SqliteQueue` + `Worker`) | Durable JSON/In-Memory (`SqliteQueue` + `Worker`, zero CGo) |
| Master data | CSV + seeder Laravel (pilar data) | via `TerminologyClient` | via `TerminologyClient` | via `TerminologyClient` |
| Jalur lawas | `src/FHIR/*` untuk pemakai 3.x | tidak ada | tidak ada | tidak ada |
| Versi runtime minimum | PHP 8.0 | Node 20 | Python 3.10 | Go 1.21 |

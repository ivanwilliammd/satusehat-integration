# PRD — Ekosistem SDK SATUSEHAT (PHP · Node/TS · Python · Go)

Status: v1.0 · 2026-10-27 · Pemilik produk: Ivan William Harsono
Dokumen ini **satu-satunya** PRD lintas bahasa. SRD per bahasa terpisah:
`docs/SRD.md` (PHP, di repo ini), `docs/SRD.md` di `satusehat-integration-node`,
`-python`, `-go`.

## 1. Tujuan Bersama

Empat SDK, satu perilaku: memasang paket di bahasa apa pun harus memberi kemampuan yang sama
untuk membangun payload FHIR R4 SATUSEHAT, mengirimnya dengan token yang selalu segar, dan
membaca galat validasi dalam bahasa manusia. Nomor versi bergerak bersama; perbedaan yang
tersisa harus **tertulis** di tabel §4, bukan ditemukan oleh pemakai.

## 2. Keadaan Terukur (2026-10-27)

| | PHP | Node/TS | Python | Go |
|---|---|---|---|---|
| Paket | `ivanwilliammd/satusehat-integration` | `@ivanwilliammd/satusehat-integration` | `satusehat-integration` (PyPI) | `github.com/ivanwilliammd/satusehat-integration-go/v4` |
| Versi terakhir di repo | 4.13.0 (CHANGELOG) | 4.14.2 | 4.14.1 | v4 (tag terakhir terpisah) |
| Registri | Packagist | npm | PyPI | proxy.golang.org |
| Berkas sumber | 142 Builder, 32 DataType, 49 FHIR | 172 `.ts`; `src/builder` 143 berkas | 179 `.py`; `src/builder` 1 berkas per resource | 192 `.go` |
| Antrean persisten | 8 kelas (SQLite) | `queue/`: error_classifier, monitor, rate_limiter | `queue/` | ada |
| Klien terminologi | `TerminologyClient` + 22 kelas | `terminology/`: 19 modul + `TerminologyClient.ts` | ada | `TerminologyClient` + query builder |
| Berkas uji | 45 kelas; **860 uji / 1380 asersi** | 3 berkas `*.test.ts` | **tidak ada direktori uji** (pytest ada di dev-deps) | 3 berkas `*_test.go` |
| Pelacak paritas | — | `SYNC_MANIFEST.json` | `SYNC_MANIFEST.json` | belum ada |

**Temuan yang harus ditindaklanjuti (bukan sekadar catatan):**

1. `SYNC_MANIFEST.json` di Node dan Python masih menyatakan **semua komponen `pending`** dengan
   `php_last_commit: 9b7c97a`, padahal port sudah jauh berjalan (Node 143 berkas builder,
   Go sudah mem-port `TerminologyClient`). Artinya alat paritas itu **tidak lagi mengukur apa
   pun** — status "pending" pada manifest bukan bukti belum diport.
2. Total `PayloadBuilder` di manifest (51) sudah basi; PHP kini 141. Manifest yang menyebut angka
   lama akan terus melaporkan "belum paripurna" walau seluruh port selesai.
3. Cakupan uji pincang: PHP 860 uji, Node 3, Go 3, Python 0. Klaim "paritas perilaku" tidak
   dapat dipertahankan tanpa uji di tiga bahasa lain.

## 3. Kebutuhan Fungsional Bersama

| ID | Kebutuhan | PHP | Node | Python | Go |
|---|---|---|---|---|---|
| SDK-01 | Pembangun payload per resource FHIR (`build()`/`json()`) | ya | ya | ya | ya |
| SDK-02 | `DataType` bertipe (Coding, HumanName, Reference, Quantity, …) | ya | ya | ya | ya |
| SDK-03 | Permintaan HTTP: GET/POST/PUT/PATCH/DELETE + Bundle | ya | ya | ya | ya |
| SDK-04 | Token OAuth2 client_credentials, diperbarui otomatis pada 401 | ya | ya | ya | ya |
| SDK-05 | Pembungkus jawaban + pemetaan kode galat validasi | ya | ya | ya | ya |
| SDK-06 | Antrean persisten (pending/failed/dlq) + pembatas laju | ya | sebagian | sebagian | sebagian |
| SDK-07 | Klien terminologi SmarTerm (PAT / OIDC client_credentials) | ya | ya | ya | ya |
| SDK-08 | Master data CSV + seeder | **ya (satu-satunya)** | tidak | tidak | tidak |
| SDK-09 | Uji otomatis memadai untuk setiap kontrak | ya (860) | **tipis (3)** | **nol** | **tipis (3)** |
| SDK-10 | Pelacak paritas yang benar-benar terbarui | — | manifest basi | manifest basi | belum ada |

## 4. Perbedaan yang Disengaja (bukan cacat)

| Hal | PHP | Node/TS | Python | Go |
|---|---|---|---|---|
| Gaya pemanggilan | `new PayloadBuilderXxx()` lalu metode rantai | kelas `XxxBuilder` + fungsi pabrik | kelas `XxxBuilder` (snake_case) | struct + metode rantai |
| Penamaan berkas | satu berkas per kelas | `src/builder/*.ts` (kebab-case) | `src/builder/*.py` (snake_case) | paket `v4/` per resource |
| Antrean | SQLite penuh (worker, DLQ, monitor) | SQLite via `better-sqlite3`, tanpa worker | belum setara | belum setara |
| Master data | CSV + seeder (pilar data) | — | — | — |
| Jalur lawas | `src/FHIR/*` untuk pemakai 3.x | tidak ada | tidak ada | tidak ada |
| PHP 7.4 / Node 18 / Python 3.9 / Go 1.21 | versi minimum masing-masing | | | |

**Aturan**: perbedaan pada tabel §4 boleh ada; perbedaan pada §3 adalah cacat yang harus
ditutup atau dipindahkan ke §4 dengan alasan tertulis.

## 5. Kebutuhan Non-Fungsional Bersama

- **API publik stabil**: perubahan yang memutus pemanggil wajib naik major dan dicatat di
  `CHANGELOG.md` masing-masing repo.
- **Tanpa rahasia di repo**: kredensial hanya dari environment; `.env.example` berisi placeholder.
- **Kegagalan terlihat**: tidak ada penelanan galat tanpa catatan.
- **Kiriman idempoten**: id yang sudah ada dipakai untuk PUT, bukan POST baru.
- **Rilis serentak**: satu kemampuan baru mendarat di keempat repo dalam satu gelombang rilis;
  bahasa yang tertinggal dicatat di `SYNC_MANIFEST.json` dengan commit acuan yang **terbaru**.

## 6. Fase

1. **Fase A (selesai)**: PHP sebagai acuan; DataType + SSRequest + builder inti.
2. **Fase B (berjalan)**: port builder & terminologi ke Node/Python/Go; manifest paritas
   diperbarui tiap gelombang.
3. **Fase C (belum)**: uji kontrak bersama — berkas contoh payload yang sama dibaca keempat SDK
   dan dibandingkan hasilnya per rilis; manifest diisi otomatis dari hasil uji itu, bukan tangan.

## 7. Kriteria Penerimaan

- Setiap repo: `composer test` / `npm test` / `pytest` / `go test ./...` hijau pada versi minimum
  dan versi terbaru yang didukung.
- `SYNC_MANIFEST.json` menyebut commit PHP yang benar-benar ada (`git cat-file -e <sha>`), bukan
  hash lama.
- Tidak ada resource yang "ada di PHP, tidak di bahasa lain" tanpa entri di §4.

## 8. Keputusan yang Harus Diambil Pemilik Produk

1. **Uji kontrak bersama** (Fase C): dibangun sekarang, atau paritas dijaga manual per rilis?
2. **Master data**: tetap hanya di PHP (pemakai lain memanggil API terminologi), atau diterbitkan
   juga sebagai berkas data yang dapat diunduh SDK lain?
3. **Antrean**: disamakan di empat bahasa, atau cukup PHP (yang punya beban kiriman terbanyak)?
4. **Nasib `SYNC_MANIFEST.json`**: diperbarui otomatis oleh skrip, atau diganti tabel di dokumen
   ini (satu sumber, tidak bisa basi diam-diam)?

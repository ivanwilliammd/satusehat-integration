# Paritas facade lama ↔ PayloadBuilder native

Dokumen ini menjawab satu pertanyaan: **kalau sebuah resource dibangun lewat kelas facade lama
(`Satusehat\Integration\FHIR\*`) dan lewat `PayloadBuilder*` native, apakah payload FHIR-nya sama?**
Jawabannya harus berupa angka yang bisa dijalankan ulang, bukan ingatan.

Uji: `tests/FHIR/FacadeParityTest.php` — jalankan dengan

```bash
vendor/bin/phpunit tests/FHIR/FacadeParityTest.php
```

## Bentuk dua jalur

| | Facade lama | Builder native |
|---|---|---|
| Kelas | `Satusehat\Integration\FHIR\Patient` | `Satusehat\Integration\Builder\PayloadBuilderPatient` |
| Masukan | argumen skalar (`setCode($code, $display, $text)`) | objek `DataType` (`setCode(CodeableConcept)`) |
| Keluaran | `json()` → **string JSON** (pretty print) | `json()` → **array** |
| Validasi wajib | `json()` melempar `FHIRMissingProperty` | tidak memvalidasi; pemanggil yang memeriksa |
| Kirim ke API | `post()` / `put()` di kelas facade | `SSRequest::post()` di pemanggil |

Karena tipe keluarannya berbeda, uji paritas **men-decode** keluaran facade lalu membandingkan
array-nya dengan keluaran builder (`json_decode($facade->json(), true)` vs `$builder->json()`),
setelah keduanya dikanonikalisasi (`ksort` rekursif) supaya urutan kunci tidak ikut diuji.

## Status terukur (2026-10-28)

- Kelas facade di `src/FHIR/`: **49**
- Facade yang punya shim `public static function build(): PayloadBuilder…`: **3**
- Resource dengan uji paritas isi payload (facade vs builder, masukan identik): **3**
- Kelas facade yang **belum** punya shim: **46** (semuanya sudah punya `PayloadBuilder` pasangannya,
  jadi pekerjaannya aditif: satu metode `build()` + satu kasus uji paritas per resource)

Daftar yang sudah ber-shim dan beruji — **daftar ini dijaga uji**
(`test_shim_list_matches_documented_parity_matrix`): menambah shim tanpa memperbarui berkas ini
membuat suite gagal.

- shim: `AllergyIntolerance`
- shim: `Device`
- shim: `DiagnosticReport`

## Jebakan yang sudah terbukti

1. **`Annotation::__construct($author, ?string $text, ?string $time)` — argumen pertama AUTHOR, bukan teks.**
   Menulis `new Annotation('catatan')` menghasilkan `note: [{"author":"catatan"}]`, dan FHIR R4
   mewajibkan `Annotation.text`; `author` hanya pengisi opsional. Payload tetap "terlihat benar"
   sampai pemeriksa FHIR menolaknya. Pakai `new Annotation(null, 'catatan')` atau serahkan string
   langsung: `addNote('catatan')`.
2. **`addNote()` dulu tidak seragam antar builder** — 5 builder menerima `string`, 8 menerima
   `Annotation`. Sejak 2026-10-28 keduanya menerima `Annotation|string` (perluasan tipe, bukan
   perubahan perilaku), sehingga kode lama tidak perlu diubah dan bentuk teks biasa tidak lagi
   berakhir di `author`.
3. **Urutan kunci JSON tidak boleh diuji.** `json_encode` mempertahankan urutan sisip; facade dan
   builder menyisipkan pada urutan berbeda untuk resource yang sama. Bandingkan array hasil
   `ksort` rekursif, jangan string JSON-nya.
4. **`meta.profile` bukan paritas.** Sebagian facade menyisipkan profil SATUSEHAT di `meta`;
   builder tidak menambahkannya otomatis. Kalau kelak ada facade ber-`meta`, selisih itu harus
   dinyatakan sebagai perbedaan yang DISENGAJA di tabel dokumen ini — jangan dinormalkan diam-diam
   di dalam uji.

## Cara menambah resource berikutnya (46 sisa)

1. Tambahkan `public static function build(): PayloadBuilderX` pada `src/FHIR/X.php`
   (pola: `Device::build()`).
2. Tambahkan satu kasus uji di `tests/FHIR/FacadeParityTest.php`: jalankan deret setter yang SAMA
   pada facade dan pada builder, lalu `assertParitySame()`.
3. Tambahkan baris `- shim: `X`` di dokumen ini; tanpa itu `test_shim_list_matches_documented_parity_matrix` gagal.
4. Buktikan merah dulu bila ada keraguan: hapus sementara pemanggilan setter di sisi builder, uji
   harus gagal pada path yang tepat (`/note/0/text: facade=… builder=<tidak ada>`).

## Cacat yang ditemukan saat uji paritas ini dibuat (2026-10-28)

1. **`Satusehat\Integration\FHIR\Element` tidak pernah ada.** `Immunization`,
   `MedicationAdministration`, `MedicationDispense`, dan `Specimen` menulis `extends Element`,
   padahal kelas itu tidak ada di repo **dan tidak pernah ada di riwayat git**
   (`git log --all -- src/FHIR/Element.php` kosong). Akibatnya keempat facade itu mati saat
   dipakai: `Class "Satusehat\Integration\FHIR\Element" not found`. Facade-nya tidak pernah
   diuji langsung — uji yang ada menguji `PayloadBuilder`-nya, bukan kelas facade-nya.
   Ditambahkan `src/FHIR/Element.php` (basis 6 baris: `$data` + `toArray()`), dijaga
   `FacadeParityTest::test_element_facades_can_be_instantiated()`.
2. **Dua gaya `json()` pada facade.** Kelas facade warisan `OAuth2Client` mengembalikan **string**
   (`Device`, `AllergyIntolerance`, `DiagnosticReport`), sedangkan empat kelas turunan `Element`
   mengembalikan **array**. Pemanggil yang memperlakukan facade secara seragam akan pecah.
   Jangan "menormalkan" dengan mengubah tipe pengembalian kelas lama (itu memutus pemanggil yang
   sudah ada): uji yang membandingkan payload sudah men-decode keduanya, dan pemakaian baru
   sebaiknya lewat `PayloadBuilder` yang selalu mengembalikan array.

## Utang teknis terukur (bukan dikerjakan siklus ini)

- **754 parameter bertipe `string|array|int|bool|float … = null`** di 172 berkas `src/` memicu
  `Implicitly marking parameter … as nullable is deprecated` di PHP 8.4. Suite tetap hijau
  (deprecation bukan warning), tetapi galat ini akan menjadi keras di PHP 9. Perbaikannya mekanis
  (`?string $x = null`), hanya saja menyentuh hampir seluruh 49 kelas facade — kerjakan per
  domain, dengan angka uji sebelum/sesudah.
- **46 facade belum ber-shim** `build()` (lihat tabel di atas); semuanya sudah punya
  `PayloadBuilder` pasangannya, jadi tidak ada resource yang tertinggal tanpa jalur native.

## Status uji terakhir

```
vendor/bin/phpunit                    → OK (866 tests, 1418 assertions)
vendor/bin/phpunit tests/FHIR/FacadeParityTest.php → OK (6 tests, 38 assertions)
```

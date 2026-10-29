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

- Kelas facade di `src/FHIR/`: **50**
- Facade yang punya shim `public static function build(): PayloadBuilder…`: **6**
- Resource dengan uji paritas isi payload (facade vs builder, masukan identik): **6**
- Kelas facade yang **belum** punya shim: **46** → **43** setelah `Organization`, `Procedure`, `Patient` (semuanya sudah punya
  `PayloadBuilder` pasangannya, jadi pekerjaannya aditif: satu metode `build()` + satu kasus uji
  paritas per resource)

Daftar yang sudah ber-shim dan beruji — **daftar ini dijaga uji**
(`test_shim_list_matches_documented_parity_matrix`): menambah shim tanpa memperbarui berkas ini
membuat suite gagal.

- shim: `AllergyIntolerance`
- shim: `Device`
- shim: `DiagnosticReport`
- shim: `Organization`
- shim: `Patient`
- shim: `Procedure`

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

## Cacat yang ditemukan uji paritas Organization (2026-10-29)

1. **Tapak `type` 0..* ditulis sebagai satu objek.** `PayloadBuilderOrganization::setType()` dan
   `PayloadBuilderLocation::setType()` memakai `set()` sehingga payload berbunyi `type: {coding: […]}`
   — sedangkan FHIR R4 menetapkan `Organization.type` dan `Location.type` sebagai **0..\***, jadi
   yang sah adalah `type: [{coding: […]}]`. Facade lama sudah benar, sehingga paritas inilah yang
   menyingkap cacatnya: `diff` menunjuk `/type/0` di sisi facade vs `/type/coding` di sisi builder.
   Akibat di lapisan atas: validator yang menuntut bentuk daftar menolak payload yang dibangun jalur
   native, sementara jalur facade lama lolos — dua jalur untuk satu resource memberi hasil berbeda.
   Perbaikan: `push()` (bukan `set()`), dan `test_list_cardinality_fields_are_arrays` menjaga
   bentuknya untuk kedua builder tanpa bergantung pada uji paritas.
   **Jangan tertukar dengan `Device.type`** — di sana tapaknya 0..1, jadi satu objek memang benar;
   itulah sebabnya cacat ini lolos di `Device` dan hanya muncul di `Organization`/`Location`.
2. **`InvalidArgumentException`-nya bukan:** mengubah `setType()` menjadi daftar TIDAK memutus
   pemanggil lama yang membaca `type.coding` — bentuk itu memang tidak sah, jadi tidak ada pemakaian
   benar yang bisa dipertahankan. Uji lama di `OrganizationTest`/`LocationTest` justru mengunci
   bentuk objek; keduanya disesuaikan ke `type[0].coding[0].code`.
3. **`Address` tidak punya `extension`.** Facade lama menyisipkan
   `https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode` (province/city/district/
   village) sendiri, sedangkan `DataType\Address` tidak menyediakan wadahnya — sehingga jalur native
   sama sekali tidak bisa menyatakan kode wilayah yang dituntut SATUSEHAT. Ditambahkan
   `public array $extension = []` (FHIR R4 `Address.extension` 0..*). Bedanya tetap DISENGAJA:
   builder tidak menambahkan extension itu otomatis; pemanggil yang menyusunnya.

## Status uji terakhir (2026-10-29)

```
vendor/bin/phpunit                                  → OK (868 tests, 1424 assertions)
vendor/bin/phpunit tests/FHIR/FacadeParityTest.php  → OK (8 tests, 43 assertions)
```

## Status uji terakhir (sebelum 2026-10-29)

```
vendor/bin/phpunit                    → OK (866 tests, 1418 assertions)
vendor/bin/phpunit tests/FHIR/FacadeParityTest.php → OK (6 tests, 38 assertions)
```

## Sapuan tapak `type` (2026-10-29, lanjutan)

Kelas cacat yang sama ternyata **tidak berhenti di dua resource**. Dua builder lagi menulis `type`
sebagai objek padahal tapaknya **0..\***, dan keduanya sudah diperbaiki dengan `push()` sehingga
pemanggilan berulang MENAMBAH, bukan menimpa:

| Builder | Tapak `type` | Bukti |
|---|---|---|
| `PayloadBuilderHealthcareService::setType()` | 0..* | `HealthcareService.type [0..*] CodeableConcept` |
| `PayloadBuilderInsurancePlan::setType()` | 0..* | `InsurancePlan.type [0..*] CodeableConcept` |

Tapak diverifikasi lewat alat MCP `fhir-r4` (`fhir_elemen`), bukan dari ingatan, karena `InsurancePlan`
punya dua medan bernama `type` dengan tapak BERBEDA: `InsurancePlan.type` = **0..*** sedangkan
`InsurancePlan.plan.type` = **0..1**. Menyamakan keduanya menghasilkan payload yang salah di salah
satu tempat. Penjaganya `tests/FHIR/ResourceTypeCardinalityTest.php` (4 uji) — satu uji bentuk
(`type` bukan objek) dan satu uji perilaku (dua panggilan = dua entri).

**Delapan belas builder lain yang menulis `set('type', …)` TIDAK diubah, dan itu keputusan yang
diukur.** Tapaknya 0..1 atau 1..1, jadi objek tunggal memang benar: `Account`, `AllergyIntolerance`,
`Bundle`, `CatalogEntry`, `Claim`, `ClaimResponse`, `Composition`, `Coverage`, `Device`,
`DeviceMetric`, `DocumentManifest`, `ExplanationOfBenefit`, `Group`, `Invoice`, `MolecularSequence`,
`PaymentReconciliation`, `Specimen`, `SpecimenDefinition`. Menyalin `push()` ke semuanya akan
MENAMBAHKAN cacat baru. Bila kelak ada yang diubah, periksa tapaknya per medan — bukan per nama medan.

### Cacat yang masih terbuka (belum dikerjakan, sengaja)

`PayloadBuilderInsurancePlan::setCoverage()` menulis `coverage` sebagai **CodeableConcept**,
padahal `InsurancePlan.coverage` adalah **0..\* BackboneElement** yang mewajibkan `type` (1..1) dan
`benefit` (1..\*). Artinya payload `InsurancePlan` tetap tidak sah menurut tapak meski `type` sudah
benar. Memperbaikinya bukan penambalan satu baris: bentuk `coverage` berubah dan setiap pemanggil
lama ikut berubah — jadi perlu keputusan pemilik pustaka (perilaku baru vs keluaran ganda).

## Cacat yang ditemukan uji paritas Procedure (2026-10-29)

1. **Builder Procedure kehilangan dua medan yang FASADE punya** — `basedOn` (0..\* Reference) dan
   `reasonCode` (0..\* CodeableConcept). Kode lama yang dibangun lewat facade (`addBasedOn()`,
   `addReasonCode()`) tidak punya padanan di jalur native sama sekali, jadi pemakaian baru yang
   butuh dua medan itu harus kembali menyusun array manual — persis yang ingin diakhiri facade.
   Ditambahkan `PayloadBuilderProcedure::addBasedOn(Reference)` + `addReasonCode(CodeableConcept)`
   (keduanya `push()`, sesuai tapak 0..\*); uji paritas menutup selisihnya.
   **Pelajaran:** paritas bukan hanya soal *bentuk* payload untuk setter yang ada di kedua jalur —
   periksa juga setter yang hanya ada di SATU jalur. Uji paritaslah yang menemukannya, bukan
   pembacaan kode.
2. **`Procedure.location` pada facade berbentuk Reference datar** (`location: {reference: …}`),
   padahal FHIR R4 menetapkan `Procedure.location` = **0..\* BackboneElement** yang mewajibkan
   `location` (Reference) 1..1 di dalamnya. Builder belum punya setter padanannya. Karena
   memperbaikinya berarti mengubah bentuk keluaran facade (breaking bagi pemanggil lama),
   cacat ini **dibiarkan terbuka dan dicatat** — uji paritas sengaja tidak menyertakan
   `setLocation()` supaya selisih yang disengaja tidak tersamarkan sebagai paritas.
3. **`setCategory()` facade selalu menulis `text: null`** bila argumen `$text` tidak diisi
   (`'text' => $text` tanpa pemeriksaan), sedangkan `DataType` membuang nilai null. FHIR tidak
   mengenal properti bernilai null, jadi bentuk facade itu bukan payload yang sah. Uji paritas
   memakai `$text` terisi sehingga kedua jalur sama; pemakaian baru sebaiknya lewat builder.

## Cacat kelas: `Builder::push('a/b')` menulis KUNCI LITERAL (2026-10-29, ditemukan uji paritas Patient)

`Builder::push()` dulu tidak mengenal jalur berjenjang — hanya `set()` yang mengenalnya. Akibatnya
`push('meta/profile', …)` menambah entri pada kunci **`"meta/profile"`**, bukan `meta.profile`.
Payload-nya tetap JSON yang sah, jadi tidak ada satu pun galat: `json_encode` hanya meng-escape
garis miring, dan halaman/uji yang memeriksa `resourceType` atau medan lain tetap hijau. Yang
hilang adalah medan itu sendiri — `meta.profile` yang dituntut SATUSEHAT tidak pernah ada, dan
server menerima properti yang tidak dikenal.

Enam titik pemanggil terkena, semuanya terbukti lewat uji:

| Berkas | Pemanggilan | Bentuk lama | Bentuk benar |
|---|---|---|---|
| `PayloadBuilderPatient` | `push('meta/profile')` | `"meta/profile": [...]` | `meta.profile` |
| `PayloadBuilderImmunization` | `$this->data['meta/profile'] = …` | sama | `meta.profile` |
| `PayloadBuilderGroup` | `$this->data['meta/profile'] = …` | sama | `meta.profile` |
| `PayloadBuilderGenomicStudy` | `push('meta/profile')` | sama | `meta.profile` |
| `PayloadBuilderNutritionOrder` | `push('oralDiet/type')`, `push('oralDiet/schedule')` | `"oralDiet/type"` | `oralDiet.type` |
| `PayloadBuilderClaim` | `push('insurance/account')` | `"insurance/account"` | `insurance[0].account` |

Perbaikan dipilih di **satu tempat** (`Builder::push()` kini menelusuri segmen jalur seperti
`set()`), bukan enam tambalan — kalau tidak, builder berikutnya yang memakai bentuk berjenjang akan
mengulang cacat yang sama. Penjaga: `tests/FHIR/NestedPushPathTest.php` (2 uji) memindai seluruh
payload keenam builder dan menolak **kunci apa pun yang memuat `/`** di kedalaman berapa pun.

**Yang membuat cacat ini bertahan lama:** tiga uji justru MENGUNCI bentuk yang salah —
`GroupTest::test_set_meta_profile` dan `ImmunizationTest::test_set_meta_profile` menegaskan
`$result['meta/profile'][0]`, sementara `GenomicStudyTest` memuat komentar "push() doesn't handle
dot notation" sebagai keadaan yang diterima. Uji yang membaca kunci datar tidak membuktikan apa pun
tentang FHIR; ia hanya membuktikan kode berperilaku seperti kodenya. Keduanya diperbaiki ke
`$result['meta']['profile'][0]`.

**Cacat yang MASIH terbuka (butuh keputusan pemilik pustaka):** `Claim::addInsuranceAccount()`
menghasilkan `insurance: [{account: …}]`, padahal `Claim.insurance` 0..\* BackboneElement mewajibkan
`sequence` 1..1, `focal` 1..1, dan `coverage` 1..1. Sama kelasnya dengan `Procedure.location` dan
`InsurancePlan.coverage`: memperbaikinya mengubah bentuk keluaran (breaking), jadi dicatat dulu.

## Penjaga cakupan jalur berjenjang (2026-10-30)

`NestedPushPathTest` (6→8 kasus) membuktikan PERILAKU: `push('a/b', …)` mendarat di `a.b`. Ia tidak
menjaga CAKUPAN — pemanggil baru yang salah tetap lolos selama enam kasus lama hijau. Karena itu
`tests/FHIR/NestedPathSweepTest.php` memindai `src/` dan menuntut tiga hal:

1. setiap `push('<a>/<b>', …)` yang TIDAK ber-variabel punya padanan dinamis di
   `NestedPushPathTest` (dicocokkan sebagai jalur bertitik, mis. `insurance.account`);
2. tidak ada `$this->data['a/b']` (kunci literal) di `src/`;
3. tidak ada uji yang MENGUNCI kunci literal berjenjang (cacat 2026-10-29 terjadi justru karena
   dua uji menegaskan `['meta/profile']`).

**Komentar dibuang lebih dulu** (`token_get_all`) — `Builder::push()` memuat contoh
`push('meta/profile', …)` di komentarnya sendiri, dan `str_contains` akan menghitungnya sebagai
pemanggil sungguhan.

**Sapuan pertama versi ini hijau palsu.** Ia membangun SETIAP builder tanpa argumen lalu memeriksa
payload kosong — dan payload kosong tidak pernah memuat jalur berjenjang, jadi nol kunci berisi `/`
selalu benar. Kontrol negatif yang membuktikannya: mengembalikan `push()` ke bentuk literal membuat
`NestedPushPathTest` merah (2 kegagalan) sementara sapuan kosong itu TETAP hijau. Yang benar adalah
memindai titik pemanggilan, bukan keluaran.

**Dua celah yang ditemukan penjaga ini** (langsung ditutup uji dinamis): `Claim::addInsuranceAccount()`
→ `insurance.account` dan `NutritionOrder::addOralDietSchedule()` → `oralDiet.schedule`. Empat jalur
ber-variabel (`item/{$idx}/location` dan sejenisnya) sengaja dihitung, bukan dicocokkan; angka 4
diasersikan supaya titik baru muncul di diff, bukan lolos diam-diam (SRD T-05).

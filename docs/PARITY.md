# PARITY — lima SDK SATUSEHAT

Halaman ini **identik di lima wiki**: `satusehat-integration` (PHP), `-node`, `-python`, `-go`,
`-rust`. Semua angka **diukur** oleh `scripts/parity-manifest.sh` (di repo PHP) ke
`SYNC_MANIFEST.json` schema 3 — bukan dari ingatan.

Diukur: 2026-11-15 · commit PHP `67c91fd`

## 1. Jumlah artefak

Tabel di bawah **dihasilkan** oleh `scripts/parity-manifest.sh --docs` dari hitungan `find`/`grep` pada
tiap checkout. Jangan menyunting angkanya: sunting skripnya, lalu jalankan ulang. Angka hasil ukur yang
disalin tangan akan basi pada commit berikutnya, dan dokumen yang basi lebih buruk daripada tidak ada.

<!-- parity:begin (dihasilkan scripts/parity-manifest.sh --docs, jangan disunting) -->
Diukur: 2026-11-15 · commit PHP `93ebe3c`

| bagian | PHP | Node | Python | Go | Rust |
|---|---|---|---|---|---|
| payload builder | 141 | 144 | 141 | 141 | 9 |
| datatype | 33 | 33 | 33 | 33 | 12 |
| terminology files | 21 | 21 | 21 | 21 | 1 |
| queue files | 8 | 8 | 8 | 8 | 1 |
| parser files | 55 | 55 | 55 | 142 | 0 |
| test files | 231 | 196 | 183 | 246 | 72 |
| metode builder tidak diekspor (Go) | — | — | — | 62 dari 1788 | — |
<!-- parity:end -->

Selisih yang perlu dijelaskan, dan yang tidak akan hilang dengan sendirinya:

- **Node menghitung lebih banyak kelas builder daripada PHP.** Tiga alias kelas ikut terhitung — nama
  kanon ditambah nama lama yang dipertahankan supaya pemanggil lama tidak pecah.
- **Parser Go menghitung satu berkas lebih banyak** karena satu berkas pembantu cocok dengan pola
  `*_parser.go`. Berkas parser per resource sama banyaknya dengan PHP.
- **Rust tidak mengejar jumlah builder.** Desainnya struct input bertipe per resource klinis, jadi
  cakupannya diukur dari resource yang dipakai, bukan dari jumlah modul.
- **Jumlah berkas uji bukan ukuran cakupan.** Satu berkas bisa memuat satu uji atau seratus; yang menentukan
  adalah apakah kedua implementasi pernah dijalankan pada input yang sama.

## 2. Kemampuan

| kemampuan | PHP | Node | Python | Go | Rust |
|---|---|---|---|---|---|
| payload builder | present | present | present | present | partial |
| http send | present | present | present | present | present |
| response validation | present | present | present | present | present |
| fhir parser | present | present | present | present | absent |
| terminology client | present | present | present | present | present |

`partial` = ada sebagian. `absent` = tidak ada. Dinilai dari keberadaan berkas, bukan uji perilaku.

## 3. Cacat terukur

Angkanya ada di tabel bagian 1, baris terakhir. Metode berhuruf kecil itu bukan setter resource: 28 `set`, 28 `push`, 2 `fail`, dan empat pembantu lain
(`ref`, `autoPrefix`, `setCodeable`, `dispenseRequest`). Semuanya dipakai di dalam paket; tidak ada satu pun
setter resource yang tersembunyi. Pada 2026-11-14 keadaan yang tercatat di halaman ini berbeda: saat itu angka
"629 dari 976" dan klaim bahwa `PatientBuilder` tidak mengekspor `SetId`/`AddIdentifier`. Keduanya sudah
tidak berlaku — seluruh setter resource sekarang berhuruf besar dan `go build ./...` bersih di VPS ini.

Nol cacat terbuka yang terukur sampai 2026-11-15.

## 4. Konvensi penamaan

| konsep | PHP | Node/TS | Python | Go | Rust |
|---|---|---|---|---|---|
| kelas pembangun | `PayloadBuilderPatient` | `Patient` | `PatientBuilder` | `PatientBuilder` | `PatientInput` + `Builder::patient` |
| set satu nilai | `setGender()` | `setGender()` | `set_gender()` | `SetGender()` | field struct `gender` |
| tambah ke daftar | `addIdentifier()` | `addIdentifier()` | `add_identifier()` | `AddIdentifier()` | field `Vec`/`Option` |
| hasil | `->build()` | `.build()` | `.build()` | `.Build()` | `Builder::patient(&input)` |
| validasi | `validate()` | `validate()` | `validate()` | `Validate()` | `validate(&value)` |
| kirim | `SSRequest` | `SSRequest` | `SSRequest` | `SSRequest` | `submit_bundle()` / CLI `push` |
| parser | `PatientParser` | `PatientParser` | `PatientParser` | `PatientParser` | belum |

Dua ketidakseragaman yang **sengaja dibiarkan**:

1. **Node menamai kelas = nama resource** (`Patient`, bukan `PatientBuilder`) — idiom TypeScript,
   sudah jadi permukaan publik; mengubahnya berarti breaking change.
2. **Rust memakai struct input**, bukan setter berantai — struct literal memaksa semua field wajib
   terisi, tidak ada objek setengah jadi.

## 5. Contoh setara

```php
$patient = (new PayloadBuilderPatient())->setNik('3201234567890123')
    ->setName('WIWI TJAHJADI')->setGender('female')->setBirthDate('1987-03-14')->build();
```
```ts
const patient = new Patient().setNik('3201234567890123')
  .setName('WIWI TJAHJADI').setGender('female').setBirthDate('1987-03-14').build();
```
```python
patient = (PatientBuilder().set_nik("3201234567890123")
    .set_name("WIWI TJAHJADI").set_gender("female").set_birth_date("1987-03-14").build())
```
```go
patient := builder.NewPatientBuilder().SetNik("3201234567890123").
    SetName("WIWI TJAHJADI").SetGender("female").SetBirthDate("1987-03-14").Build()
```
```rust
let patient = Builder::patient(&PatientInput {
    nik: "3201234567890123".into(), ihs_number: None,
    name: "WIWI TJAHJADI".into(), gender: "female".into(),
    birth_date: "1987-03-14".into(), phone: None, address: None, city: None,
});
```

Kesetaraan yang dijaga: **konsep dan JSON yang dihasilkan**, bukan bentuk pemanggilan. Buktinya
korpus konformansi di `corpus/` yang dipakai bersama.

## 6. Aturan wiki

Halaman wajib ada di kelima wiki dengan nama sama: `Home`, `Installation`, `Usage`, `Features`,
`PayloadBuilder`, `FHIR-Resources`, `FHIR-DataTypes`, `SSRequest-SSResponse`,
`Validation-Rule-Numbers`, `Error-Handling`, `Onboarding`, `Parity`.

`Parity` harus identik kata per kata. Periksa:

```bash
for r in satusehat-integration satusehat-integration-node satusehat-integration-python \
         satusehat-integration-go satusehat-integration-rust; do
  git clone -q https://github.com/ivanwilliammd/$r.wiki.git /tmp/w-$r 2>/dev/null
  sha256sum /tmp/w-$r/Parity.md
done | awk '{print $1}' | sort -u | wc -l     # harus 1
```

## 7. Pekerjaan berikut

1. **Rust: builder 5 dari permukaan typed, parser nol.** Rust tidak mengejar angka 141: desainnya struct
   input bertipe, jadi cakupan diukur dari resource klinis yang dipakai, bukan dari jumlah modul.
2. **Wiki `Parity` di lima repo harus identik kata per kata**, dan halaman ini baru saja diubah — wiki
   belum disinkronkan.
3. **Samakan cara menghitung aturan validasi** di kelima bahasa; sekarang hanya PHP yang menghitungnya.

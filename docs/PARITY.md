# PARITY — lima SDK SATUSEHAT

Halaman ini **identik di lima wiki**: `satusehat-integration` (PHP), `-node`, `-python`, `-go`,
`-rust`. Semua angka **diukur** oleh `scripts/parity-manifest.sh` (di repo PHP) ke
`SYNC_MANIFEST.json` schema 3 — bukan dari ingatan.

Diukur: 2026-11-14 · commit PHP `e4c3667`

## 1. Jumlah artefak

| bagian | PHP | Node | Python | Go | Rust |
|---|---|---|---|---|---|
| payload builder | 141 | 141 | 141 | 141 | 4 |
| datatype | 32 | 31 | 33 | 33 | 12 |
| terminology files | 22 | 20 | 27 | 17 | 1 |
| queue files | 8 | 8 | 8 | 8 | 1 |
| parser files | 18 | 18 | 18 | 18 | 0 |
| test files | 53 | 8 | 9 | 7 | 38 |

Empat SDK (PHP · Node · Python · Go) **sama jumlahnya** pada builder, queue, dan parser:
141 · 8 · 18. Perbedaan yang tersisa: datatype (31–33), terminologi (17–27), dan jumlah berkas uji
(8–9 vs 53 di PHP) — jumlah uji bukan ukuran cakupan.

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

```
go: metode builder tidak diekspor — 629 dari 976 metode berhuruf kecil
        dampak: 347 metode dapat dipanggil dari luar paket
```
`PatientBuilder` di Go punya `setId`, `addIdentifier`, `setStatus`, `setSubject` — semuanya huruf
kecil, jadi **tidak diekspor**. Perbaikan menyentuh ratusan metode dan harus disertai
`go build ./...` yang sehat; toolchain Go di VPS build saat ini rusak (`/usr/lib/golang` tanpa
`src`), jadi perbaikan belum dikerjakan.

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

1. **Go: ekspor 629 metode builder** — penghalang pemakaian nyata; butuh toolchain sehat.
2. **Node/Python/Go: berkas uji** — 8–9 vs 53 di PHP; cakupan perilaku belum terukur.
3. **Rust: 4 → 141 builder, tambah parser** — jalur panjang, dikerjakan bertahap oleh nightly worker.
4. **Samakan cara menghitung aturan validasi** di kelima bahasa.

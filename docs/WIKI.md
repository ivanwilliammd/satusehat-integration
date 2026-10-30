# Wiki: apa yang dijaga, dan bagaimana memperbaruinya

Wiki GitHub (`ivanwilliammd/satusehat-integration.wiki.git`) adalah dokumentasi pemakai paket.
Halaman yang ada: `Home`, `Installation`, `Onboarding`, `Usage`, `Features`, `PayloadBuilder`,
`FHIR-Resources`, `FHIR-DataTypes`, `SSRequest-SSResponse`, `Validation-Rule-Numbers`, `Error-Handling`.

## Angka yang tidak boleh ditulis tangan

Jumlah kelas PayloadBuilder adalah angka yang paling cepat basi: wiki sempat menyebut **112**
sementara `src/Builder/` sudah berisi **141**, dan README menyebut **51**. Ketiganya sekarang
dijaga mesin, bukan ingatan.

| Sumber angka | Dijaga oleh |
|---|---|
| Wiki `PayloadBuilder` (indeks per kelas) | `docs/scripts/build-wiki-builder-index.php` — blok di antara penanda `GENERATED:builder-index` |
| Hitungan di `Home`, `Features`, `FHIR-Resources` | dibangkitkan/diperiksa dengan skrip yang sama |
| Angka di `README.md` | `tests/Unit/WikiBuilderIndexTest.php` (gagal bila berbeda) |

## Perintah

```bash
# cetak indeks (tanpa menulis apa pun)
php docs/scripts/build-wiki-builder-index.php

# tulis ulang blok di halaman wiki lokal (wiki-clone/ atau hasil git clone .wiki.git)
php docs/scripts/build-wiki-builder-index.php --splice=wiki/PayloadBuilder.md

# periksa apakah wiki basi; keluar 1 bila ya (bisa dipakai di CI/pre-push)
php docs/scripts/build-wiki-builder-index.php --check=wiki/PayloadBuilder.md
```

Berkas sasaran **wajib** memuat penanda berikut; tanpa penanda skrip keluar 1 (bukan menebak posisi):

```
<!-- GENERATED:builder-index:start -->
<!-- GENERATED:builder-index:end -->
```

## Aturan saat menambah PayloadBuilder

1. Tambah berkas `src/Builder/PayloadBuilder<Tipe>.php` seperti biasa.
2. Pembangun NON-FHIR **wajib** menyebut `NON-FHIR` di docblock kelasnya — itulah satu-satunya
   penanda yang dipakai skrip untuk memisahkan 139 FHIR R4 dari 2 pembangun khusus SATUSEHAT.
3. Jalankan `--splice` pada halaman wiki, lalu `--check` (harus "segar").
4. Perbarui angka di `README.md`; uji `WikiBuilderIndexTest` akan menolak bila lupa.
5. `vendor/bin/phpunit` tetap hijau sebelum push.

## Batas yang diketahui

- Skrip membaca **nama berkas + docblock + daftar metode publik**, bukan memvalidasi isi FHIR.
  Kesalahan struktur resource tetap ditangkap uji FHIR, bukan oleh indeks wiki.
- Kolom "Key Methods" berisi empat metode `set*`/`add*` pertama sesuai urutan di berkas — urutan itu
  bukan penilaian kepentingan, jadi jangan dibaca sebagai anjuran pemakaian.
- `ResourceGuide` dideklarasikan sebagai R4 di docblock-nya sendiri; indeks mengikuti deklarasi repo,
  bukan katalog HL7 (ResourceGuide sebenarnya R5). Perbaikan deklarasi = keputusan pemilik paket.

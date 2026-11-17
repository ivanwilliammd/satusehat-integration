# Versi FHIR: R4 sebagai acuan, R5 disiapkan

SATUSEHAT berjalan di FHIR R4. Karena itu **R4 adalah acuan utama SDK ini**, dan seluruh kelas
tanpa awalan versi — `Satusehat\Integration\Builder\*`, `Satusehat\Integration\DataType\*` —
adalah R4.

R5 disiapkan tanpa mengubah satu pun pemanggilan yang sudah ada.

## Janji yang dipegang

Memakai R5 cukup dengan mengganti baris `use`:

```php
// R4 (acuan, tanpa perubahan apa pun)
use Satusehat\Integration\Builder\PayloadBuilderPatient;

// R5 — hanya baris ini yang berubah
use Satusehat\Integration\R5\Builder\PayloadBuilderPatient;
```

Sisanya tetap: nama kelas, nama method, urutan argumen, bentuk payload. Ini bukan harapan, melainkan
dijaga uji `tests/Version/R5NamespaceTest.php` untuk setiap kelas:

1. kelas R5 ada untuk semua 142 builder dan 34 datatype;
2. kelas R5 adalah **turunan** kelas R4 (`is_subclass_of`);
3. daftar method publiknya **identik** dengan kelas R4 (reflection);
4. hasil generate tidak boleh menyimpang dari kanon (`--check`).

## Kenapa turunan, bukan salinan

Salinan akan bercabang: satu perbaikan di R4 tidak sampai ke R5, dan dua perilaku mulai berbeda
tanpa ada yang memutuskan. Turunan membuat R5 mewarisi R4 apa adanya — selama R5 memang tidak
mengubah apa pun, tidak ada yang bisa melenceng.

## Bila R5 memang berbeda

Satu resource = satu berkas. Hapus penanda `@generated` di
`src/R5/Builder/PayloadBuilderX.php`, lalu tulis implementasinya. Generator berhenti menyentuh
berkas itu (ia hanya mengurus berkas ber-penanda `@generated`), dan uji reflection akan menuntut
perbedaannya dinyatakan — bukan disembunyikan.

Aturan yang sama berlaku untuk datatype: `src/R5/DataType/CodeableConcept.php`, dan seterusnya.

**Saat ini belum ada satu pun perbedaan R5 yang ditulis**, karena belum ada yang diverifikasi
terhadap spesifikasi R5. Menyalin dugaan ke dalam kode akan menghasilkan kelas R5 yang mengaku
sesuatu yang tidak diperiksa.
Menambah perbedaan dilakukan satu per satu, dengan rujukan elemen R5-nya.

## Batas yang perlu dinyatakan

- Lapisan R5 saat ini **belum** memuat elemen yang hanya ada di R5. Selama itu belum ditulis,
  `R5\Builder\X` menghasilkan payload R4 — dan itu memang perilaku yang diuji.
- Validasi, parser, dan queue belum dipisah per versi; mereka bekerja pada bentuk payload, bukan
  pada definisi versi.

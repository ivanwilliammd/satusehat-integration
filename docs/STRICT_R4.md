# Mode ketat R4

Builder generik bisa menulis elemen yang tidak ada di resource-nya. Payload-nya tetap JSON sah, dan
validasi struktural tidak mengeluh — tetapi FHIR R4 tidak mengenal elemen itu, dan SATUSEHAT akan
menolak atau mengabaikannya.

## Mengaktifkan

```bash
export SATUSEHAT_STRICT_R4=1
```

`Builder::build()` lalu memanggil `R4ElementGuard::assert()` dan melempar `InvalidArgumentException`
yang menyebut resource dan elemen yang salah:

```
Goal menulis elemen yang tidak ada di FHIR R4: startDateTime
```

## Kenapa opt-in, bukan bawaan

Menyalakannya secara default akan menggagalkan pemanggil yang sudah memakai setter lama. Kesalahannya
nyata, tetapi memutus pemanggil produksi bukan tugas pustaka ini — pemilik kode yang tahu kapan
pindah.

## Dasar pemeriksaan

`data/r4_elements.json` — 148 resource, dihasilkan `scripts/generate_r4_elements.py` dari
`profiles-resources.json` HL7 R4 resmi. Elemen pilihan (`onset[x]`, `deceased[x]`) sudah diperluas ke
nama konkretnya, sehingga bentuk sah tidak dituduh melanggar.

Resource yang tidak ada di R4 (mis. `ChargeItemResponse`) tidak diperiksa: tidak ada dasar untuk
menyebutnya salah. Tujuh builder seperti itu sudah ditandai `@deprecated` di `docs/R4_CONFLICT_TRIAGE.md`.

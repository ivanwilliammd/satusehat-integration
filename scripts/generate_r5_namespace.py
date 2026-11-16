"""Siapkan namespace R5 sebagai lapisan tipis di atas kelas kanon R4.

Yang diinginkan: memakai R5 cukup dengan mengganti baris `use`, bukan menulis ulang pemanggilan.
Karena itu setiap kelas R5 dideklarasikan sebagai turunan kelas R4 dengan nama dan permukaan method
yang sama — selama R5 memang tidak mengubahnya.

Bila satu resource MEMANG berbeda di R5, berkas hasil generate untuk resource itu diganti dengan
implementasi tulisan tangan. Generator tidak akan menyentuhnya: berkas yang tidak memuat penanda
@generated dianggap milik manusia.

Untuk saat ini semua kelas R5 adalah turunan apa adanya. Itu bukan klaim bahwa R5 identik dengan
R4 — itu berarti belum ada perbedaan yang DIVERIFIKASI, dan perbedaan yang belum diverifikasi
tidak boleh dikarang.

Jalankan: python3 scripts/generate_r5_namespace.py [--check]
"""
import pathlib
import re
import sys

AKAR = pathlib.Path(__file__).resolve().parents[1]
SUMBER = {"Builder": AKAR / "src" / "Builder", "DataType": AKAR / "src" / "DataType"}
TUJUAN = AKAR / "src" / "R5"


def kelas(berkas: pathlib.Path) -> str | None:
    m = re.search(r"^(?:final\s+|abstract\s+)?class\s+(\w+)", berkas.read_text(), re.M)
    return m.group(1) if m else None


def isi(ruang: str, nama: str) -> str:
    return f"""<?php

declare(strict_types=1);

namespace Satusehat\\Integration\\R5\\{ruang};

/**
 * @generated oleh scripts/generate_r5_namespace.py — jangan disunting tangan.
 *
 * Lapisan R5: turunan apa adanya dari kelas kanon (R4). Selama R5 tidak mengubah elemen resource
 * ini, memakai R5 cukup dengan mengganti baris `use`. Bila R5 memang berbeda, hapus penanda
 * @generated dan tulis implementasinya di sini — generator akan berhenti menyentuh berkas ini.
 */
class {nama} extends \\Satusehat\\Integration\\{ruang}\\{nama}
{{
}}
"""


def sinkronkan() -> list[str]:
    berubah: list[str] = []
    for ruang, direktori in SUMBER.items():
        (TUJUAN / ruang).mkdir(parents=True, exist_ok=True)
        for berkas in sorted(direktori.glob("*.php")):
            nama = kelas(berkas)
            if not nama:
                continue
            tujuan = TUJUAN / ruang / f"{nama}.php"
            baru = isi(ruang, nama)
            if not tujuan.exists():
                tujuan.write_text(baru, encoding="utf-8", newline="\n")
                berubah.append(f"baru   R5/{ruang}/{nama}.php")
                continue
            # Windows checkout mengubah akhir baris menjadi CRLF; tanpa normalisasi, setiap
            # berkas akan terlihat "berubah" dan --check gagal di runner Windows saja.
            lama = tujuan.read_text(encoding="utf-8").replace("\r\n", "\n")
            if "@generated" not in lama:
                continue  # milik manusia: implementasi R5 yang berbeda
            if lama != baru:
                tujuan.write_text(baru, encoding="utf-8", newline="\n")
                berubah.append(f"ubah   R5/{ruang}/{nama}.php")
    return berubah


def main() -> int:
    n = sinkronkan()
    if "--check" in sys.argv:
        if n:
            print("namespace R5 belum sinkron:")
            print("\n".join(n[:20]))
            return 1
        print("namespace R5 sinkron")
        return 0
    print(f"{len(n)} berkas diubah")
    print("\n".join(n[:10]))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

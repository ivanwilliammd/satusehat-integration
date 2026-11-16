"""Tandai setter kategori B yang menulis elemen di luar R4.

Sumber keputusan: `docs/r4_category_b_decisions.json` (dihasilkan scripts/decide_category_b.py dari
definisi R4 dan R5 resmi). Skrip ini tidak memutuskan apa pun — ia hanya menerapkan keputusan itu
sebagai docblock, supaya pemanggil melihat peringatan alih-alih menemukan payload yang ditolak.

Status yang ditandai:
  quirk — elemen ada di R5, tidak di R4. Berguna bila kelak pindah ke namespace R5.
  asing — tidak ada di R4 maupun R5. Kemungkinan besar sisa salinan dari sumber lain.

Status `pindah` tidak ditandai di sini: perbaikannya ada di badan method (jalur R4 yang benar),
bukan di docblock.

Jalankan: python3 scripts/mark_category_b.py [--check]
"""
import json
import pathlib
import re
import sys

AKAR = pathlib.Path(__file__).resolve().parents[1]
BUILDER = AKAR / "src" / "Builder"
KEPUTUSAN = AKAR / "docs" / "r4_category_b_decisions.json"

TEKS = {
    "quirk": "hanya ada di FHIR R5; payload R4 dengan elemen ini akan ditolak server",
    "asing": "tidak ada di FHIR R4 maupun R5; jangan diandalkan",
}


def blok_method(teks: str):
    posisi = [(m.start(), m.group(1)) for m in re.finditer(r"^    public function (\w+)\(", teks, re.M)]
    hasil = []
    for i, (awal, nama) in enumerate(posisi):
        akhir = posisi[i + 1][0] if i + 1 < len(posisi) else len(teks)
        hasil.append((nama, awal, akhir))
    return hasil


def tandai(path: pathlib.Path, status_per_elemen: dict) -> list[str]:
    teks = path.read_text(encoding="utf-8")
    ditandai = []
    for nama, awal, akhir in reversed(blok_method(teks)):
        badan = teks[awal:akhir]
        kena = [
            (e, s) for e, s in status_per_elemen.items()
            if s in TEKS and re.search(rf"->(?:set|push)\(\s*'{re.escape(e)}'", badan)
        ]
        if not kena:
            continue
        sebelum = teks[max(0, awal - 500):awal]
        if "@deprecated" in sebelum.split("public function")[-1]:
            continue
        baris = "\n".join(f"     * @deprecated `{e}` {TEKS[s]}" for e, s in kena)
        doc = f"    /**\n{baris}\n     */\n"
        teks = teks[:awal] + doc + teks[awal:]
        ditandai.append(nama)
    if ditandai:
        path.write_text(teks, encoding="utf-8")
    return ditandai


def main() -> int:
    if not KEPUTUSAN.exists():
        raise SystemExit("docs/r4_category_b_decisions.json belum ada — jalankan decide_category_b.py")
    keputusan = json.loads(KEPUTUSAN.read_text(encoding="utf-8"))

    jumlah, rincian = 0, []
    for builder, elemen in sorted(keputusan.items()):
        status = {e: v["status"] for e, v in elemen.items()}
        if not any(s in TEKS for s in status.values()):
            continue
        ditandai = tandai(BUILDER / f"{builder}.php", status)
        if ditandai:
            jumlah += len(ditandai)
            rincian.append(f"{builder}: {', '.join(ditandai)}")

    if "--check" in sys.argv:
        if rincian:
            print("\n".join(rincian))
            return 1
        print("semua setter kategori B sudah ditandai")
        return 0
    print(f"{jumlah} method ditandai di {len(rincian)} builder")
    print("\n".join(rincian[:10]))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

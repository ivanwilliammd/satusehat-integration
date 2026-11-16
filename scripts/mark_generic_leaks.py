"""Tandai setter yang menulis elemen di luar definisi R4 pada resource itu.

Kenapa menandai, bukan menghapus: setter generik (`setStatus`, `setCode`, `setSubject`, ...) sah
dipakai pada resource lain, jadi yang salah adalah PEMAKAIANNYA di builder ini. Menghapus method
akan merusak pemanggil lama yang mungkin memang mengandalkan payload apa adanya; menandai
`@deprecated` memberi peringatan tanpa memutus kode.

Alasan ini perlu diulang di setiap builder: getter/setter tidak pernah melempar galat saat menulis
elemen asing — payload hanya akan ditolak server, jauh setelah kode ditinggalkan penulisnya.

Jalankan: python3 scripts/mark_generic_leaks.py [--check]
"""
import json
import pathlib
import re
import subprocess
import sys

AKAR = pathlib.Path(__file__).resolve().parents[1]
BUILDER = AKAR / "src" / "Builder"


def audit() -> list:
    keluaran = subprocess.run(
        [sys.executable, str(AKAR / "scripts" / "audit-r4-conformance.py"), "--json"],
        capture_output=True, text=True, check=True,
    ).stdout
    return json.loads(keluaran)


def blok_method(teks: str):
    """(nama, awal, akhir) untuk setiap method publik, agar badan method bisa diperiksa."""
    posisi = [(m.start(), m.group(1)) for m in re.finditer(r"^    public function (\w+)\(", teks, re.M)]
    hasil = []
    for i, (awal, nama) in enumerate(posisi):
        akhir = posisi[i + 1][0] if i + 1 < len(posisi) else len(teks)
        hasil.append((nama, awal, akhir))
    return hasil


def tandai(path: pathlib.Path, elemen: list[str]) -> list[str]:
    teks = path.read_text()
    ditandai = []
    potongan = []
    sisa = teks
    for nama, awal, akhir in reversed(blok_method(teks)):
        badan = teks[awal:akhir]
        kena = [e for e in elemen if re.search(rf"->(?:set|push)\(\s*'{re.escape(e)}'", badan)]
        if not kena:
            continue
        sebelum = teks[max(0, awal - 400):awal]
        if "@deprecated" in sebelum.split("public function")[-1]:
            continue
        doc = (
            "    /**\n"
            f"     * @deprecated menulis {', '.join('`'+k+'`' for k in kena)} padahal resource ini tidak\n"
            "     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.\n"
            "     */\n"
        )
        sisa = sisa[:awal] + doc + sisa[awal:]
        ditandai.append(nama)
    if ditandai:
        path.write_text(sisa)
    return ditandai


def main() -> int:
    kena = 0
    rincian = []
    for row in audit():
        if not row["in_r4"] or not row["not_in_r4"]:
            continue
        generik = {"id", "identifier", "status", "code", "subject", "encounter"}
        elemen = [e for e in row["not_in_r4"] if e in generik]
        if not elemen:
            continue
        path = BUILDER / f"{row['builder']}.php"
        ditandai = tandai(path, elemen)
        if ditandai:
            kena += len(ditandai)
            rincian.append(f"{row['builder']}: {', '.join(ditandai)}")
    if "--check" in sys.argv:
        if rincian:
            print("\n".join(rincian))
            return 1
        print("tidak ada setter yang belum ditandai")
        return 0
    print(f"{kena} method ditandai di {len(rincian)} builder")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

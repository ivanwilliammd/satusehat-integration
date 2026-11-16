"""Hasilkan/rapikan parser PHP dari builder kanon.

Parser di repo ini hanya membaca elemen tingkat atas sebuah resource. Menulis 87 kelas dengan
tangan berarti 87 kesempatan salah ketik nama elemen, dan nama yang salah TIDAK memicu galat —
ia hanya mengembalikan null. Karena itu daftar getter diturunkan dari builder: satu-satunya
sumber kebenaran tentang elemen apa yang bisa ditulis adalah builder itu sendiri.

Aturan:
- Berkas yang belum ada dibuat utuh.
- Berkas yang sudah ada TIDAK ditimpa: getter yang belum ada ditambahkan sebelum `}` terakhir.
  Getter tulisan tangan (mis. getMultipleBirth dengan fallback Boolean/Integer) dipertahankan.
- Elemen `resourceType` tidak dibuatkan getter ganda; sudah ada getResourceType().

Jalankan: python3 scripts/generate_parsers.py [--check]
  --check  keluar dengan kode 1 bila ada berkas yang belum sinkron (dipakai uji CI)
"""
import pathlib
import re
import sys

AKAR = pathlib.Path(__file__).resolve().parents[1]
BUILDER = AKAR / "src" / "Builder"
PARSER = AKAR / "src" / "Parser"

POLA_ELEMEN = (
    re.compile(r"\$this->(?:set|push)\(\s*'([^']+)'"),
    re.compile(r"\$this->data\[\s*'([A-Za-z0-9_]+)'\s*\]"),
)


def elemen_builder(teks: str) -> list[str]:
    kunci: list[str] = []
    for pola in POLA_ELEMEN:
        for m in pola.finditer(teks):
            k = m.group(1).split("/")[0]
            if k and k not in kunci:
                kunci.append(k)
    return [k for k in kunci if k != "resourceType" and re.fullmatch(r"[A-Za-z][A-Za-z0-9_]*", k)]


def nama_getter(kunci: str) -> str:
    return "get" + kunci[0].upper() + kunci[1:]


def getter_yang_ada(teks: str) -> set[str]:
    return set(m.group(1) for m in re.finditer(r"function\s+(get[A-Za-z0-9_]*)\s*\(", teks))


def kepala(resource: str) -> str:
    return f"""<?php

declare(strict_types=1);

namespace Satusehat\\Integration\\Parser;

/**
 * Parser untuk resource FHIR R4 {resource}.
 *
 * Dihasilkan dari PayloadBuilder{resource} oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/{resource.lower()}.html
 */
class {resource}Parser
{{
    private array $data;

    public function __construct($data)
    {{
        $this->data = is_array($data) ? $data : [];
    }}

    public function getRaw(): array
    {{
        return $this->data;
    }}
"""


def isi_getter(kunci: str) -> str:
    return f"""
    public function {nama_getter(kunci)}()
    {{
        return $this->data['{kunci}'] ?? null;
    }}
"""


def berkas_untuk(resource: str, elemen: list[str]) -> str:
    bagian = [kepala(resource)]
    for k in elemen:
        bagian.append(isi_getter(k))
    bagian.append("}\n")
    return "".join(bagian)


def sinkronkan() -> list[str]:
    berubah: list[str] = []
    for path in sorted(BUILDER.glob("PayloadBuilder*.php")):
        teks = path.read_text()
        m = re.search(r"resourceType\s*=\s*'([^']+)'", teks)
        if not m:
            continue
        resource = m.group(1)
        elemen = elemen_builder(teks)
        tujuan = PARSER / f"{resource}Parser.php"
        if not tujuan.exists():
            tujuan.write_text(berkas_untuk(resource, elemen))
            berubah.append(f"baru   {tujuan.name}")
            continue
        lama = tujuan.read_text()
        ada = getter_yang_ada(lama)
        kurang = [k for k in elemen if nama_getter(k) not in ada]
        if not kurang:
            continue
        sisipan = "".join(isi_getter(k) for k in kurang)
        posisi = lama.rstrip().rfind("}")
        tujuan.write_text(lama[:posisi] + sisipan + lama[posisi:])
        berubah.append(f"tambah {tujuan.name} ({len(kurang)} getter)")
    return berubah


def main() -> int:
    n = sinkronkan()
    if "--check" in sys.argv:
        if n:
            print("parser belum sinkron:")
            print("\n".join(n))
            return 1
        print("parser sinkron")
        return 0
    print(f"{len(n)} berkas diubah")
    print("\n".join(n[:20]))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

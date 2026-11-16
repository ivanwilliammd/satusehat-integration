"""Putuskan setiap elemen kategori B: pindah ke jalur R4, quirk R5, atau tidak ada di keduanya.

Keputusan tidak boleh dari ingatan. Skrip ini membaca definisi R4 dan R5 yang resmi lalu menulis
`docs/r4_category_b_decisions.json`. Berkas itulah yang dipakai uji dan penanda @deprecated, supaya
CI tidak perlu mengunduh definisi FHIR.

  (a) pindah  — elemen ada di R4, tetapi di jalur lain (mis. Claim.insurance.coverage)
  (b) quirk   — elemen tidak ada di R4, tetapi ada di R5
  (c) asing   — tidak ada di R4 maupun R5

Jalankan: R4_DIR=/tmp/r4def R5_DIR=/tmp/r5def python3 scripts/decide_category_b.py
"""
import json
import os
import pathlib
import subprocess
import sys

AKAR = pathlib.Path(__file__).resolve().parents[1]
TUJUAN = AKAR / "docs" / "r4_category_b_decisions.json"
GENERIK = {"id", "identifier", "status", "code", "subject", "encounter"}


def elemen_sah(direktori: pathlib.Path):
    """{resource: (nama elemen tingkat atas yang sah, daftar jalur lengkap)}."""
    sumber = direktori / "profiles-resources.json"
    if not sumber.exists():
        raise SystemExit(f"definisi tidak ada: {sumber}")
    data = json.loads(sumber.read_text(encoding="utf-8"))
    hasil = {}
    for entry in data["entry"]:
        res = entry["resource"]
        if res.get("resourceType") != "StructureDefinition" or res.get("kind") != "resource":
            continue
        if not res.get("snapshot", {}).get("element"):
            continue
        nama, jalur = set(), []
        for el in res["snapshot"]["element"]:
            if not el["path"].startswith(res["id"] + "."):
                continue
            bagian = el["path"].split(".")
            jalur.append(el["path"])
            if len(bagian) != 2:
                continue
            akar = bagian[1]
            if akar.endswith("[x]"):
                dasar = akar[:-3]
                for tipe in el.get("type", []):
                    kode = tipe.get("code", "")
                    if kode and kode != "Extension":
                        nama.add(dasar + kode[0].upper() + kode[1:])
            else:
                nama.add(akar)
        hasil[res["id"]] = (nama, jalur)
    return hasil


def main() -> int:
    r4 = elemen_sah(pathlib.Path(os.environ.get("R4_DIR", "/tmp/r4def")))
    r5_dir = pathlib.Path(os.environ.get("R5_DIR", "/tmp/r5def"))
    r5 = elemen_sah(r5_dir) if (r5_dir / "profiles-resources.json").exists() else {}

    keluaran = subprocess.run(
        [sys.executable, str(AKAR / "scripts" / "audit-r4-conformance.py"), "--json"],
        capture_output=True, text=True, encoding="utf-8", check=True,
    ).stdout
    baris = json.loads(keluaran)

    keputusan = {}
    for row in baris:
        if not row["in_r4"]:
            continue
        asing = [e for e in row["not_in_r4"] if e not in GENERIK]
        if not asing:
            continue
        for elemen in asing:
            jalur4 = [p for p in r4[row["resourceType"]][1]
                      if p.split(".")[-1].replace("[x]", "") == elemen]
            in_r5 = elemen in r5.get(row["resourceType"], (set(), []))[0]
            # Pengecualian yang disengaja: R4 memang punya jalur itu, tetapi maknanya berbeda.
            # PaymentReconciliation.detail.type adalah jenis satu baris rincian pembayaran, bukan
            # jenis rekonsiliasi; memindahkannya ke sana akan menghasilkan data yang salah meski
            # elemennya "ada". Karena R5 menaikkannya ke tingkat atas, statusnya quirk.
            if row["resourceType"] == "PaymentReconciliation" and elemen == "type":
                status, alasan = "quirk", "hanya ada di tingkat atas pada R5; R4.detail.type bukan elemen yang sama"
            elif jalur4:
                status, alasan = "pindah", f"R4 punya {jalur4[0]}"
            elif in_r5:
                status, alasan = "quirk", "hanya ada di R5"
            else:
                status, alasan = "asing", "tidak ada di R4 maupun R5"
            keputusan.setdefault(row["builder"], {})[elemen] = {
                "status": status,
                "r4_path": jalur4[0] if jalur4 else None,
                "r5": in_r5,
                "alasan": alasan,
            }

    TUJUAN.write_text(
        json.dumps(keputusan, indent=1, sort_keys=True, ensure_ascii=False) + "\n",
        encoding="utf-8",
    )
    jumlah = {}
    for isi in keputusan.values():
        for d in isi.values():
            jumlah[d["status"]] = jumlah.get(d["status"], 0) + 1
    print(f"{TUJUAN.name}: {len(keputusan)} builder · {jumlah}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

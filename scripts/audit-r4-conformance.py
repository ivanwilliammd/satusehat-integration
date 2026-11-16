#!/usr/bin/env python3
"""Audit PHP builders against the FHIR R4 specification.

Parity between ports says the four SDKs produce the same payload. It says nothing
about whether that payload is FHIR. This script answers the second question: for
every PayloadBuilder<X>.php, list the element names it writes that do not exist on
resource X in the R4 definitions, and flag resource types that R4 never defined.

Usage:
    python3 scripts/audit-r4-conformance.py            # summary
    python3 scripts/audit-r4-conformance.py --list     # per-builder detail
    python3 scripts/audit-r4-conformance.py --json     # machine readable

Definitions are read from $R4_DIR/profiles-resources.json (default /tmp/r4def),
fetched once from https://hl7.org/fhir/R4/definitions.json.zip when missing.
"""
import json
import os
import re
import subprocess
import sys
from pathlib import Path

# Windows memakai cp1252 sebagai encoding konsol; dokumen ini berisi karakter non-ASCII dan
# JSON-nya dibaca skrip lain, jadi stdout dipaksa UTF-8 supaya tidak gagal di runner Windows.
if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8")

R4_DIR = Path(os.environ.get("R4_DIR", "/tmp/r4def"))
BUILDER_DIR = Path(__file__).resolve().parent.parent / "src" / "Builder"
RETRIEVE = r"->(?:set|push|setCodeable)\(\s*'([A-Za-z][A-Za-z0-9]*)'"


def load_definitions() -> dict:
    """Return {resourceType: set(allowed top-level property names)} from the R4 snapshot.

    Choice types (`onset[x]`, `deceased[x]`, `multipleBirth[x]`) are expanded to the concrete
    names they can serialise as: `onsetDateTime`, `deceasedBoolean`, `multipleBirthInteger`, and
    so on. Without this, a builder that correctly writes `onsetDateTime` is reported as writing an
    element R4 does not define — a false positive that buries the real ones.
    """
    source = R4_DIR / "profiles-resources.json"
    if not source.exists():
        R4_DIR.mkdir(parents=True, exist_ok=True)
        subprocess.run(
            ["curl", "-sSL", "-o", str(R4_DIR / "definitions.json.zip"),
             "https://hl7.org/fhir/R4/definitions.json.zip"],
            check=True,
        )
        subprocess.run(["unzip", "-o", "-q", str(R4_DIR / "definitions.json.zip"), "-d", str(R4_DIR)], check=True)
    entries = json.loads(source.read_text(encoding="utf-8"))
    allowed = {}
    for entry in entries["entry"]:
        res = entry["resource"]
        if res.get("resourceType") != "StructureDefinition" or res.get("kind") != "resource":
            continue
        if not res.get("snapshot", {}).get("element"):
            continue
        nama = set()
        for el in res["snapshot"]["element"]:
            if not el["path"].startswith(res["id"] + ".") or el["path"].count(".") != 1:
                continue
            akar = el["path"].split(".")[1]
            if akar.endswith("[x]"):
                dasar = akar[:-3]
                # Tipe yang sah untuk elemen pilihan ini, dari daftar type pada definisinya.
                for tipe in el.get("type", []):
                    kode = tipe.get("code", "")
                    if kode and kode != "Extension":
                        nama.add(dasar + kode[0].upper() + kode[1:])
                nama.add(dasar[0].upper() + dasar[1:] if dasar else dasar)
                # Path dasarnya sendiri juga sah untuk pemakaian resource-specific.
                nama.add(dasar)
            else:
                nama.add(akar.replace("[x]", ""))
        allowed[res["id"]] = nama
    return allowed


def audit(allowed: dict) -> list:
    rows = []
    for path in sorted(BUILDER_DIR.glob("PayloadBuilder*.php")):
        text = path.read_text()
        match = re.search(r"resourceType\s*=\s*'([^']+)'", text)
        if not match:
            continue
        resource = match.group(1)
        written = sorted(set(re.findall(RETRIEVE, text)))
        known = allowed.get(resource)
        rows.append(
            {
                "builder": path.stem,
                "resourceType": resource,
                "in_r4": known is not None,
                "methods": len(set(re.findall(r"public function (\w+)\(", text)) - {"__construct", "build"}),
                "elements": written,
                "not_in_r4": [] if known is None else [e for e in written if e not in known],
            }
        )
    return rows


GENERIK = {"id", "identifier", "status", "code", "subject", "encounter"}
TUJUAN_TRIASE = Path(__file__).resolve().parent.parent / "docs" / "R4_CONFLICT_TRIAGE.md"


def render_triase(rows: list) -> str:
    """Dokumen triase: setiap builder yang menulis elemen asing, dengan kategori dan tindakan.

    Kategori dipisah karena tindakannya berbeda tajam:
      A. kebocoran generic setter — setter generik dipakai pada resource yang tidak punya elemennya
      B. elemen khas resource — perlu keputusan manusia per elemen
      C. resource yang R4 tidak pernah definisikan — di luar cakupan konformansi
    """
    bukan_r4 = [r for r in rows if not r["in_r4"]]
    asing = [r for r in rows if r["in_r4"] and r["not_in_r4"]]
    kat_a = [r for r in asing if set(r["not_in_r4"]) <= GENERIK]
    kat_b = [r for r in asing if set(r["not_in_r4"]) - GENERIK]
    bersih = [r for r in rows if r["in_r4"] and not r["not_in_r4"]]

    baris = [
        "# Triase konformansi R4 — builder kanon",
        "",
        "Dihasilkan `scripts/audit-r4-conformance.py --triage`; jangan disunting tangan.",
        "Elemen pilihan (`start[x]`, `deceased[x]`) sudah diperluas ke nama konkretnya, sehingga",
        "`deceasedBoolean` tidak lagi dilaporkan sebagai pelanggaran.",
        "",
        f"- builder: **{len(rows)}**",
        f"- bersih terhadap R4: **{len(bersih)}**",
        f"- kategori A (kebocoran setter generik): **{len(kat_a)}**",
        f"- kategori B (elemen khas resource): **{len(kat_b)}**",
        f"- kategori C (resource tidak ada di R4): **{len(bukan_r4)}**",
        "",
        "## Kategori C — resource di luar R4",
        "",
        "| Builder | Tindakan |",
        "| --- | --- |",
    ]
    for r in bukan_r4:
        baris.append(f"| `{r['builder']}` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |")

    baris += [
        "",
        "## Kategori A — setter generik pada resource yang tidak punya elemen itu",
        "",
        "Pola sama di semua baris: `setStatus`/`setCode`/`setSubject`/`setEncounter`/`addIdentifier`",
        "menulis elemen yang tidak ada di resource tersebut. Setter itu tetap berguna untuk resource",
        "lain, jadi yang salah adalah PEMAKAIANNYA di builder ini, bukan setter-nya.",
        "",
        "| Builder | Elemen asing |",
        "| --- | --- |",
    ]
    for r in kat_a:
        baris.append(f"| `{r['builder']}` | {', '.join('`'+e+'`' for e in r['not_in_r4'])} |")

    baris += [
        "",
        "## Kategori B — perlu keputusan per elemen",
        "",
        "Tidak ada perbaikan borongan di sini: sebagian elemen adalah nama yang salah, sebagian",
        "salinan dari versi FHIR lain (R5), sebagian lagi memang quirk yang dipertahankan demi",
        "kompatibilitas. Setiap baris harus diputuskan sendiri, dan keputusannya dicatat di sini.",
        "",
        "| Builder | Elemen asing | Status |",
        "| --- | --- | --- |",
    ]
    keputusan = {}
    berkas_keputusan = TUJUAN_TRIASE.parent / "r4_category_b_decisions.json"
    if berkas_keputusan.exists():
        keputusan = json.loads(berkas_keputusan.read_text(encoding="utf-8"))
    for r in kat_b:
        khusus = [e for e in r["not_in_r4"] if e not in GENERIK]
        diputuskan = keputusan.get(r["builder"], {})
        status = "; ".join(
            f"`{e}` {diputuskan[e]['status']}" if e in diputuskan else f"`{e}` belum diputuskan"
            for e in khusus
        )
        baris.append(f"| `{r['builder']}` | {', '.join('`'+e+'`' for e in khusus)} | {status} |")

    baris += [
        "",
        "## Catatan",
        "",
        "Bersih terhadap R4 berarti elemen tingkat atasnya ada di definisi resource — bukan berarti",
        "payload lolos validasi penuh (kardinalitas, tipe, binding). Untuk itu ada korpus contoh di",
        "`satusehat-conformance`.",
        "",
    ]
    return "\n".join(baris)


def main() -> int:
    rows = audit(load_definitions())
    if "--triage" in sys.argv:
        dokumen = render_triase(rows)
        if "--check" in sys.argv:
            lama = TUJUAN_TRIASE.read_text(encoding="utf-8") if TUJUAN_TRIASE.exists() else ""
            if lama != dokumen:
                print("docs/R4_CONFLICT_TRIAGE.md tidak sinkron — jalankan --triage")
                return 1
            print("triase sinkron")
            return 0
        TUJUAN_TRIASE.parent.mkdir(parents=True, exist_ok=True)
        TUJUAN_TRIASE.write_text(dokumen, encoding="utf-8")
        print(f"ditulis: {TUJUAN_TRIASE}")
        return 0
    if "--json" in sys.argv:
        print(json.dumps(rows, indent=2))
        return 0

    unknown_type = [r for r in rows if not r["in_r4"]]
    misplaced = [r for r in rows if r["not_in_r4"]]
    boilerplate = [
        r for r in rows
        if r["methods"] == 6 and set(r["elements"]) == {"id", "identifier", "status", "code", "subject", "encounter"}
    ]
    print(f"builders: {len(rows)}")
    print(f"resource type not defined by R4: {len(unknown_type)}")
    print(f"builders writing at least one element R4 does not define: {len(misplaced)}")
    print(f"boilerplate-only builders (the same six generic setters): {len(boilerplate)}")
    if "--list" in sys.argv:
        for r in unknown_type:
            print(f"  {r['resourceType']:30s} resource type absent from R4")
        for r in misplaced:
            print(f"  {r['resourceType']:30s} {', '.join(r['not_in_r4'])}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

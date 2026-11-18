#!/usr/bin/env python3
"""Extract the static terminology tables from src/Terminology into the Rust crate's JSON.

Only the classes that hold literal arrays are extracted. The others (Icd10, Loinc, Kptl*, …) are
Eloquent models over the host application's database; a crate cannot ship someone else's data, so
they stay PHP-only by design.

Usage (from the PHP repository root):
    python3 docs/scripts/extract-terminology-maps.py --out ../satusehat-integration-rs/crates/core/resources/terminology-maps.id.json
"""
from __future__ import annotations

import argparse
import json
import pathlib
import re

SOURCES = ["ImmunizationTerminology.php", "MedicationTerminology.php", "CompositionTerminology.php"]


def properties(text: str) -> list[str]:
    return re.findall(r"public array \$(\w+)\s*=\s*\[", text)


def parse_property(text: str, prop: str) -> dict:
    start = text.find(f"public array ${prop}")
    body = text[start:]
    nxt = re.search(r"\n    public (array|\$|function)", body[10:])
    body = body[: 10 + nxt.start()] if nxt else body

    out: dict = {}
    # Shape A: '93001282' => [ ['system' => …, 'code' => …, 'display' => …], … ]
    for key in re.finditer(r"'([A-Za-z0-9\-\.]+)'\s*=>\s*\[\s*\[", body):
        segment = body[key.end() - 1 :]
        end = re.search(r"\n        \]", segment)
        segment = segment[: end.start()] if end else segment
        codings = [
            {"system": s, "code": c, "display": d}
            for s, c, d in re.findall(
                r"'system'\s*=>\s*'([^']*)',\s*'code'\s*=>\s*'([^']*)',\s*'display'\s*=>\s*'([^']*)'",
                segment,
            )
        ]
        if codings:
            out[key.group(1)] = codings
    if out:
        return out

    # Shape B: 'BS003' => 'Aerosol Spray'
    for key in re.finditer(r"'([A-Za-z0-9\-\.]+)'\s*=>\s*'([^']*)'", body):
        out.setdefault(key.group(1), key.group(2))
    # Shape C: '11369-6' => ['system' => 'http://loinc.org', 'display' => '…']
    for key in re.finditer(
        r"'([A-Za-z0-9\-\.]+)'\s*=>\s*\[\s*'system'\s*=>\s*'([^']*)',\s*'display'\s*=>\s*'([^']*)'",
        body,
    ):
        out[key.group(1)] = {"system": key.group(2), "display": key.group(3)}
    return out


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--out", required=True, help="target JSON path in the Rust crate")
    args = ap.parse_args()

    source_dir = pathlib.Path(__file__).resolve().parents[2] / "src" / "Terminology"
    result: dict = {}
    for name in SOURCES:
        text = (source_dir / name).read_text(encoding="utf-8")
        stem = name.replace("Terminology.php", "").lower()
        for prop in properties(text):
            table = parse_property(text, prop)
            if table:
                result[f"{stem}.{prop}"] = table
                print(f"{stem}.{prop}: {len(table)} keys")

    out_path = pathlib.Path(args.out)
    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text(json.dumps(result, ensure_ascii=False, indent=1), encoding="utf-8")
    print(f"wrote {out_path} — {sum(len(v) for v in result.values())} keys in {len(result)} maps")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

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

R4_DIR = Path(os.environ.get("R4_DIR", "/tmp/r4def"))
BUILDER_DIR = Path(__file__).resolve().parent.parent / "src" / "Builder"
RETRIEVE = r"->(?:set|push|setCodeable)\(\s*'([A-Za-z][A-Za-z0-9]*)'"


def load_definitions() -> dict:
    """Return {resourceType: set(allowed top-level property names)} from the R4 snapshot."""
    source = R4_DIR / "profiles-resources.json"
    if not source.exists():
        R4_DIR.mkdir(parents=True, exist_ok=True)
        subprocess.run(
            ["curl", "-sSL", "-o", str(R4_DIR / "definitions.json.zip"),
             "https://hl7.org/fhir/R4/definitions.json.zip"],
            check=True,
        )
        subprocess.run(["unzip", "-o", "-q", str(R4_DIR / "definitions.json.zip"), "-d", str(R4_DIR)], check=True)
    entries = json.loads(source.read_text())
    allowed = {}
    for entry in entries["entry"]:
        res = entry["resource"]
        if res.get("resourceType") != "StructureDefinition" or res.get("kind") != "resource":
            continue
        if not res.get("snapshot", {}).get("element"):
            continue
        allowed[res["id"]] = {
            el["path"].split(".")[1].replace("[x]", "")
            for el in res["snapshot"]["element"]
            if el["path"].startswith(res["id"] + ".") and el["path"].count(".") == 1
        }
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


def main() -> int:
    rows = audit(load_definitions())
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

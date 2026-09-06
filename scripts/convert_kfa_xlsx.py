#!/usr/bin/env python3
"""Convert KFA alkes + BZA xlsx exports to SDK-style semicolon CSVs.

Alkes rows = product variants (66k), keyed by product_template.kfa_code (82xxxxxx).
BZA rows  = bahan zat aktif (24k), kode 91xxxxxx.

Output columns mirror the existing kfa.csv (obat) layout where sensible,
plus alkes/BZA-specific fields.
"""
import csv
import sys

import openpyxl


def norm(v):
    if v is None:
        return ""
    s = str(v).strip()
    return s if s not in ("/", "False", "None", "[]") else ""


def norm_int(v):
    s = norm(v)
    return s


def to_bool(v):
    s = norm(v)
    if s == "":
        return ""
    return "TRUE" if s.lower() in ("true", "1", "yes", "y") else "FALSE"


def sheet_rows(path):
    wb = openpyxl.load_workbook(path, read_only=True)
    ws = wb[wb.sheetnames[0]]
    it = ws.iter_rows(values_only=True)
    header = [str(h) if h is not None else "" for h in next(it)]
    idx = {h: i for i, h in enumerate(header)}
    for r in it:
        yield dict(zip(header, r)), idx


def write_csv(path, header, rows):
    with open(path, "w", encoding="utf-8", newline="") as f:
        w = csv.writer(f, delimiter=";", lineterminator="\n")
        w.writerow(header)
        w.writerows(rows)
    print(f"Wrote {path}: {len(rows)} rows")


def main():
    alkes_xlsx = sys.argv[1] if len(sys.argv) > 1 else "/home/openclaw/.hermes/cache/documents/doc_439746f05a61_kfa_alkes.xlsx"
    bza_xlsx = sys.argv[2] if len(sys.argv) > 2 else "/home/openclaw/.hermes/cache/documents/doc_800932fcc43b_kfa_farmasi_bza.xlsx"
    outdir = sys.argv[3] if len(sys.argv) > 3 else "/home/openclaw/projects/satusehat-integration/database/seeders/csv"

    # ── ALKES ──────────────────────────────────────────────────────────────
    # Row = produk varian. Kolom kunci:
    #   name                          → nama produk (varian + merk)
    #   product_template.kfa_code     → kode alkes (82xxxxxx) utk mapping/search
    #   product_template.display_name → nama generik template
    #   nama_dagang / brand           → merk
    header_alkes = [
        "kfa_code",          # product_template.kfa_code (82xxxxxx)
        "product_template",  # product_template.display_name
        "display_name",      # name (varian produk, paling deskriptif)
        "brand",             # nama_dagang
        "nie",               # izin edar
        "manufacturer",      # manufacturer / registrar
        "type_code",         # farmalkes_type.code (device/pkrt)
        "type_name",         # farmalkes_type.name (Alat Kesehatan/...)
        "type_group",        # farmalkes_type.group (alkes)
        "uom",               # uom.name
        "dosage_form",       # dosage_form.name
        "net_weight",        # net_weight
        "net_weight_uom",    # net_weight_uom_name
        "volume",            # volume
        "volume_uom",        # volume_uom_name
        "het_price",         # het_price
        "fix_price",         # fix_price
        "active",            # active
        "status",            # state
    ]
    alkes_rows = []
    seen = set()
    for r, idx in sheet_rows(alkes_xlsx):
        tpl_code = norm(r.get("product_template.kfa_code"))
        if not tpl_code or tpl_code in ("product_template.kfa_code",):
            continue
        key = tpl_code
        # baris varian digabung: display_name diambil dari kolom 'name' yang
        # paling deskriptif (termasuk merk); bila kosong pakai template name.
        nm = norm(r.get("name"))
        if not nm:
            nm = norm(r.get("product_template.name"))
        dagang = norm(r.get("nama_dagang"))
        disp = nm or norm(r.get("product_template.display_name"))
        row = [
            tpl_code,
            norm(r.get("product_template.display_name")) or norm(r.get("product_template.name")),
            disp,
            dagang or norm(r.get("brand")),
            norm(r.get("nie")) or norm(r.get("izin_edar")),
            norm(r.get("manufacturer")) or norm(r.get("registrar")),
            norm(r.get("farmalkes_type.code")),
            norm(r.get("farmalkes_type.name")),
            norm(r.get("farmalkes_type.group")),
            norm(r.get("uom.name")),
            norm(r.get("dosage_form.name")),
            norm(r.get("net_weight")),
            norm(r.get("net_weight_uom_name")),
            norm(r.get("volume")),
            norm(r.get("volume_uom_name")),
            norm(r.get("het_price")),
            norm(r.get("fix_price")),
            to_bool(r.get("active")),
            norm(r.get("state")) or "valid",
        ]
        alkes_rows.append(row)
    # sort by kode
    alkes_rows.sort(key=lambda x: x[0])
    write_csv(f"{outdir}/kfa_alkes.csv.stub", header_alkes, alkes_rows)

    # ── BZA ────────────────────────────────────────────────────────────────
    header_bza = [
        "kfa_code",          # 91xxxxxx
        "zat_aktif",         # bahan aktif
        "kekuatan",          # kekuatan zat aktif
        "name",              # nama produk
        "active",
        "status",
    ]
    bza_rows = []
    for r, idx in sheet_rows(bza_xlsx):
        code = norm(r.get("kfa_code"))
        if not code:
            continue
        bza_rows.append([
            code,
            norm(r.get("zat_aktif")),
            norm(r.get("kekuatan_zat_aktif")) or norm(r.get("kekuatan")),
            norm(r.get("name")),
            to_bool(r.get("active")),
            norm(r.get("state")) or "valid",
        ])
    bza_rows.sort(key=lambda x: x[0])
    write_csv(f"{outdir}/kfa_bza.csv.stub", header_bza, bza_rows)


if __name__ == "__main__":
    main()

#!/usr/bin/env bash
# Regenerasi SYNC_MANIFEST.json keempat SDK SATUSEHAT dari ANGKA TERUKUR (bukan ingatan).
# Pakai: bash scripts/parity-manifest.sh          -> cetak JSON ke layar
#        bash scripts/parity-manifest.sh --write   -> tulis ke tiap checkout sibling
set -euo pipefail
ROOT=${SATUSEHAT_SIBLINGS:-$HOME/projects}
PHP_R=$ROOT/satusehat-integration
ND_R=$ROOT/satusehat-integration-node
PY_R=$ROOT/satusehat-integration-python
GO_R=$ROOT/satusehat-integration-go/v4
RS_R=$ROOT/satusehat-integration-rs
for d in "$PHP_R" "$ND_R" "$PY_R" "$GO_R" "$RS_R"; do
  [ -d "$d" ] || { echo "checkout tidak ada: $d" >&2; exit 2; }
done
c_php=$(ls "$PHP_R"/src/Builder/PayloadBuilder*.php | wc -l)
# Node menamai kelas builder sama dengan resource (Patient, Encounter), bukan PatientBuilder.
c_nd=$(grep -rhoE "export class [A-Za-z0-9_]+" "$ND_R"/src/builder/*.ts | wc -l)
c_py=$(grep -rhoE "^class [A-Za-z0-9_]+Builder" "$PY_R"/src/builder --include="*.py" --exclude="test_builders.py" --exclude="base_builder.py" | wc -l)
c_go=$(grep -rhoE "^type [A-Za-z0-9_]+Builder struct" "$GO_R"/src/builder/*.go | wc -l)
c_rs=$(find "$RS_R"/crates/core/src/builder -name "*.rs" ! -name "mod.rs" | wc -l)
d_php=$(ls "$PHP_R"/src/DataType/*.php | grep -v "/DataType\.php$" | wc -l)
d_nd=$(grep -rhoE "export class [A-Za-z0-9_]+" "$ND_R"/src/datatype/datatype.ts "$ND_R"/src/datatype/datatypes.ts | wc -l)
d_py=$(grep -rhoE "^class [A-Za-z0-9_]+" "$PY_R"/src/datatype --include="*.py" --exclude="test_*.py" | wc -l)
d_go=$(grep -rhoE "^type [A-Za-z0-9_]+ struct" "$GO_R"/src/datatype/*.go | wc -l)
d_rs=$(grep -cE "^pub fn " "$RS_R"/crates/core/src/types.rs)
t_php=$(ls "$PHP_R"/src/Terminology/*.php | grep -viE "/Resolver\.php$|/TerminologyClient\.php$" | wc -l)
t_nd=$(ls "$ND_R"/src/terminology/*.ts | grep -viE "/index\.ts$|/resolver\.ts$|/terminologyclient\.ts$|\.test\.ts$" | wc -l)
t_py=$(ls "$PY_R"/src/terminology/*.py | grep -viE "/__init__\.py$|/base\.py$|/resolver\.py$|/terminology_client\.py$|/terminology_query_builder\.py$|/test_.*\.py$" | wc -l)
t_go=$(ls "$GO_R"/src/terminology/*.go | grep -viE "/resolver\.go$|/terminology_client\.go$|/terminology_query_builder\.go$|_test\.go$" | wc -l)
t_rs=$(ls "$RS_R"/crates/core/src/terminology.rs | wc -l)
q_php=$(ls "$PHP_R"/src/Queue/*.php | wc -l)
q_nd=$(ls "$ND_R"/src/queue/*.ts | grep -vE '(index\.ts|\.test\.ts)$' | wc -l)
q_py=$(ls "$PY_R"/src/queue/*.py | grep -vE '(__init__\.py|test_.*\.py)$' | wc -l)
q_go=$(ls "$GO_R"/src/queue/*.go | grep -vE '_test\.go$' | wc -l)
q_rs=$(ls "$RS_R"/crates/client/src/journal.rs 2>/dev/null | wc -l)
p_php=$(ls "$PHP_R"/src/Parser/*Parser.php 2>/dev/null | wc -l)
p_nd=$(ls "$ND_R"/src/parser/*Parser.ts 2>/dev/null | wc -l)
p_py=$(ls "$PY_R"/src/parser/*_parser.py 2>/dev/null | wc -l)
p_go=$(ls "$GO_R"/src/parser/*_parser.go 2>/dev/null | wc -l)
p_rs=0   # Rust belum punya parser FHIR
x_php=$(find "$PHP_R"/tests -name "*Test.php" | wc -l)
x_nd=$(find "$ND_R"/src -name "*.test.ts" | wc -l)
x_py=$(find "$PY_R"/src -name "test_*.py" | wc -l)
x_go=$(find "$GO_R"/src -name "*_test.go" | wc -l)
x_rs=$(grep -rho "#\[test\]" "$RS_R"/crates | wc -l)
go_total=$(grep -rhoE "^func \(b \*[A-Za-z]+\) [A-Za-z]+" "$GO_R"/src/builder/*.go | wc -l)
go_exported=$(grep -rhoE "^func \(b \*[A-Za-z]+\) [A-Z][A-Za-z]*" "$GO_R"/src/builder/*.go | wc -l)
has_file() {
  local dir="$1" pat1="$2" pat2="$3"
  if [ -n "$(find "$dir"/src \( -iname "$pat1" -o -iname "$pat2" \) 2>/dev/null | head -n 1)" ]; then
    printf "present"
  else
    printf "absent"
  fi
}
hs_php=$(has_file "$PHP_R" "*ssrequest*" "*ss_request*")
hs_nd=$(has_file "$ND_R" "*ssrequest*" "*ss_request*")
hs_py=$(has_file "$PY_R" "*ssrequest*" "*ss_request*")
hs_go=$(has_file "$GO_R" "*ssrequest*" "*ss_request*")
rv_php=$(has_file "$PHP_R" "*ssvalidationerror*" "*ss_validation_error*")
rv_nd=$(has_file "$ND_R" "*ssvalidationerror*" "*ss_validation_error*")
rv_py=$(has_file "$PY_R" "*ssvalidationerror*" "*ss_validation_error*")
rv_go=$(has_file "$GO_R" "*ssvalidationerror*" "*ss_validation_error*")
fp_php=$(has_file "$PHP_R" "*patientparser*" "*patient_parser*")
fp_nd=$(has_file "$ND_R" "*patientparser*" "*patient_parser*")
fp_py=$(has_file "$PY_R" "*patientparser*" "*patient_parser*")
fp_go=$(has_file "$GO_R" "*patientparser*" "*patient_parser*")
hs_rs=$( [ -n "$(find "$RS_R"/crates -iname "*transport*" -o -iname "*submit*" | head -1)" ] && echo present || echo absent )
rv_rs=$( [ -n "$(find "$RS_R"/crates -iname "*report*" -o -iname "*validate*" | head -1)" ] && echo present || echo absent )
fp_rs="absent"
go_total=$(grep -rhoE "^func \(b \*[A-Za-z]+\) [A-Za-z]+" "$GO_R"/src/builder/*.go | wc -l)
go_exported=$(grep -rhoE "^func \(b \*[A-Za-z]+\) [A-Z][A-Za-z]*" "$GO_R"/src/builder/*.go | wc -l)
sha=$(git -C "$PHP_R" rev-parse --short HEAD)
bcap() { if [ "${2:-0}" -ge "${1:-1}" ]; then echo present; elif [ "${2:-0}" -gt 0 ]; then echo partial; else echo absent; fi; }

json() {
  printf '{\n'
  printf '  "schema": 3,\n'
  printf '  "measured_at": "%s",\n' "$(date +%Y-%m-%d)"
  printf '  "measured_by": "scripts/parity-manifest.sh (repo satusehat-integration)",\n'
  printf '  "php_last_commit": "%s",\n' "$sha"
  printf '  "counts": {\n'
  printf '    "payload_builder":   { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s },\n' "$c_php" "$c_nd" "$c_py" "$c_go" "$c_rs"
  printf '    "datatype":          { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s },\n' "$d_php" "$d_nd" "$d_py" "$d_go" "$d_rs"
  printf '    "terminology_files": { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s },\n' "$t_php" "$t_nd" "$t_py" "$t_go" "$t_rs"
  printf '    "queue_files":       { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s },\n' "$q_php" "$q_nd" "$q_py" "$q_go" "$q_rs"
  printf '    "parser_files":      { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s },\n' "$p_php" "$p_nd" "$p_py" "$p_go" "$p_rs"
  printf '    "test_files":        { "php": %s, "node": %s, "python": %s, "go": %s, "rust": %s }\n' "$x_php" "$x_nd" "$x_py" "$x_go" "$x_rs"
  printf '  },\n'
  printf '  "capabilities": {\n'
  printf '    "payload_builder":     { "php": "present", "node": "%s", "python": "%s", "go": "%s", "rust": "%s" },\n' "$(bcap "$c_php" "$c_nd")" "$(bcap "$c_php" "$c_py")" "$(bcap "$c_php" "$c_go")" "$(bcap "$c_php" "$c_rs")"
  printf '    "http_send":           { "php": "%s", "node": "%s", "python": "%s", "go": "%s", "rust": "%s" },\n' "$hs_php" "$hs_nd" "$hs_py" "$hs_go" "$hs_rs"
  printf '    "response_validation": { "php": "%s", "node": "%s", "python": "%s", "go": "%s", "rust": "%s" },\n' "$rv_php" "$rv_nd" "$rv_py" "$rv_go" "$rv_rs"
  printf '    "fhir_parser":         { "php": "%s", "node": "%s", "python": "%s", "go": "%s", "rust": "%s" },\n' "$fp_php" "$fp_nd" "$fp_py" "$fp_go" "$fp_rs"
  printf '    "terminology_client":  { "php": "%s", "node": "%s", "python": "%s", "go": "%s", "rust": "%s" }\n' "$(bcap 1 "$t_php")" "$(bcap 1 "$t_nd")" "$(bcap 1 "$t_py")" "$(bcap 1 "$t_go")" "$(bcap 1 "$t_rs")"
  printf '  },\n'
  printf '  "defects": [\n'
  printf '    { "language": "go", "issue": "metode builder tidak diekspor", "measured": "%s dari %s metode berhuruf kecil", "impact": "%s metode dapat dipanggil dari luar paket" }\n' "$((go_total-go_exported))" "$go_total" "$go_exported"
  printf '  ],\n'
  printf '  "notes": "Semua angka diukur find/grep pada checkout masing-masing. absent/partial bukan penilaian kualitas. Lihat docs/PARITY.md."\n'
  printf '}\n'
}
if [ "${1:-}" = "--write" ]; then
  for target in "$PHP_R/SYNC_MANIFEST.json" "$ND_R/SYNC_MANIFEST.json" "$PY_R/SYNC_MANIFEST.json" "$GO_R/SYNC_MANIFEST.json"; do
    json > "$target" && echo "ditulis: $target"
  done
else
  json
fi

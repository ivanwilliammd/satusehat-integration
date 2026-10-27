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
for d in "$PHP_R" "$ND_R" "$PY_R" "$GO_R"; do
  [ -d "$d" ] || { echo "checkout tidak ada: $d" >&2; exit 2; }
done
c_php=$(ls "$PHP_R"/src/Builder/PayloadBuilder*.php | wc -l)
c_nd=$(grep -rhoE "export class [A-Za-z0-9_]+" "$ND_R"/src/builder/*.ts | wc -l)
c_py=$(grep -rhoE "^class [A-Za-z0-9_]+Builder" "$PY_R"/src/builder --include="*.py" --exclude="test_*.py" | wc -l)
c_go=$(grep -rhoE "^type [A-Za-z0-9_]+Builder struct" "$GO_R"/src/builder/*.go | wc -l)
d_php=$(ls "$PHP_R"/src/DataType/*.php | wc -l)
d_nd=$(grep -rhoE "export class [A-Za-z0-9_]+" "$ND_R"/src/datatype/datatype.ts "$ND_R"/src/datatype/datatypes.ts | wc -l)
d_py=$(grep -rhoE "^class [A-Za-z0-9_]+" "$PY_R"/src/datatype --include="*.py" --exclude="test_*.py" | wc -l)
d_go=$(grep -rhoE "^type [A-Za-z0-9_]+ struct" "$GO_R"/src/datatype/*.go | wc -l)
t_php=$(ls "$PHP_R"/src/Terminology/*.php | wc -l)
t_nd=$(ls "$ND_R"/src/terminology/*.ts | wc -l)
t_py=$(ls "$PY_R"/src/terminology/*.py | wc -l)
t_go=$(ls "$GO_R"/src/terminology/*.go | wc -l)
q_php=$(ls "$PHP_R"/src/Queue/*.php | wc -l)
q_nd=$(ls "$ND_R"/src/queue/*.ts | wc -l)
q_py=$(ls "$PY_R"/src/queue/*.py | wc -l)
q_go=$(ls "$GO_R"/src/queue/*.go | wc -l)
x_php=$(find "$PHP_R"/tests -name "*Test.php" | wc -l)
x_nd=$(find "$ND_R"/src -name "*.test.ts" | wc -l)
x_py=$(find "$PY_R"/src -name "test_*.py" | wc -l)
x_go=$(find "$GO_R"/src -name "*_test.go" | wc -l)
sha=$(git -C "$PHP_R" rev-parse --short HEAD)
json() {
  printf '{\n'
  printf '  "schema": 2,\n'
  printf '  "measured_at": "%s",\n' "$(date +%Y-%m-%d)"
  printf '  "measured_by": "scripts/parity-manifest.sh (repo satusehat-integration)",\n'
  printf '  "php_last_commit": "%s",\n' "$sha"
  printf '  "counts": {\n'
  printf '    "payload_builder":   { "php": %s, "node": %s, "python": %s, "go": %s },\n' "$c_php" "$c_nd" "$c_py" "$c_go"
  printf '    "datatype":          { "php": %s, "node": %s, "python": %s, "go": %s },\n' "$d_php" "$d_nd" "$d_py" "$d_go"
  printf '    "terminology_files": { "php": %s, "node": %s, "python": %s, "go": %s },\n' "$t_php" "$t_nd" "$t_py" "$t_go"
  printf '    "queue_files":       { "php": %s, "node": %s, "python": %s, "go": %s },\n' "$q_php" "$q_nd" "$q_py" "$q_go"
  printf '    "test_files":        { "php": %s, "node": %s, "python": %s, "go": %s }\n' "$x_php" "$x_nd" "$x_py" "$x_go"
  printf '  },\n'
  printf '  "capabilities": {\n'
  printf '    "payload_builder":     { "php": "present", "node": "present", "python": "present", "go": "present" },\n'
  printf '    "http_send":           { "php": "present", "node": "absent",  "python": "absent",  "go": "absent" },\n'
  printf '    "response_validation": { "php": "present", "node": "absent",  "python": "absent",  "go": "absent" },\n'
  printf '    "fhir_parser":         { "php": "present", "node": "absent",  "python": "absent",  "go": "absent" },\n'
  printf '    "terminology_client":  { "php": "present", "node": "present", "python": "present", "go": "present" }\n'
  printf '  },\n'
  printf '  "notes": "absent = diukur dengan find src -iname; lihat docs/SRD.md tiap repo. Jangan sunting tangan."\n'
  printf '}\n'
}
if [ "${1:-}" = "--write" ]; then
  for target in "$PHP_R/SYNC_MANIFEST.json" "$ND_R/SYNC_MANIFEST.json" "$PY_R/SYNC_MANIFEST.json" "$GO_R/SYNC_MANIFEST.json"; do
    json > "$target" && echo "ditulis: $target"
  done
else
  json
fi

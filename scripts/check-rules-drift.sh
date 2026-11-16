#!/usr/bin/env bash
# Periksa apakah berkas aturan wajib SATUSEHAT masih sama di kelima port.
#
# Aturan itu satu sumber kebenaran tetapi harus ikut di tiap paket: PHP & Python membacanya dari
# `data/`, Node mengimpornya, Go memakai go:embed, Rust memakai include_str!. Berkas yang menyimpang
# berarti satu port memeriksa aturan yang berbeda — kesalahan yang tidak akan tertangkap uji mana pun di
# repo masing-masing.
#
# Repo yang tidak ada di mesin ini dilewati (CI hanya checkout satu repo), jadi skrip ini aman dipanggil
# dari mana saja. Keluar 1 hanya bila dua repo yang sama-sama ada berbeda isinya.
set -euo pipefail

AKAR="${SATUSEHAT_PORT_ROOT:-$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)}"

declare -A LOKASI=(
  [php]="satusehat-integration/data/satusehat/required-rules.json"
  [node]="satusehat-integration-node/src/data/satusehat/required-rules.json"
  [python]="satusehat-integration-python/data/satusehat/required-rules.json"
  [go]="satusehat-integration-go/v4/src/terminology/data/satusehat/required-rules.json"
  [rust]="satusehat-integration-rs/crates/core/src/data/satusehat/required-rules.json"
)

acu=""
acu_nama=""
beda=0

for port in php node python go rust; do
  berkas="$AKAR/${LOKASI[$port]}"
  if [[ ! -f "$berkas" ]]; then
    echo "lewat  $port  (tidak ada di mesin ini)"
    continue
  fi
  sha="$(sha256sum "$berkas" | cut -c1-16)"
  if [[ -z "$acu" ]]; then
    acu="$sha"
    acu_nama="$port"
    echo "acu    $port  $sha"
  elif [[ "$sha" != "$acu" ]]; then
    echo "BEDA   $port  $sha  (acu $acu_nama $acu)"
    beda=1
  else
    echo "sama   $port  $sha"
  fi
done

exit "$beda"

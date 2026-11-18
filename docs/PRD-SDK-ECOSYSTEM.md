# PRD — SATUSEHAT SDK ecosystem (PHP · Node/TS · Python · Go · Rust)

Status: v1.2 · 2026-11-17 · Product owner: Ivan William Harsono
This is the **only** cross-language PRD. Per-language SRDs are separate: `docs/SRD.md` (PHP, this
repository), and `docs/SRD.md` in `satusehat-integration-node`, `-python`, `-go`, `-rs`.

## 1. Shared goal

Five SDKs, one behaviour: installing the package in any language must give the same ability to build
SATUSEHAT FHIR R4 payloads, send them with a token that is never stale, and read validation errors in
human language. Version numbers move together; whatever still differs must be **written down** in
section 4 rather than discovered by a user.

## 2. Measured state (2026-11-17)

| | PHP | Node/TS | Python | Go | Rust |
|---|---|---|---|---|---|
| Package | `ivanwilliammd/satusehat-integration` | `@ivanwilliammd/satusehat-integration` | `satusehat-integration` (PyPI) | `github.com/ivanwilliammd/satusehat-integration-go/v4` | `satusehat-integration` (crates.io) |
| Latest version | 4.19.1 | 4.19.1 | 4.19.1 | v4.19.1 | 4.19.1 |
| Registry | Packagist | npm | PyPI | proxy.golang.org | crates.io |
| Payload builders | 141 `PayloadBuilder*` | 141 | 141 | 141 | 137 modules (141 of 141 names) |
| Datatypes | 33 | 33 | 33 | 33 | 33 |
| HTTP send + OAuth2 | `OAuth2Client` + `SSRequest` | same | same | same | `client` crate |
| Response validation (558 rules) | `SSResponse`, `BundleResponse`, `SSValidationError` | same | same | same | `report` + `validate` |
| FHIR parsers | 142 | 142 | 141 | 142 | 136 |
| Persistent queue | 8 modules | 8 modules | 8 modules | 8 modules | 1 module |
| Terminology client | 23 modules | 23 modules | 24 modules | 23 modules | 1 module |
| Tests | 1.933 (4.672 assertions) | 1.279 | 2.219 (+1 skipped) | 555 runs / 494 functions | 381 |
| Parity tracker | `SYNC_MANIFEST.json` | same | same | `v4/SYNC_MANIFEST.json` | same |

Readers should treat the columns as **measured**, not as a claim of equality: the counts come from
`scripts/parity-manifest.sh`, and `docs/PARITY.md` explains every difference that remains.

## 3. Shared functional requirements

| ID | Requirement | PHP | Node | Python | Go | Rust |
|---|---|---|---|---|---|---|
| SDK-01 | Per-resource payload builder (`build()` / `json()`) | yes (141) | yes (141) | yes (141) | yes (141) | yes (136 FHIR R4) |
| SDK-02 | Typed `DataType` (Coding, HumanName, Reference, Quantity, …) | yes | yes | yes | yes | partial (12) |
| SDK-03 | HTTP: GET/POST/PUT/PATCH/DELETE + Bundle (`SSRequest`) | yes | yes | yes | yes | yes (`submit_bundle`) |
| SDK-04 | OAuth2 client_credentials, refreshed on 401 (`OAuth2Client`) | yes | yes | yes | yes | yes |
| SDK-05 | Response wrapper + 558 validation rule codes | yes | yes | yes | yes | yes |
| SDK-06 | Persistent queue (pending/failed/dlq) + worker + rate limiter | yes (8) | yes (8) | yes (8) | yes (8) | partial (1) |
| SDK-07 | SmarTerm terminology client (PAT / OIDC client_credentials) | yes | yes | yes | yes | yes |
| SDK-08 | Master data CSV + Laravel seeder | **yes (only PHP)** | no | no | no | no |
| SDK-09 | FHIR parser per resource | yes (142) | yes (142) | yes (141) | yes (142) | yes (136) |
| SDK-10 | Parity tracker that is actually regenerated (`SYNC_MANIFEST.json`) | yes | yes | yes | yes | yes |
| SDK-11 | SATUSEHAT required rules as data, with a fingerprint test | yes | yes | yes | yes | yes |
| SDK-12 | Non-standard SATUSEHAT identifiers and extensions as constants | yes | yes | yes | yes | yes |
| SDK-13 | Opt-in strict R4 element guard | yes | yes | yes | yes | no |

## 4. Deliberate differences (not defects)

| Aspect | PHP | Node/TS | Python | Go | Rust |
|---|---|---|---|---|---|
| Call style | `new PayloadBuilderXxx()` then chain | `XxxBuilder` class + factory | `XxxBuilder` (snake_case and camelCase) | struct + chain methods | typed input struct + `Builder::xxx` |
| File naming | one file per class | `src/builder/*.ts` (kebab-case) | `src/builder/*.py` (snake_case) | `v4/` package per module | one module per resource |
| Queue | SQLite PDO | SQLite `better-sqlite3` | SQLite `sqlite3` | durable JSON / in-memory, no CGo | JSON journal |
| Master data | CSV + Laravel seeder | via `TerminologyClient` | via `TerminologyClient` | via `TerminologyClient` | via terminology client |
| Legacy path | `src/FHIR/*` for 3.x callers | none | none | none | none |
| Minimum runtime | PHP 8.0 | Node 20 | Python 3.10 | Go 1.21 | stable Rust |

Rust stays pre-1.0 because SDK-02, SDK-06 and SDK-13 are not yet equal; the version number says so
instead of hiding it.

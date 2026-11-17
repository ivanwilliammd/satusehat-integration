# PRD — satusehat-integration (PHP SDK for SATUSEHAT FHIR R4)

Status: v2.0 · 2026-11-17 · Product owner: Ivan William Harsono
Technical companion: `docs/SRD.md`. Source of truth for code: `src/`, `database/`, `tests/`,
`CHANGELOG.md`.

## 1. Executive summary

`satusehat-integration` is an open-source (MIT) PHP library for building FHIR R4 payloads and calling
SATUSEHAT, the national health data platform of the Indonesian Ministry of Health. It is a **native
client**: it assembles resources, signs OAuth2 requests, and reads responses. It is **not** a FHIR
server and stores no clinical data.

Measured 2026-11-17 (file counts in this repository):

| Part | Count |
|---|---|
| `src/Builder/` (141 `PayloadBuilder*` + the abstract `Builder`) | 142 files |
| `src/DataType/` (FHIR value objects) | 34 classes |
| `src/FHIR/` (legacy resources, compatibility path) | 50 classes |
| `src/Parser/` | 142 classes |
| `src/Terminology/` | 24 classes |
| `src/Queue/` (SQLite queue: worker, DLQ, rate limit) | 8 classes |
| `src/Console/Commands/` | 3 commands |
| `database/seeders/csv/*.stub` (master data) | 18 files |
| Test methods / assertions | 1.933 / 4.672, 0 failing |

## 2. Background and problem

SATUSEHAT integration in Indonesian hospitals keeps repeating the same work: assembling FHIR JSON that
passes Ministry validation, managing OAuth2 tokens that expire mid-send, and guessing what a numeric
validation code means.

The problems this library answers:

1. **FHIR shapes are expensive to write by hand.** A valid `Encounter` needs dozens of nested elements;
   hand-written arrays produce validation errors that say nothing useful.
2. **Tokens interrupt the flow.** SATUSEHAT tokens are short-lived; a send that runs for hours fails
   when the token dies in the middle.
3. **Error codes are unreadable.** The platform answers with rule numbers, not sentences.
4. **Sends disappear without a trace.** Sending to a facility with a poor network needs a queue,
   retries, and a record; `try/catch` in a controller is not enough.
5. **National master data is large.** ICD-10, ICD-9-CM, KFA, KPTL, LOINC, SNOMED, UCUM, and region
   codes: copying them by hand into every application means drift between institutions.

## 3. Users and personas

| Persona | Primary need |
|---|---|
| Hospital/clinic application developer (PHP/Laravel) | Assemble valid FHIR resources without memorising the specification |
| Integration / interoperability engineer | Control tokens, retries, and delivery idempotency |
| Data analyst / clinical coder | National master data that can be seeded and reused |
| Maintainer of derivative products (v1.emr, fusion, claim) | One library shared by several repositories |

## 4. Goals and success metrics

| Goal | Metric |
|---|---|
| Valid payloads without hand-written arrays | Every resource has a `PayloadBuilderXxx` with `json()`/`build()` |
| Sends survive token expiry | `SSRequest` refreshes the token itself and retries once |
| Errors are actionable | Rule codes mapped to human messages (`SSValidationError`) |
| No lost sends | Persistent queue + DLQ + attempt counter |
| Master data ready to use | 18 CSV files + seeders, one command |
| Cross-language parity | PHP/Node/Python/Go share one release number; Rust carries its own while it is not equal |

## 5. Scope

**In scope**

- FHIR R4 payload builders (142 classes) and `DataType` (34) as structured values.
- HTTP client `SSRequest`/`SSResponse`: GET/POST/PUT/PATCH/DELETE, FHIRPath PATCH, `postBundle`.
- OAuth2 client_credentials against SATUSEHAT; token stored and refreshed automatically.
- Persistent SQLite queue: `EnqueueSatusehat`, `ProcessSatusehatQueue`, `QueueStatusSatusehat`, worker,
  DLQ, rate limiter, monitor.
- Mapping of 558 validation rule codes and 14 error response shapes.
- Master data (18 CSVs) + seeders + `.stub` migration for applications that use them.
- `TerminologyClient`: client for SmarTerm (`terminology.ivanmd.id`) — Sanctum PAT **or** OIDC
  client_credentials with automatic refresh.
- `TenantCredentialResolver`: per-tenant credentials (env / array / database) for multitenant apps.
- `KYC`: RSA key generation + encryption for the SATUSEHAT identity verification flow.

**Out of scope**

- A FHIR server: this library serves no `CapabilityStatement`, enforces no FHIR search (`_include`,
  `searchset`), and stores no patient data. That role belongs to `v1.emr` and `terminology`.
- Full validation against the official StructureDefinitions; that validation happens on the SATUSEHAT
  side.
- Any web interface or dashboard: everything goes through the CLI or the calling code.

## 6. Functional requirements

Status is measured from files in the repository, not from intent.

| ID | Requirement | Status |
|---|---|---|
| FR-01 | Every FHIR resource has a fluent builder producing a send-ready array | Present: 141 `PayloadBuilder*` classes |
| FR-02 | Complex FHIR types as structured objects, recursive `toArray()`, nulls dropped | Present: 34 `DataType` classes |
| FR-03 | Builders accept typed objects and plain strings; references get a resource-type prefix | Present |
| FR-04 | Invalid `status`/`intent` rejected at build time, not at send time | Present (`InvalidArgumentException`) |
| FR-05 | HTTP client with exponential retry, `429`/`Retry-After` respected, token refresh on `401` | Present: `SSRequest` |
| FR-06 | Response wrapped: success, error message, resource id | Present: `SSResponse` |
| FR-07 | Validation rule codes mapped to messages | Present: `SSValidationError` (558 rules, 14 response types) |
| FR-08 | Persistent queue with retries, DLQ, rate limiter, monitoring | Present: `src/Queue` (8 classes) + 3 artisan commands |
| FR-09 | National master data seedable from CSV | Present: 18 CSVs + seeders + `.stub` migration |
| FR-10 | Terminology client (SmarTerm) with two auth modes | Present: `TerminologyClient` |
| FR-11 | Per-tenant credentials without copying code | Present: `TenantCredentialResolver` |
| FR-12 | Laravel support (service provider, config, migrations) and plain PHP | Present: `SatusehatIntegrationServiceProvider` |
| FR-13 | Every path above has automated tests | Present: 1.933 test methods |
| FR-14 | Behavioural parity with the Node/Python/Go/Rust SDKs | Active: `docs/PARITY.md` plus the conformance corpus |

## 7. Non-functional requirements

- **Compatibility**: PHP `^8.0`; `illuminate/*` `^8.0`–`^13.0`, because one library serves repositories
  on different Laravel majors. A new dependency needs a strong reason. The floor is not a preference:
  `match` and `?->` are already used in `src/`, and `tests/Unit/PhpVersionContractTest.php` fails when
  the promise in `composer.json` drops.
- **No clinical data at rest.** The library writes logs and queue rows, never patient records.
- **Failure is visible.** No silent `catch`; every failed send ends in `failed` or `dlq` with a reason.

# SRD — satusehat-integration (PHP SDK for SATUSEHAT FHIR R4)

Status: v2.0 · 2026-11-17 · Companion: `docs/PRD.md`
Every number below is **measured** from this repository (`ls`, `grep`, `phpunit`); the command is
written next to the ones that are easy to re-derive. When the code changes, update this document in the
same commit.

## 1. Scope

How the library works internally: layers, public contract, token and queue flow, error mapping,
compatibility rules, and how it is tested. It does not explain how to use SATUSEHAT (that is
`README.md`) or the products that consume it.

## 2. Architecture principles

1. **Build first, send later.** Payload builders (`Builder`, `DataType`) never touch the network;
   transport lives only in `SSRequest` and `Queue\Worker`. One direction of dependency.
2. **Typed values on the new path.** Types, `status`, and references are validated while the payload is
   built, so an error surfaces before anything is sent.
3. **The legacy path stays alive but does not grow.** `src/FHIR/*` is the compatibility layer for 3.x
   callers. New behaviour goes into `PayloadBuilder` only; legacy files are migrated one at a time
   (tracked as T-01).
4. **Every failure has a home.** A failed send ends in `failed` or `dlq` with the reason stored; there
   is no empty `catch`.
5. **Secrets come from the environment.** No credential in code, sample config, or log.
6. **One behaviour, five languages.** A payload shape change must have its counterpart in the Node,
   Python, Go, and Rust SDKs at the same release number.

## 3. System architecture

```
consumer (hospital app / Laravel / script)
   │
   ├── Builder/*        build the FHIR payload      (no network)
   │     └── DataType/* typed values (34)
   │
   ├── SSRequest        synchronous send            (guzzle, retry, token refresh)
   │     ├── SSResponse          wrapped response
   │     └── SSValidationError   558 rules + 14 response types
   │
   ├── Queue/*          asynchronous send           (SQLite: pending→processing→success|failed|dlq)
   │     ├── SqliteQueue, Worker, RateLimiter, ErrorClassifier, QueueMonitor, QueueResult
   │     └── Console/Commands: EnqueueSatusehat, ProcessSatusehatQueue, QueueStatusSatusehat
   │
   ├── Publisher/*      send to a broker/client     (Guzzle, PSR-18, Laravel HTTP)
   │
   └── external services
         ├── OAuth2Client     SATUSEHAT token (client_credentials)
         ├── TerminologyClient SmarTerm (PAT or OIDC client_credentials)
         ├── Tenancy/TenantCredentialResolver  per-tenant credentials
         └── KYC              RSA keys + encryption (identity verification)
```

Dependencies point downward only: `Builder` may not import `SSRequest`; `DataType` may not import
anything from `Queue`.

## 4. Measured inventory (2026-11-17)

| Directory | Files | Role |
|---|---|---|
| `src/Builder/` | 142 | 141 `PayloadBuilderXxx` per resource plus the abstract `Builder` |
| `src/DataType/` | 34 | Coding, CodeableConcept, HumanName, Reference, Quantity, … |
| `src/FHIR/` | 50 | legacy-style resources for 3.x compatibility; not extended any more |
| `src/Parser/` | 142 | typed readers over a SATUSEHAT payload |
| `src/Terminology/` | 24 | terminology client support |
| `src/Queue/` | 8 | persistent queue |
| `src/SSResponse/` | 3 | `SSResponse`, `SSValidationError`, support |
| `src/Models/` | 3 | `SatusehatLog`, `SatusehatQueue`, `SatusehatToken` |
| `src/Console/Commands/` | 3 | artisan queue commands |
| `src/Tenancy/` | 1 | `TenantCredentialResolver` |
| `src/` root | 5 | `OAuth2Client`, `TerminologyClient`, `TerminologyQueryBuilder`, `KYC`, service provider |
| `database/seeders/csv/*.stub` | 18 | master-data datasets shipped as stubs |

## 5. Public contract

### 5.1 Builder

```php
$b = new PayloadBuilderPatient();                  // 141 PayloadBuilder* classes
$b->setId('100000000001')
  ->addName(new HumanName(family: 'Harsono', given: ['Ivan']))
  ->setGender('male')
  ->setBirthDate('1990-01-01')
  ->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Patient');
$payload = $b->build();   // FHIR array; nulls and empty arrays dropped
$payload = $b->json();    // same; subclasses may add validation and throw RuntimeException
```

The abstract `Builder` provides `build()`/`json()`; there is **no** `::make()` — instances come from
`new`, and class names always start with `PayloadBuilder`.

Binding rules:

- **Methods are not uniform across resources.** Not every resource has a setter for every element
  (for example `PayloadBuilderPatient` has no `setManagingOrganization`); read the class before calling.
- **Typed object OR string** is accepted wherever a reference or CodeableConcept is expected. A string
  that already contains `/`, or starts with `urn:`, `http://`, `https://`, gets no type prefix.
- **`status`/`intent` are validated**; a value outside the list throws `InvalidArgumentException`.
- **`build()` returns an array**, not an object.

### 5.2 Transport

`SSRequest` wraps Guzzle: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `PATCH` by FHIRPath, and
`POST` of a Bundle. On `401` it refreshes the token once and retries; on `429`/`5xx` it backs off
exponentially, honouring `Retry-After` when present.

### 5.3 Validation

`SSValidationError` maps the 558 SATUSEHAT rule codes and 14 response types onto readable messages.
Callers branch on the code, never on the message text.

### 5.4 Terminology

`TerminologyClient` talks to SmarTerm (`terminology.ivanmd.id`), separate from SATUSEHAT OAuth. Two
modes: **Sanctum PAT** (`TERMINOLOGY_API_TOKEN`) or **OIDC client_credentials**
(`TERMINOLOGY_CLIENT_ID`/`_SECRET`/`_TOKEN_URL`) with automatic refresh. When both are configured, PAT
wins. Terminology must not become a silent dead end: a service failure is surfaced as a clear error,
never as an empty payload masquerading as "no data".

`TerminologyQueryBuilder` gives the fluent surface: `query($text)->…`, `map()`, `validate()`,
`autoMap()`, `kptlCombine()`, `resolveCondition()`, `resolveChiefComplaint()`, `resolveObservation()`,
`resolveMedication()`, `resolveServiceRequest()`, `valueSets()`, `valueSet($bundleId)`.

### 5.5 Per-tenant credentials

`TenantCredentialResolver::resolve(?string $teamKey)` picks a credential source in order: override
disabled → **env**; override enabled with a non-database driver → **array**; override enabled with the
`database` driver → **tenant row**, falling back to the array. The cache key includes `teamKey`, so one
process can serve several tenants without overwriting each other.

## 6. Flows

**Synchronous send**: build payload → `SSRequest::post` → `401`? new token and retry → `429`? wait →
`2xx`? wrap as `SSResponse` with the resource id → otherwise map the error through
`SSValidationError`.

**Asynchronous send**: `EnqueueSatusehat` writes a `pending` row → `ProcessSatusehatQueue` runs
`Worker::process(limit)` → `RateLimiter::wait()` → send → `markSuccess` / `markFailed` → attempts
exhausted → `markDlq`. `QueueStatusSatusehat` prints per-status counts and the DLQ contents.

**Master data**: `database/seeders/csv/*.csv.stub` files (18) are copied to `.csv` at publish time and
read by the seeders (separator `;`, with a header row). A migration for `.stub` ships for applications
that want the dataset stored locally.

## 7. Compatibility rules

- **Never remove or rename a public method** without a migration note in `CHANGELOG.md`.
- **New logic only in `PayloadBuilder`**; adding behaviour under `src/FHIR/*` is forbidden.
- **`DataType::toArray()` must not return an object**; older tests rely on plain arrays.
- **PHP 8.0 floor** — measured, not negotiated: `match(` and `?->` appear across `src/`, so 7.4 has
  been impossible for a long time. `tests/Unit/PhpVersionContractTest.php` scans `src/` tokens (not
  text, so `match(` inside a comment does not count), reads the lowest constraint from
  `composer.json`, and requires that version to exist in the CI matrix.

## 8. Performance and limits

Building a payload is pure array work; the expensive part is the network. The queue exists so a caller
can accept a request without waiting for SATUSEHAT, and the rate limiter keeps the send rate inside the
platform quota. Nothing in this library caches SATUSEHAT responses: a stale cache in a clinical
integration is a wrong answer, not a fast one.

## 9. Tests

```bash
SATUSEHAT_ENV=DEV vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests
```

1.933 test methods, 4.672 assertions. CI runs the suite on Linux, macOS, and Windows for each supported
PHP version.

## 10. Architecture decisions

See `docs/ARCHITECTURE.md` for the decision records. The ones that shape this document: build/send
separation, typed datatypes on the new path, inheritance for the R5 namespace, and rules as data rather
than code.

## 11. Anti-patterns (do not repeat)

- Adding a setter that writes an element the resource does not define. `docs/R4_CONFLICT_TRIAGE.md`
  exists because that mistake was made 68 times.
- Duplicating the R5 classes instead of inheriting them, which forks behaviour silently.
- Copying a measurement into prose instead of generating it.

## 12. Measured technical debt

T-01: 46 of the 50 legacy `src/FHIR/*` classes still build their payload by hand instead of delegating
to a `PayloadBuilder`. Tracked, not hidden.

## 13. Traceability

Every functional requirement in `docs/PRD.md` maps to a class in this document; a requirement without a
class is a requirement nobody implemented.

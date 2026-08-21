# Changelog

## v4.6.0 — Phase 2: Durable Queue + Transaction Logger - 2026-08-21

### What's Changed

#### Phase 2: Durable Queue System

**New:** SQLite-compatible durable queue (no Redis required).

**SatusehatQueue Model** (`src/Models/SatusehatQueue.php`):

- Status flow: `pending` → `processing` → `success` | `failed` | `dlq`
- Exponential backoff: `attempts^2 * 30` seconds, max 5 min
- Retriable codes: 408, 429, 500, 502, 503, 504
- Auto-DLQ after `max_attempts` exceeded
- Idempotency key (UUID) for deduplication
- Deferred scheduling (`scheduled_at`)

**Artisan Commands:**

```bash
# Process pending queue (cron every minute)
php artisan satusehat:process-queue --once --limit=50

# Enqueue from CLI or code
php artisan satusehat:enqueue POST Bundle --payload='{"resourceType":"Bundle",...}'

# Status dashboard
php artisan satusehat:queue-status
php artisan satusehat:queue-status --dlq

# Re-queue failed/dlq entries
php artisan satusehat:process-queue --reset

```
**Service Provider Enhancements:**

- `schedule()`: auto-registers `satusehat:process-queue --once` every minute
- Auto-publish queue migration via `--queue` tag
- Commands auto-registered for `php artisan`

#### Transaction Logging (Wired)

- **SSRequest**: every HTTP call now logged to `satusehat_log` table
- Configurable: `SATUSEHAT_LOG_ENABLED=false` to disable
- Logs: action, url, payload, response, http_code, user_id

#### Config Additions

```env
SATUSEHAT_LOG_ENABLED=true
SATUSEHAT_LOG_USER_ID=system
SATUSEHAT_QUEUE_TABLE=satusehat_queue
SATUSEHAT_QUEUE_MAX_ATTEMPTS=5

```

---

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/v4.5.0...v4.6.0

## v4.5.0 — Non-Core Resources + FHIR PATCH + BundleResponse - 2026-08-21

### What's Changed

#### New FHIR Resources (Non-Core)

12 new resources — 12 FHIR classes + 12 PayloadBuilder classes:

- **Coverage** — Insurance/payer coverage
- **CoverageEligibilityRequest / Response** — Eligibility check
- **Claim / ClaimResponse** — Claims submission
- **ChargeItem / ChargeItemDefinition / ChargeItemResponse** — Billing items
- **PaymentNotice / PaymentReconciliation** — Payment tracking
- **Invoice** — Billing invoice
- **ImagingStudy** — Imaging study reference

#### FHIR PATCH Support

- **SSRequest**: `PATCH` now uses `Content-Type: application/json-patch+json` (FHIR R4 spec)
- **SSRequest**: Added `patchFhirPath()` for FHIRPath Patch format (`application/fhir+json`)

#### Bundle Enhancements (PayloadBuilderBundle)

- Fixed: duplicate `ifNoneMatch` → now correctly sets `ifMatch` for conditional PUT/PATCH
- Added: `ifMatch` support in `addBatchEntry()`
- Added: `addTransactionEntry()` — convenience wrapper for transaction bundles
- Added: `addBatchOnlyEntry()` — request-only entry (no response expectation)
- Added: `addGetEntry()` / `addDeleteEntry()` — convenience helpers for GET/DELETE entries
- Fixed: GET/DELETE entries no longer require `resource` body

#### BundleResponse Parser (New)

- `BundleResponse` class to parse batch/transaction responses
- Per-entry status, resource ID, ETag, OperationOutcome extraction
- `getSuccessEntries()` / `getFailedEntries()` helpers
- `isAllSuccess()` for atomic bundle validation


---

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/v4.4.0...v4.5.0

## v4.4.0 — Phase 3: Account, DiagnosticReport, EpisodeOfCare + CI Matrix Fix - 2026-08-21

### What's Changed

#### New FHIR Resources (Phase 3)

- **Account** — FHIR R4 Account resource class
- **DiagnosticReport** — FHIR R4 DiagnosticReport resource class
- **EpisodeOfCare** — FHIR R4 EpisodeOfCare resource class

#### CI / Build

- **Unix + Windows matrix** — `pcntl` is Unix-only; Windows now runs PHP 8.0 + 8.3 without it
- **PHP version matrix** — Corrected to PHP 8.0 + 8.3 only (removed PHP 7.4, which requires PHP 8.0+ runtime)
- **prefer-stable only** — Dropped `prefer-lowest` from base matrix to avoid `illuminate/*` PHP 8.0+ dependency conflicts

#### Syntax

- **Union types removed** — `float|int` in `PayloadBuilderImmunization`, `string|DateTimeInterface` in `PayloadBuilderBundle` (PHP 7.4 compat, not tested in CI)

#### Docs

- **README** — PHP 8.0+, 32 DataTypes, 38 PayloadBuilders, 38 FHIR Resources
- **Wiki** — Features, FHIR-Resources, Installation updated to reflect v4.4.0


---

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/v4.3.3...v4.4.0

## v3.3.4 — Add Account FHIR class - 2026-08-18

### What's New

**FHIR Resource: Account**

Phase 3 migration from `fhirvel-ss`. See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration




```
## v4.3.3 - 2026-08-18

### v4.3.3 — PHP 7.4 Full Compatibility Fix

#### Fixes

- **Builder.php**: remove `mixed` param type hints (PHP 8+), add namespaced `str_contains`/`str_starts_with` polyfills for PHP 7.4
- **DataType.php**: remove `mixed` from `toArrayRecursive()` signature
- **CI**: matrix = PHP 7.4 + 8.3 (ubuntu + windows)

## v4.3.2 - 2026-08-18

### v4.3.2 — PHP 7.4 Compatibility Fix

#### Fix

Replace all `: static` return type with `: self` in DataType classes.

`static` return type is PHP 8.1+ syntax — causes parse error on PHP 7.4.
`self` has identical behavior for final DataType classes.

10 files: CodeableConcept, Timing, TimingRepeat, Dosage, DosageDoseAndRate, Extension, Signature, DataRequirement, RelatedArtifact, TriggerDefinition.

composer.json + CI now actually support PHP 7.4 as claimed.

## v4.3.1 - 2026-08-18

### v4.3.1 — Test Suite Stabilization

#### Core fixes

- **DataType::toArray()**: now recursively converts nested DataType objects (Coding, Period, etc.) to plain arrays. Previously stored Coding objects as objects, causing "Cannot use object as array" errors in tests.
- **Builder::set()**: now handles dot-notation paths (e.g. `collection/collectedDateTime`) by creating nested array structures. Previously stored as literal key `'collection/collectedDateTime'`.
- **Builder::json()**: added base method (validates + returns array).
- **PayloadBuilderImmunization**: fix CodeableConcept constructor (was passing Coding[] array to constructor).

#### CI

- Matrix reduced to PHP 7.4 + 8.3 only (ubuntu + windows)

#### Tests

- 38 test files covering all PayloadBuilder resources
- 782 tests, 1185 assertions — all passing

## v4.3.0 - 2026-08-18

### v4.3.0 — Full Unit Test Suite + Builder Bug Fixes

#### Tests

- 38 test files in tests/FHIR/ covering all PayloadBuilder resources
- AllergyIntolerance, Bundle, CarePlan, ClinicalImpression, Composition, Condition, Device, DiagnosticReport, DocumentReference, Encounter, EpisodeOfCare, FamilyMemberHistory, GenomicStudy, Goal, Group, Immunization, Location, Medication (+Administration, +Dispense, +Statement, +Request), MolecularSequence, NutritionOrder, Observation, Organization, Patient, Practitioner, PractitionerRole, Procedure, QuestionnaireResponse, RelatedPerson, RiskAssessment, ServiceRequest, Specimen, Substance, Task
- tests/bootstrap.php: use putenv() so OAuth2Client::getenv() picks up env vars in CI

#### Fixes

- PayloadBuilderImmunization: fix CodeableConcept constructor (takes string params, not Coding[])

#### CI

- Matrix: PHP 7.4, 8.1, 8.2, 8.3 on ubuntu + windows

## v4.2.2 - 2026-08-18

### v4.2.2 — CI Test Bootstrap Fix

#### Fixes

- phpunit.xml.dist: bootstrap=vendor/autoload.php → tests/bootstrap.php (was ignoring config() + base_path() stubs)
- tests/bootstrap.php: add SATUSEHAT_ENV=STG + credential stubs so OAuth2Client::__construct() no longer throws on missing env in CI

## v4.2.1 - 2026-08-18

### v4.2.1 — Test Compatibility Fix

#### Fix

- **OAuth2Client**: add ss_config() helper — checks function_exists before calling Laravel config() helper. Returns default when unavailable (e.g. in CI unit tests without Laravel bootstrap).

#### Files changed

- src/OAuth2Client.php: +ss_config helper, replace config() call

## v4.2.0 - 2026-08-16

### v4.2.0 — Phase 2: Full PayloadBuilder Migration

#### 21 new PayloadBuilders (migrated from fhirvel-ss)

Account, AllergyIntolerance, DiagnosticReport, DocumentReference, EpisodeOfCare, FamilyMemberHistory, GenomicStudy, Group, Immunization, Medication, MedicationAdministration, MedicationDispense, MedicationStatement, MolecularSequence, QuestionnaireResponse, RelatedPerson, RiskAssessment, ServiceRequest, Specimen, Substance, Task

#### Core fixes

- **SSRequest**: add `patch()` method — PATCH verb support for FHIR conditional updates
- **Builder**: add `json()` base method — validates + throws on error
- **PayloadBuilderBundle**: complete rewrite — type constants, search/history/batch entry helpers, pagination links, `validate()`, `json()`

#### Pattern

All PayloadBuilders: immutable chainable setters → `->json()` → SSRequest HTTP call

#### Changelog

- SSRequest: `get()`, `post()`, `put()`, `patch()`, `delete()`, `postBundle()`
- All 38 resource PayloadBuilders with typed DataType params
- Backward compatible

## v4.1.0 - 2026-08-16

### v4.1.0 — Device Migration (Phase 3, Phase 1 Compliant)

#### FHIR Device

- Add **PayloadBuilderDevice**: fluent API using DataType classes (Identifier, CodeableConcept, Reference, Annotation)
- Refactor Device.php: static build() returning PayloadBuilderDevice, chainable setters, json() validates required fields (status, manufacturer, type)
- Backward compatible with old OAuth2Client/SSRequest pattern

#### Breaking Changes

- Device::build() now returns PayloadBuilderDevice (use ->json() to serialize)
- Old Device::ss_send() deprecated — use SSRequest::post() with ->json() output

## v4.0.1 — Phase 1: FHIR R4 DataType Architecture Refactor - 2026-08-15

**NOTE:** v4.0.0 was blocked by Packagist (stable version immutability). Use v4.0.1.

### What's New

#### 31 FHIR R4 DataType Classes

Core: Coding, CodeableConcept, Identifier, Period, ContactPoint, Address, HumanName, Reference
Quantity: Quantity, SimpleQuantity, Range, Ratio, Age, Count, Distance, Duration, Money
Structured: Attachment, Narrative, Annotation, Timing, TimingRepeat, Dosage, DosageDoseAndRate
Utility: Extension, Signature, RelatedArtifact, Expression, TriggerDefinition, DataRequirement, ParameterDefinition

#### SSRequest — HTTP Client

- get/post/put/delete returning SSResponse
- Auto token-refresh on HTTP 401 (up to 5 retries)
- HTTP 429 with Retry-After header handling
- Exponential backoff: 2^n seconds, max 5 retries, capped at 60s

#### SSResponse — Response Wrapper

- isSuccess() / isError() for HTTP status check
- getErrorMessages() extracts FHIR OperationOutcome issue messages
- getResourceId() for response ID extraction

#### 17 PayloadBuilder Classes

Builder.php (abstract base) + PayloadBuilderPatient, Encounter, Observation, Condition, Procedure, MedicationRequest, Organization, Practitioner, PractitionerRole, Location, Bundle, CarePlan, Composition, ClinicalImpression, Goal, NutritionOrder

#### Breaking Changes

- DataType base class now has recursive toArray()
- SSRequest/SSResponse replaces raw ss_post/ss_get return format

## v4.0.0 — Phase 1: DataType Architecture Refactor - 2026-08-15

### Breaking Changes

- **DataType base class** now has recursive `toArray()` — nested `DataType` objects are serialized properly. Classes relying on shallow `toArray()` output may need adjustment.
- **SSRequest/SSResponse** replaces raw `ss_post/ss_get` return format — old `$client->ss_post()` returning `[$statusCode, $response]` is deprecated in favor of `SSResponse` objects.

### What's New

**31 FHIR R4 DataType classes** (`src/DataType/`):

- Core: `Coding`, `CodeableConcept`, `Identifier`, `Period`, `ContactPoint`, `Address`, `HumanName`, `Reference`
- Quantity: `Quantity`, `SimpleQuantity`, `Range`, `Ratio`, `Age`, `Count`, `Distance`, `Duration`, `Money`
- Structured: `Attachment`, `Narrative`, `Annotation`, `Timing`, `TimingRepeat`, `Dosage`, `DosageDoseAndRate`
- Utility: `Extension`, `Signature`, `RelatedArtifact`, `Expression`, `TriggerDefinition`, `DataRequirement`, `ParameterDefinition`

**SSRequest** (`src/SSRequest/SSRequest.php`):

- HTTP client with `get/post/put/delete` → returns `SSResponse`
- Auto token-refresh on HTTP 401 (up to 5 retries)
- HTTP 429 with `Retry-After` header handling
- Exponential backoff: 2^n seconds, max 5 retries, capped at 60s
- Configurable timeout, SSL verify, custom headers

**SSResponse** (`src/SSResponse/SSResponse.php`):

- `isSuccess()` / `isError()` for HTTP status check
- `getErrorMessages()` extracts FHIR OperationOutcome issue messages
- `getResourceId()` / `get(resourceType)` for response ID extraction

**12 PayloadBuilder classes** (`src/Builder/`):

- `Builder.php` — abstract base with fluent `set()` / `push()` / `merge()` / `build()`
- `PayloadBuilderPatient`, `Encounter`, `Observation`, `Condition`, `Procedure`, `MedicationRequest`, `Organization`, `Practitioner`, `PractitionerRole`, `Location`, `Bundle`, `CarePlan`, `Composition`, `ClinicalImpression`, `Goal`, `NutritionOrder`
- Fluent API: `->setSubject($ref)->setStatus('active')->build()`
- Polymorphic setters for FHIR `[x]` elements (e.g., `setValueQuantity()`, `setOnsetDateTime()`)
- All fields typed with DataType objects

```bash
composer update ivanwilliammd/satusehat-integration













```
## v3.3.3 — Add Composition FHIR class - 2026-08-12

### What's New

**FHIR Resource: Composition**

Phase 3 migration from `fhirvel-ss`. See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration













```
## v3.3.2 — Add NutritionOrder FHIR class - 2026-08-09

### What's New

**FHIR Resource: NutritionOrder**

Phase 3 migration from `fhirvel-ss`. See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration













```
## v3.3.1 — Add Goal FHIR class - 2026-08-05

### What's New

**FHIR Resource: Goal**

Phase 3 migration from `fhirvel-ss`. See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration
















```
## v3.3.0 — Add CarePlan FHIR class - 2026-08-02

### What's New

**FHIR Resource: CarePlan**

Phase 3 migration from `fhirvel-ss`. See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration

















```
## 3.3.0 — Add ClinicalImpression FHIR class - 2026-08-01

### What's New

**FHIR Resource: ClinicalImpression**

Phase 3 migration from `fhirvel-ss` — one resource at a time.

See [ROADMAP.md](https://github.com/ivanwilliammd/satusehat-integration/blob/main/ROADMAP.md).

```bash
composer update ivanwilliammd/satusehat-integration


















```
## 3.2.0 — Laravel 13 Support - 2026-08-01

### What's New

**Laravel 13 Support**

- Bump `illuminate/config`, `illuminate/database`, `illuminate/support` to `^13.0`
- Fix Dotenv path: use `base_path()` when inside Laravel, fallback to `getcwd()` for standalone
- Replace deprecated `Dotenv::createUnsafeImmutable` with `Dotenv::createImmutable`

### Upgrade

```bash
composer update ivanwilliammd/satusehat-integration



















```
### Compatibility

| Laravel | Package Version |
|---------|---------------|
| 8–12   | 2.8.x – 3.1.x |
| 13      | 3.2.0+        |

## 3.1.0-stable - 2025-02-25

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/141
* Updated support for Laravel 12

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/3.0.0-stable...3.1.0-stable

## 3.0.0-stable - 2025-01-30

FULLY Tested functionality for Fase 1 Rawat Jalan SATUSEHAT

### Kualitas Kode dan Dukungan

|  | Free |
|----------|------|
| Functional Testing | ⚠️ |
| Code Quality Check | ❌ |
| Dukungan integrasi | ❌ |
| Tanya Jawab | ❌ |
| Pelatihan FHIR | ❌ |
| Pelatihan Terminologi | ❌ |

### Integrasi Terminologi

|  | Terminologi | Free |
|---|-------------|------|
|1 | ICD-10: Diagnosis | ✅ |
|2 | ICD-9CM: Procedure | ❌ |
|3 | Kode Wilayah Indonesia | ✅ |
|4 | KFA API (v2) | ✅ |
|5 | LOINC and LOINC Answer: for Lab & Radiology | ❌ |
|6 | SNOMED-CT | ❌ |
|7 | KPTL (Kode Pembiayaan Tindakan dan Layanan Kesehatan) | ❌ |
|8 | Units of Measure | ❌ |
|9 | CVX (CDC Vaccine Codes) | ❌ |
|10 | Terminology Kemkes (upd. Apr 2023) | ❌ |
|11 | FHIR Value Set dan Terminology | ❌ |

### Integrasi dasar SATUSEHAT

|  | Fitur | Free |
|---|-------------|------|
| 1 | Basic - Otentikasi | ✅ |
| 2 | Basic - KYC Centang Biru | ✅ |
| 3 | Data - Batch/Bundle Kunjungan Diagnosis | ✅ |
| 4 | Pencarian - ID Tenaga Kesehatan | ✅ |
| 5 | Pencarian - ID Pasien | ✅ |
| 6 | Manajemen - Lokasi | ✅ |
| 7 | Manajemen - Suborganisasi | ✅ |
| 8 | Identitas - Anggota Keluarga/Wali | ❌ |
| 9 | Klinis - Pemeriksaan tanda-tanda vital | ⚠️ |
| 10 | Klinis - Pemeriksaan fisik | ❌ |
| 11 | Klinis - Peresepan Obat | ❌ |
| 12 | Klinis - Edukasi | ❌ |
| 13 | Klinis - Pemberian Tindakan | ❌ |
| 14 | Klinis - Prognosis | ❌ |
| 15 | Klinis - Pencatatan Alergi | ❌ |
| 16 | Klinis - Pencatatan Medikasi | ❌ |
| 17 | Klinis - Imunisasi | ❌ |
| 18 | Klinis - Asesmen Risiko | ❌ |
| 19 | Klinis - Asuhan Keperawatan | ❌ |
| 20 | Klinis - Rencana Perawatan | ❌ |
| 21 | Klinis - Rencana Follow-up | ❌ |
| 22 | Logistik - Order Gizi | ❌ |
| 23 | Logistik - Pengaturan Bed | ❌ |
| 24 | Logistik - Manajemen tugas (Task) | ❌ |
| 25 | Logistik - Peralatan (Device) Diagnostik / Terapetik | ❌ |
| 26 | Penunjang - Farmasi Dispense Obat | ❌ |
| 27 | Penunjang - Laboratorium | ❌ |
| 28 | Penunjang - Radiologi (diluar router) | ❌ |
| 29 | Penunjang - Pelaporan genetik | ❌ |
| 30 | Pembiayaan - Billing dan Invoice | ❌ |
| 31 | Pembiayaan - Klaim Swasta | ❌ |
| 32 | Pembiayaan - Klaim BPJS | ❌ |
| 33 | Lain-lain - Kuesioner | ❌ |

### Use-case Implementasi

|  | Fitur | Free |
|---|-------------|------|
| 1 | Dasar - Layanan Rawat Jalan | ❌ |
| 2 | Dasar - Layanan Rawat Inap | ❌ |
| 3 | Dasar - Layanan Gawat Darurat | ❌ |
| 4 | Rujukan - Nomor Resep National | ❌ |
| 5 | Rujukan - Sampel laboratorium | ❌ |
| 6 | Rujukan - Sampel Skrining Hipotiroid Kongenital | ❌ |
| 7 | Layanan khusus - Gigi | ❌ |
| 8 | Layanan primer - Antenatal Care | ❌ |
| 9 | Layanan primer - Intranatal Care | ❌ |
| 10 | Layanan primer - Postnatal Care | ❌ |
| 11 | Layanan primer - Gizi | ❌ |
| 12 | Layanan primer - Skrining PTM | ❌ |
| 13 | Layanan primer - Tuberkulosis | ❌ |
| 14 | BGSi - Registri Kanker | ❌ |
| 15 | BGSi - Registri Jantung | ❌ |
| 16 | BGSi - Registri Stroke | ❌ |
| 17 | BGSi - Registri Uronefrologi | ❌ |
| 18 | BGSi - Registri Mata | ❌ |

### Penjelasan Simbol:

- **✅**: Semua metode (GET, POST, PUT) didukung.
- **⚠️**: Dukungan kasus penggunaan sebagian.
- **❌**: Tidak didukung.

## 2.9.8 - 2024-11-14

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.7...2.9.8

- @ivanwilliammd Revert mandatory requirement of Patient.address on https://github.com/ivanwilliammd/satusehat-integration/pull/60

## 2.9.7 - 2024-11-13

### What's Changed

* Add new physical location type ( Bed ) by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/63

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.6...2.9.7

## 2.9.6 - 2024-11-12

### What's Changed

* Disable required address in Patient by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/60
* Hotfix organization by @yudistirasd ft. @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/62

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.5...2.9.6

## 2.9.5 - 2024-11-04

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.3...2.9.5

Hotfix by @ivanwilliammd for kode wilayah Indonesia migration --> 2.9.x feat by @IrsyadProject)

## 2.9.3 - 2024-11-04

### What's Changed

* Update CSV data kode wilayah dari KEMENDAGRI Tahun 2023, update migration & seeder kode wilayah by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/59

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.2...2.9.3

## 2.9.2 - 2024-10-10

### What's Changed

* fix: bug migration kode wilayah indonesia by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/58

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.1...2.9.2

## 2.9.1 - 2024-10-10

### What's Changed

* fix: kode wilayah indonesia by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/57

### New Contributors

* @IrsyadProject made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/57

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.0...2.9.1

## 2.9.0 - 2024-07-20

### What's Changed

* Bump dependabot/fetch-metadata from 2.1.0 to 2.2.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/54
* Fixed put function for for encounter, condition, location, observation

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.8.3...2.9.0

## 2.8.3 - 2024-07-02

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.8.2...2.8.3

- Full updated dependencies for Laravel 11 (Illuminate 11)

## 2.8.0 - 2024-07-02

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.7.0...2.8.0

- Add composer.json declaration to support php 8.2+ / 8.3+

## 2.7.0 - 2024-05-09

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/47
* Bump dependabot/fetch-metadata from 2.0.0 to 2.1.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/48
* Fix  seach patient by nik by @widialjatsiyah in https://github.com/ivanwilliammd/satusehat-integration/pull/50

### New Contributors

* @widialjatsiyah made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/50

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.6.0...2.7.0

## 2.6.0 - 2024-04-10

### What's Changed

* Refactor Exception Handling with some fix by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/44
* 43 refactoring exception handlling by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/45
* fixed OAuthClient.php by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/46

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.5.1...2.6.0

## 2.5.1 - 2024-04-06

### What's Changed

* hotfix multitenancy feature and strictly typed OAuthClient.php by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/42

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.5.0...2.5.1

## 2.5.0 - 2024-04-06

### What's Changed

* Added: API KFA
* Updated : OAuth parameter changed from base_url to fhir_url
* KFA Integration by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/38
* 31 api kfa by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/39
* updated v2.5.0 by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/40

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.4.0...2.5.0

## 2.4.0 - 2024-04-03

### What's Changed

* Update: OAuth2Client, Patient, and Organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/36
* Added compatibility for Patient Post with identifier `nik-ibu`

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.3...2.4.0

## 2.3.3 - 2024-04-02

### What's Changed

* updated json function inside bundle by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/34

### New Contributors

* @ivanwilliammd made their contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/34

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.2...2.3.3

## 2.3.2 - 2024-04-02

### What's Changed

* improvement: Condition throw exception error by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/29

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.1...2.3.2

## 2.3.1 - 2024-03-31

### What's Changed

* fix: Encounter & Condition bundle by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/27
* Linkage of urn:uuid between Encounter & Condition
* Updated wiki

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.0...2.3.1

## 2.3.0 - Initiation of bundle support - 2024-03-30

### What's Changed

* feat: Encounter & Condition bundle by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/26

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.2.1...2.3.0

## v2.3.x :

- Initiation of bundle support
- Minor Bug fix

## v2.2.x :

- Change test to use PHPUnit 9 for support of 7.4, 8.1, 8.2, 8.3

## v2.1.x :

- Added Kode Wilayah Indonesia (KodWilId) class
- Minor default parameter of `ss_parameter_override` to false in satusehat config file
- Updated .env.example

## v2.0.x :

- Splitted terminology model
- Added new migration database, and seeder
- Expanded Practitioner GET Model
- Updated satusehat config file to support multitenancy with overloading in Controller using `http://github.com/mpociot/teamwork` package

Example of overloaded BaseController in Laravel 8+:

```php
<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Auth
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    //

    public function overrideEnvironment($ss_oauth2){

        $this->currentTeam= Auth::user()->currentTeam;
        $ss_oauth2->satusehat_env = $this->currentTeam->ss_environment;

        // Override construct parameter
        if($this->currentTeam){
            if($ss_oauth2->satusehat_env == 'PROD'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_prod_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_prod_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_prod_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'STG'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_stg_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_stg_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_stg_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'DEV'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_dev_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_dev_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_dev_organization_id;
            } else {
                return redirect()->route('admin.home')->withDanger('Anda belum menambahkan settingan environment SATUSEHAT pada Database.');
            }
        }

        return $ss_oauth2;
    }
}




















































```
v1.2.x :

- Backlog Compatilibity with Laravel 8+ (PHP 7.4) / Laravel 9 (PHP 8.0+) / Laravel 10 (PHP 8.1+)
- Bug fixing
- Splitted Encounter statusHistory
- Added functionality of Patient,
- Minor adjustmnent of Organization, Location, and OAuth2Client
- Minor bug fix

v1.1 :

- Standardize json() function to result encoded one with pretty print and no escape sequence
- Major functional fix of Encounter & Condition function class. Conversion to ATOM type datetime
- Added beta functionality of Observation
- Fixing inconsistency and function in Condition
- Updated timezone format

v1.0 :

- First beta version with PHP Class and consistency update of ICD 10-column migration
- Added faster batch import using csv seeder library

v0.15 :

- Last v0 series internally tested for creating OAuth 2
- Shipped basic method for GET by NIK function
- Shipped POST / PUT on FHIR object directly at Encounter, Condition, Organization, Location

## 2.2.1 - Ensuring consistency of $this->json() - 2024-03-26

### What's Changed

* Bump dependabot/fetch-metadata from 1.6.0 to 2.0.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/23

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.2.0...2.2.1

## 2.2.0 - Ensuring full compatibility from PHP 7.4, 8.1, 8.2, 8.3 - 2024-03-24

### What's Changed

* [WIP] Backward compatibility to PHP-7.4 by @YogiPristiawan in https://github.com/ivanwilliammd/satusehat-integration/pull/22

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.1.0...2.2.0

## 2.1.0 - Minor Adjustment + Kode Wilayah Indonesia Inclusion - 2024-03-22

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.0.1...2.1.0

v2.1.x :

- Added Kode Wilayah Indonesia (KodWilId) class
- Minor default parameter of `ss_parameter_override` to false in satusehat config file
- Updated .env.example

## Major Terminology Update and Multitenancy Support with Overloadding - 2024-03-22

v2.0.x :

- Splitted terminology model
- Added new migration database, and seeder
- Expanded Practitioner GET Model
- Updated satusehat config file to support multitenancy with overloading in Controller using `http://github.com/mpociot/teamwork` package

Example of overloaded BaseController in Laravel 8+:

```php
<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Auth
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    //

    public function overrideEnvironment($ss_oauth2){

        $this->currentTeam= Auth::user()->currentTeam;
        $ss_oauth2->satusehat_env = $this->currentTeam->ss_environment;

        // Override construct parameter
        if($this->currentTeam){
            if($ss_oauth2->satusehat_env == 'PROD'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_prod_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_prod_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_prod_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'STG'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_stg_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_stg_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_stg_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'DEV'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_dev_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_dev_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_dev_organization_id;
            } else {
                return redirect()->route('admin.home')->withDanger('Anda belum menambahkan settingan environment SATUSEHAT pada Database.');
            }
        }

        return $ss_oauth2;
    }
}




















































```
## 1.2.1 - 2024-03-22

### What's Changed

* feat: setType organization by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/14
* #fix FHIR Location by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/19

### New Contributors

* @yudistirasd made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/14

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.2.0...1.2.1

## 1.2.0 - 2024-03-17

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/6
* fix: undefined variable by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/8
* feat: patient by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/9
* fix: organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/10
* fix: OAuth2Client by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/11
* split encounter addStatusHistory method by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/12
* Update Encounter, Condition and Observation by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/15

### New Contributors

* @SyaefulKai made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/8

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.1...1.2.0

## 1.2 - 2024-03-17

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/6
* fix: undefined variable by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/8
* feat: patient by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/9
* fix: organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/10
* fix: OAuth2Client by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/11
* split encounter addStatusHistory method by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/12
* Update Encounter, Condition and Observation by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/15

### New Contributors

* @SyaefulKai made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/8

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.1...1.2

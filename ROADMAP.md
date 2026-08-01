# Roadmap: satusehat-integration Open Source Journey

> **Mission:** Build the most complete open-source SATUSEHAT FHIR R4 integration library for the PHP/Laravel ecosystem, closing the gap with the premium `fhirvel-ss` package by Q1 2027.

---

## Background

The SATUSEHAT ecosystem has two packages by `@ivanwilliammd`:

| Package | Type | FHIR Classes | License |
|---------|------|-------------|---------|
| `satusehat-integration` | Open Source | 8 | MIT |
| `fhirvel-ss` | Private/Premium | 36 | Proprietary |

**Goal:** Migrate `fhirvel-ss` features into `satusehat-integration` through community-driven phased releases, reaching full FHIR R4 parity by v5.0.

---

## Version Timeline

```
v3.2.0  ───  v3.3.0  ───  v3.4.0  ───  v4.0.0  ───  v5.0.0
  ✅         🔲          🔲          🔲          🔲
L13       Phase 2     Phase 2     Phase 3     Phase 3
support   docs+minor   Procedure   Full R4     Full R4
                      Medication   parity      + premium
                                  migration    features
```

---

## Phase 1 — Foundation (✅ DONE)
**v3.2.0** — Laravel 13 Support + Stability

- [x] Bump `illuminate/*` to `^13.0`
- [x] Fix Dotenv path (`base_path()` inside Laravel, `getcwd()` fallback)
- [x] Tag `3.2.0` + GitHub Release
- [x] Update README.md

---

## Phase 2 — Documentation & Community (v3.3.0 – v3.4.0)

### v3.3.0 — Docs Refresh
- [ ] Update wiki: Home.md, Features.md, Installation.md, Usage.md ✅ (in progress)
- [ ] Add `CONTRIBUTING.md` with class migration guide
- [ ] Add `CHANGELOG.md` automation
- [ ] Add `ROADMAP.md` (this file)
- [ ] Add CI/CD: PHPUnit tests for FHIR class builders
- [ ] Set up GitHub Actions for auto-release on tag

### v3.4.0 — Minor FHIR Classes
- [ ] Procedure FHIR class (`Procedure` — GET/POST/PUT)
- [ ] AllergyIntolerance FHIR class
- [ ] Immunization FHIR class
- [ ] PractitionerRole FHIR class
- [ ] DocumentReference FHIR class
- [ ] Community PR template

---

## Phase 3 — Major Feature Migration (v4.0.0)

> Migrating from `fhirvel-ss` → `satusehat-integration`

### FHIR Resource Parity

| # | Resource | v3.x | v4.0 | v5.0 |
|---|----------|------|------|------|
| 1 | Patient | ✅ | ✅ | ✅ |
| 2 | Practitioner | ✅ | ✅ | ✅ |
| 3 | Organization | ✅ | ✅ | ✅ |
| 4 | Location | ✅ | ✅ | ✅ |
| 5 | Encounter | ✅ | ✅ | ✅ |
| 6 | Condition | ✅ | ✅ | ✅ |
| 7 | Observation | ✅ | ✅ | ✅ |
| 8 | Bundle | ✅ | ✅ | ✅ |
| 9 | **Procedure** | 🔲 | ✅ | ✅ |
| 10 | **Medication** | 🔲 | ✅ | ✅ |
| 11 | **MedicationRequest** | 🔲 | ✅ | ✅ |
| 12 | **MedicationDispense** | 🔲 | ✅ | ✅ |
| 13 | **ServiceRequest** | 🔲 | ✅ | ✅ |
| 14 | **Specimen** | 🔲 | ✅ | ✅ |
| 15 | **DiagnosticReport** | 🔲 | ✅ | ✅ |
| 16 | **AllergicIntolerance** | 🔲 | ✅ | ✅ |
| 17 | **ClinicalImpression** | 🔲 | 🔲 | ✅ |
| 18 | **CarePlan** | 🔲 | 🔲 | ✅ |
| 19 | **Goal** | 🔲 | 🔲 | ✅ |
| 20 | **NutritionOrder** | 🔲 | 🔲 | ✅ |
| 21 | **Composition** | 🔲 | 🔲 | ✅ |
| 22 | **Immunization** | 🔲 | 🔲 | ✅ |

### Terminology Parity

| Feature | v3.x | v4.0 | v5.0 |
|---------|------|------|------|
| ICD-10 | ✅ | ✅ | ✅ |
| ICD-9CM | 🔲 | ✅ | ✅ |
| Kode Wilayah | ✅ | ✅ | ✅ |
| KFA API v2 | ✅ | ✅ | ✅ |
| LOINC | 🔲 | 🔲 | ✅ |
| SNOMED-CT | 🔲 | 🔲 | ✅ |

### Other v4.0 Goals
- [ ] Full PHPUnit test suite (>80% coverage on FHIR builders)
- [ ] PHPStan level 5+
- [ ] CS Fix with Laravel Pint
- [ ] Auto-release via GitHub Actions on semantic tag
- [ ] Packagist webhook for auto-update
- [ ] Integration with `satusehat-laravel-example` updated to Laravel 11/12

---

## Phase 4 — fhirvel-ss Deprecation (v5.0.0)

### Pre-deprecation (v4.x)
- [ ] Announce deprecation on GitHub, Packagist, Twitter/X
- [ ] Add `fhirvel-ss` → `satusehat-integration` migration guide
- [ ] Offer premium support credits to existing fhirvel-ss subscribers
- [ ] Update `FREEMIUM_COMPARISON.md` with v5.0 notes

### Deprecation (v5.0)
- [ ] `fhirvel-ss` archived (read-only, redirect to satusehat-integration)
- [ ] All premium FHIR classes now in `satusehat-integration`
- [ ] All subscribers migrated
- [ ] Publish case studies from early adopters

---

## Contributing

We welcome community contributions. See [CONTRIBUTING.md](CONTRIBUTING.md).

**Priority classes to migrate (from fhirvel-ss):**
1. `Procedure` — high demand for lab/radiology integration
2. `MedicationRequest` — prescription tracking
3. `MedicationDispense` — pharmacy dispensing
4. `ServiceRequest` — lab orders
5. `DiagnosticReport` — radiology results
6. `AllergicIntolerance` — clinical safety
7. `Specimen` — lab sample tracking

---

## Contact

- **Maintainer:** Dr. dr. Ivan William Harsono, MTI
- **Email:** ivanwilliam.md@gmail.com
- **GitHub Issues:** https://github.com/ivanwilliammd/satusehat-integration/issues

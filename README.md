# SATUSEHAT Integration Library

> **Build SATUSEHAT FHIR R4 objects with ease — open source Laravel PHP library.**

[![Latest Version](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Laravel](https://img.shields.io/badge/Laravel-8–13-blue)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-7.4–8.3-purple)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Stars](https://img.shields.io/github/stars/ivanwilliammd/satusehat-integration)](https://github.com/ivanwilliammd/satusehat-integration)

---

## Overview

`satusehat-integration` is an **open-source** Laravel PHP library that makes integrating with **SATUSEHAT** (Indonesia's national health data platform, powered by FHIR R4) simple and developer-friendly.

Built on the official [SATUSEHAT Platform Guidelines](https://satusehat.kemkes.go.id/platform/docs), it provides:
- OAuth2 authentication with SATUSEHAT IAM
- FHIR R4 resource object builders (Patient, Encounter, Condition, Observation, etc.)
- Bundle operations for multi-resource transactions
- KYC / Centang Biru verification
- Master data: ICD-10, Kode Wilayah Indonesia, KFA v2

---

## Quick Start

```bash
composer require ivanwilliammd/satusehat-integration
```

```env
# .env
SATUSEHAT_ENV=DEV          # DEV | STG | PROD
SATUSEHAT_BASE_URL_DEV=https://api-satusehat-dev.dto.kemkes.go.id
CLIENTID_DEV=your_client_id
CLIENTSECRET_DEV=your_client_secret
ORGID_DEV=your_org_id
```

```php
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\FHIR\FhirPatient;
use Satusehat\Integration\FHIR\FhirEncounter;

$client = new OAuth2Client();

// Build & post Patient
$patient = (new FhirPatient)
    ->setNik('3312345678901234')
    ->setName('John Doe')
    ->setGender('male')
    ->setBirthDate('1990-01-15')
    ->setAddress('Jl. Sudirman No.1', 'Jakarta Selatan', 'DKI Jakarta')
    ->setPhone('081234567890');

[$status, $resp] = $client->ss_post('Patient', $patient->build());

// Build & post Encounter
$encounter = (new FhirEncounter)
    ->setStatus('finished')
    ->setClassCode('AMB')
    ->setSubjectReference("Patient/{$resp['id']}", 'John Doe')
    ->setParticipantIndividual('Practitioner/10009880728', 'Dr. Smith')
    ->setPeriodStart(now()->toIso8601String())
    ->setReasonText('Pemeriksaan umum');

[$status, $encResp] = $client->ss_post('Encounter', $encounter->build());
```

---

## FHIR Resources Supported

| # | Resource | GET | POST | PUT |
|---|----------|-----|------|-----|
| 1 | Patient | ✅ | ✅ | ✅ |
| 2 | Practitioner | ✅ | ✅ | ✅ |
| 3 | Organization | ✅ | ✅ | ✅ |
| 4 | Location | ✅ | ✅ | ✅ |
| 5 | Encounter | ✅ | ✅ | ✅ |
| 6 | Condition | ✅ | ✅ | ✅ |
| 7 | Observation | ✅ | ✅ | ✅ |
| 8 | Bundle | — | ✅ | — |

> **Coming soon (Phase 3 roadmap):** Procedure, MedicationRequest, MedicationDispense, ServiceRequest, Specimen, DiagnosticReport, AllergicIntolerance, ClinicalImpression, CarePlan, Goal, NutritionOrder, Composition, Immunization, and more.

---

## Master Data Included

| Data | Seeder | CSV |
|------|--------|-----|
| ICD-10 (18,547 codes) | ✅ | ✅ |
| Kode Wilayah Indonesia (91,592 entries) | ✅ | ✅ |

---

## Documentation

| Page | Description |
|------|-------------|
| [Installation](https://github.com/ivanwilliammd/satusehat-integration/wiki/Installation) | composer require, publish config, env setup |
| [Usage](https://github.com/ivanwilliammd/satusehat-integration/wiki/Usage) | OAuth, Patient, Encounter, Condition, Bundle, KFA |
| [Features](https://github.com/ivanwilliammd/satusehat-integration/wiki/Features) | Full feature matrix |
| [Onboarding](https://github.com/ivanwilliammd/satusehat-integration/wiki/Onboarding) | SATUSEHAT developer account setup |
| [Wiki](https://github.com/ivanwilliammd/satusehat-integration/wiki) | Full documentation |

---

## Example Projects

- [satusehat-laravel-example](https://github.com/ivanwilliammd/satusehat-laravel-example) — Laravel 10 full integration example

---

## Open Source Roadmap

This library is actively developed. See [ROADMAP.md](ROADMAP.md) for the full phased release plan from v3.x → v5.0.

---

## Upgrade Notes

### v3.2.0 (Laravel 13)
- ✅ Added Laravel 13 support (`illuminate/* ^13.0`)
- ✅ Fixed Dotenv path resolution — `base_path()` used inside Laravel, `getcwd()` fallback for standalone
- ✅ Replaced deprecated `Dotenv::createUnsafeImmutable` → `Dotenv::createImmutable`

### v3.0.0
- Multi-role access with per-API restrictions
- Privacy: Patient/Practitioner name censorship
- `Encounter.subject.display` and `Encounter.participant.individual` must match Master Patient Index / Master Nakes Index

---

## License

MIT — see [LICENSE](LICENSE)

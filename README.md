# SATUSEHAT Integration Library

> **Build SATUSEHAT FHIR R4 objects with ease — open source Laravel PHP library.**

[![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-9%E2%80%9313-blue)](https://laravel.com)
[![FHIR R4](https://img.shields.io/badge/FHIR-R4-orange)](https://hl7.org/fhir/R4/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![GitHub Actions](https://github.com/ivanwilliammd/satusehat-integration/workflows/Tests/badge.svg)](https://github.com/ivanwilliammd/satusehat-integration/actions)
[![Latest Version](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

---

## Overview

`satusehat-integration` is an **open-source** Laravel PHP library for integrating with **SATUSEHAT** — Indonesia's national health data platform powered by FHIR R4.

Built on the official [SATUSEHAT Platform Guidelines](https://satusehat.kemkes.go.id/platform/docs), it provides:
- OAuth2 authentication with SATUSEHAT IAM
- 32 composable **DataType** classes (Coding, CodeableConcept, Identifier, Reference, etc.)
- 51 **PayloadBuilder** classes for FHIR R4 resources (core + non-core: Coverage, Claim, Invoice, etc.)
- **SSRequest / SSResponse** — typed HTTP client with auto token-refresh, retry logic, and structured response handling
- Bundle operations for multi-resource transactions
- Master data: ICD-10, Kode Wilayah Indonesia, KFA v2

---

## Quick Install

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

---

## Architecture

### DataType Classes (`src/DataType/`)

Atomic FHIR R4 value objects. All extend `DataType` which provides a recursive `toArray()` method — nested DataType instances serialize to clean FHIR JSON automatically.

| Category | Classes |
|----------|---------|
| Core | `Coding`, `CodeableConcept`, `Identifier`, `Period`, `ContactPoint`, `Address`, `HumanName`, `Reference` |
| Quantity | `Quantity`, `SimpleQuantity`, `Range`, `Ratio`, `Age`, `Count`, `Distance`, `Duration`, `Money` |
| Structured | `Attachment`, `Narrative`, `Annotation`, `Timing`, `TimingRepeat`, `Dosage`, `DosageDoseAndRate` |
| Utility | `Extension`, `Signature`, `RelatedArtifact`, `Expression`, `TriggerDefinition`, `DataRequirement`, `ParameterDefinition` |

Example — `HumanName`:

```php
use Satusehat\Integration\DataType\HumanName;

$name = new HumanName(
    family: 'Doe',
    given: ['John', 'Michael'],
    use: 'official'
);
// $name->toArray() → ['family' => 'Doe', 'given' => ['John', 'Michael'], 'use' => 'official']
```

### PayloadBuilder Pattern (`src/Builder/`)

Fluent builder for each FHIR resource. Each builder accepts DataType instances and exposes a `build()` method returning a clean FHIR JSON payload.

```php
$patient = (new PayloadBuilderPatient)
    ->setId('12345678-1234-1234-1234-123456789012')
    ->addIdentifier($identifier)
    ->addName($name)
    ->setGender('male')
    ->setBirthDate('1990-01-15')
    ->addAddress($address)
    ->addTelecom($phone)
    ->build();
```

### SSRequest / SSResponse

- **SSRequest** — HTTP client with `get()`, `post()`, `put()`, `delete()` methods. Handles OAuth2 bearer tokens, auto-refresh on HTTP 401, retry with exponential backoff on 429/5xx, configurable timeout.
- **SSResponse** — Structured response wrapper: `isSuccess()` / `isError()`, `getErrorMessages()`, `getResourceId()`.

```php
use Satusehat\Integration\SSRequest\SSRequest;
use Satusehat\Integration\OAuth2Client;

$oauth2 = new OAuth2Client();
$ss = new SSRequest($oauth2);

$resp = $ss->post('Patient', $patientPayload);

if ($resp->isSuccess()) {
    $patientId = $resp->getResourceId();
} else {
    foreach ($resp->getErrorMessages() as $msg) {
        // handle error
    }
}
```

---

## Usage Examples

### Patient

```php
use Satusehat\Integration\SSRequest\SSRequest;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\Builder\PayloadBuilderPatient;

$ss = new SSRequest(new OAuth2Client());

// Compose DataType objects
$identifier = new Identifier(
    system: 'https://fhir.kemkes.go.id/id/NIK',
    value: '3312345678901234'
);

$name = new HumanName(
    family: 'Doe',
    given: ['John'],
    use: 'official'
);

$phone = new ContactPoint(
    system: 'phone',
    value: '081234567890',
    use: 'mobile'
);

$address = new Address(
    use: 'home',
    line: ['Jl. Sudirman No.1'],
    city: 'Jakarta Selatan',
    district: 'Kebayoran Baru',
    state: 'DKI Jakarta',
    postalCode: '12190',
    country: 'ID'
);

// Build Patient resource
$patient = (new PayloadBuilderPatient)
    ->addIdentifier($identifier)
    ->addName($name)
    ->setGender('male')
    ->setBirthDate('1990-01-15')
    ->addTelecom($phone)
    ->addAddress($address)
    ->setActive(true)
    ->build();

$resp = $ss->post('Patient', $patient);
```

### Encounter

```php
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\Builder\PayloadBuilderEncounter;

$subject = new Reference(
    reference: "Patient/{$patientId}",
    display: 'John Doe'
);

$participant = new Reference(
    reference: 'Practitioner/10009880728',
    display: 'Dr. Smith'
);

$class = new CodeableConcept(
    coding: [new \Satusehat\Integration\DataType\Coding(
        system: 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
        code: 'AMB',
        display: 'ambulatory'
    )]
);

$encounter = (new PayloadBuilderEncounter)
    ->setStatus('finished')
    ->setClass($class)
    ->setSubject($subject)
    ->addParticipantIndividual($participant)
    ->setPeriodStart(now()->toIso8601String())
    ->addReasonText('Pemeriksaan umum')
    ->build();

$resp = $ss->post('Encounter', $encounter);
```

### Observation

```php
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\Builder\PayloadBuilderObservation;

$category = new CodeableConcept(
    coding: [new Coding(
        system: 'http://terminology.hl7.org/CodeSystem/observation-category',
        code: 'vital-signs',
        display: 'Vital Signs'
    )]
);

$code = new CodeableConcept(
    coding: [new Coding(
        system: 'http://loinc.org',
        code: '8867-4',
        display: 'Heart rate'
    )]
);

$value = new Quantity(
    value: 72,
    unit: 'beats/minute',
    system: 'http://unitsofmeasure.org',
    code: '/min'
);

$observation = (new PayloadBuilderObservation)
    ->setStatus('final')
    ->addCategory($category)
    ->setCode($code)
    ->setSubject($subject)
    ->setEncounter($encounterRef)
    ->setEffectiveDateTime(now()->toIso8601String())
    ->setValueQuantity($value)
    ->addReferenceRange(
        low: new Quantity(value: 60, unit: 'bpm', system: 'http://unitsofmeasure.org', code: '/min'),
        high: new Quantity(value: 100, unit: 'bpm', system: 'http://unitsofmeasure.org', code: '/min'),
        text: '60-100 bpm'
    )
    ->build();

$resp = $ss->post('Observation', $observation);
```

---

## Old Way vs v4 Way

### Before v4 (raw array)

```php
use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client();

$patient = [
    'resourceType' => 'Patient',
    'identifier' => [['system' => '...', 'value' => '...']],
    'name' => [['family' => 'Doe', 'given' => ['John'], 'use' => 'official']],
    // ... manually build every nested structure
];

[$status, $resp] = $client->ss_post('Patient', $patient);

// Check response by inspecting raw array
if ($status >= 200 && $status < 300) {
    $id = $resp['id'] ?? null;
}
```

### v4 (DataType + PayloadBuilder + SSResponse)

```php
use Satusehat\Integration\SSRequest\SSRequest;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\HumanName;
use Satusehat\Integration\Builder\PayloadBuilderPatient;

$ss = new SSRequest(new OAuth2Client());

$patient = (new PayloadBuilderPatient)
    ->addIdentifier(new Identifier(system: '...', value: '...'))
    ->addName(new HumanName(family: 'Doe', given: ['John'], use: 'official'))
    ->setGender('male')
    ->build();

$resp = $ss->post('Patient', $patient);

if ($resp->isSuccess()) {
    $id = $resp->getResourceId();
} else {
    foreach ($resp->getErrorMessages() as $msg) { /* log */ }
}
```

**Key improvements in v4:**
- DataType classes guarantee valid FHIR structure
- `toArray()` handles nested serialization recursively
- SSResponse gives typed, structured access to responses
- Automatic token refresh and retry on network failures
- Fully fluent builder API

---

## Supported FHIR Resources

All 51 resources fully implemented via PayloadBuilder classes. Core (✅) + Non-Core (💼):

| # | Resource | GET | POST | PUT | PATCH | Notes |
|---|----------|-----|------|-----|-------|-------|
| 1 | Patient | ✅ | ✅ | ✅ | ✅ | MPI |
| 2 | Practitioner | ✅ | ✅ | ✅ | ✅ | SDMK |
| 3 | PractitionerRole | ✅ | ✅ | ✅ | ✅ | |
| 4 | Organization | ✅ | ✅ | ✅ | ✅ | MSI |
| 5 | Location | ✅ | ✅ | ✅ | ✅ | |
| 6 | Encounter | ✅ | ✅ | ✅ | ✅ | |
| 7 | Condition | ✅ | ✅ | ✅ | ✅ | |
| 8 | Observation | ✅ | ✅ | ✅ | ✅ | |
| 9 | Procedure | ✅ | ✅ | ✅ | ✅ | |
| 10 | MedicationRequest | ✅ | ✅ | ✅ | ✅ | |
| 11 | Bundle | — | ✅ | — | — | batch/transaction |
| 12 | CarePlan | ✅ | ✅ | ✅ | ✅ | |
| 13 | Composition | ✅ | ✅ | ✅ | ✅ | RME |
| 14 | ClinicalImpression | ✅ | ✅ | ✅ | ✅ | |
| 15 | Goal | ✅ | ✅ | ✅ | ✅ | |
| 16 | NutritionOrder | ✅ | ✅ | ✅ | ✅ | |
| 17 | AllergyIntolerance | ✅ | ✅ | ✅ | ✅ | |
| 18 | Device | ✅ | ✅ | ✅ | ✅ | |
| 19 | DiagnosticReport | ✅ | ✅ | ✅ | ✅ | |
| 20 | DocumentReference | ✅ | ✅ | ✅ | ✅ | |
| 21 | EpisodeOfCare | ✅ | ✅ | ✅ | ✅ | |
| 22 | FamilyMemberHistory | ✅ | ✅ | ✅ | ✅ | |
| 23 | GenomicStudy | ✅ | ✅ | ✅ | ✅ | |
| 24 | Group | ✅ | ✅ | ✅ | ✅ | |
| 25 | Immunization | ✅ | ✅ | ✅ | ✅ | |
| 26 | Medication | ✅ | ✅ | ✅ | ✅ | |
| 27 | MedicationAdministration | ✅ | ✅ | ✅ | ✅ | |
| 28 | MedicationDispense | ✅ | ✅ | ✅ | ✅ | |
| 29 | MedicationStatement | ✅ | ✅ | ✅ | ✅ | |
| 30 | MolecularSequence | ✅ | ✅ | ✅ | ✅ | |
| 31 | QuestionnaireResponse | ✅ | ✅ | ✅ | ✅ | |
| 32 | RelatedPerson | ✅ | ✅ | ✅ | ✅ | |
| 33 | RiskAssessment | ✅ | ✅ | ✅ | ✅ | |
| 34 | ServiceRequest | ✅ | ✅ | ✅ | ✅ | |
| 35 | Specimen | ✅ | ✅ | ✅ | ✅ | |
| 36 | Substance | ✅ | ✅ | ✅ | ✅ | |
| 37 | Task | ✅ | ✅ | ✅ | ✅ | |
| 38 | Account | ✅ | ✅ | ✅ | ✅ | |
| 39 | ImagingStudy | 💼 | 💼 | 💼 | 💼 | non-core |
| 40 | Coverage | 💼 | 💼 | 💼 | 💼 | non-core |
| 41 | CoverageEligibilityRequest | 💼 | 💼 | 💼 | 💼 | non-core |
| 42 | CoverageEligibilityResponse | 💼 | 💼 | 💼 | 💼 | non-core |
| 43 | Claim | 💼 | 💼 | 💼 | 💼 | non-core |
| 44 | ClaimResponse | 💼 | 💼 | 💼 | 💼 | non-core |
| 45 | ChargeItem | 💼 | 💼 | 💼 | 💼 | non-core |
| 46 | ChargeItemDefinition | 💼 | 💼 | 💼 | 💼 | non-core |
| 47 | ChargeItemResponse | 💼 | 💼 | 💼 | 💼 | non-core |
| 48 | PaymentNotice | 💼 | 💼 | 💼 | 💼 | non-core |
| 49 | PaymentReconciliation | 💼 | 💼 | 💼 | 💼 | non-core |
| 50 | Invoice | 💼 | 💼 | 💼 | 💼 | non-core |

---

## Documentation

| Page | Description |
|------|-------------|
| [CHANGELOG](CHANGELOG.md) | Version history and release notes |
| [Wiki](https://github.com/ivanwilliammd/satusehat-integration/wiki) | Full documentation |
| [Installation](https://github.com/ivanwilliammd/satusehat-integration/wiki/Installation) | composer require, publish config, env setup |
| [Usage](https://github.com/ivanwilliammd/satusehat-integration/wiki/Usage) | OAuth, Patient, Encounter, Condition, Bundle, KFA |
| [Features](https://github.com/ivanwilliammd/satusehat-integration/wiki/Features) | Full feature matrix |
| [Onboarding](https://github.com/ivanwilliammd/satusehat-integration/wiki/Onboarding) | SATUSEHAT developer account setup |
| [ROADMAP.md](ROADMAP.md) | Phased release plan v3.x → v5.0 |

## External Resources

- [HL7 FHIR R4 Specification](https://hl7.org/fhir/R4/)
- [SATUSEHAT Platform Docs](https://satusehat.kemkes.go.id/platform/docs)
- [SATUSEHAT FHIR Base URL](https://api-satusehat-dev.dto.kemkes.go.id)
- [satusehat-laravel-example](https://github.com/ivanwilliammd/satusehat-laravel-example) — Laravel 10 full integration example

---

## Contributing

Contributions are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

---

## Support

Open an [issue](https://github.com/ivanwilliammd/satusehat-integration/issues) for bugs or feature requests.

---

## License

MIT — see [LICENSE](LICENSE).

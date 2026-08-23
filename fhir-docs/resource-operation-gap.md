# Resource Operation Gap Matrix

Current SDK architecture:

- Generic transport: `Satusehat\Integration\SSRequest\SSRequest`
  - `get(string $url, array $params = [])`
  - `post(string $url, array $body)`
  - `put(string $url, array $body)`
  - `delete(string $url, array $body = [])`
  - `patch(string $url, array $patchPayload)`
  - `patchFhirPath(string $url, array $fhirPathPayload)`
  - `postBundle(string $url, array $bundlePayload)`
- Per-resource `src/FHIR/*` classes: mostly payload creation + `post/put` helpers.
- Per-resource `src/Builder/PayloadBuilder*` classes: fluent payload builders.

## Answer

Per-resource convenience methods for `get/search/query by ...` are **not yet complete**.

What exists today:

```php
$ss = new SSRequest(new OAuth2Client());
$patient = $ss->get('Patient/123');
$patients = $ss->get('Patient', ['identifier' => 'https://fhir.kemkes.go.id/id/nik|317...']);
$observations = $ss->get('Observation', ['patient' => 'Patient/123', 'category' => 'laboratory']);
```

What is missing:

```php
Patient::getById($id)
Patient::searchByNik($nik)
Observation::searchByPatient($patientId)
Encounter::searchBySubject($patientId)
MedicationRequest::searchByPatient($patientId)
```

## Planned shape

Add `src/FHIR/ResourceClient.php`:

```php
$client = new ResourceClient(new SSRequest(new OAuth2Client()));

$client->resource('Patient')->get('123');
$client->resource('Patient')->search(['identifier' => 'system|value']);
$client->resource('Observation')->search(['patient' => 'Patient/123']);
```

Optional typed wrappers later:

```php
$client->patients()->byNik($nik);
$client->observations()->byPatient($patientId);
$client->encounters()->bySubject($patientId);
```

## Rule

Do not add one-off query methods into each builder. Builders remain payload-only.

Resource query belongs in:

- `SSRequest` for generic raw transport
- `ResourceClient` for generic resource operations
- typed shortcut clients only for high-value SATUSEHAT workflows

## Status

- Generic get/search via `SSRequest::get()` — **available**
- Per-resource typed get/search/query helpers — **missing**
- Search parameter reference — see `fhir-docs/resource-search-parameters.md`
- FHIR R4 cardinality reference — see `fhir-docs/resource-cardinality.md`
- SATUSEHAT supported search subset — TODO, map from SATUSEHAT docs

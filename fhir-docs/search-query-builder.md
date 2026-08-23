# SearchQueryBuilder

Validated FHIR search query builder.

Allowlist source:

- `resources/search-parameters.json`
- generated from HL7 FHIR R4 `fhir-docs/sources/hl7-r4/search-parameters.json`

SATUSEHAT-specific narrowing can be added later as override file.

## Usage

```php
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Query\SearchQueryBuilder;
use Satusehat\Integration\SSRequest\SSRequest;

$ss = new SSRequest(new OAuth2Client());

$query = SearchQueryBuilder::for('Patient')
    ->where('identifier', 'https://fhir.kemkes.go.id/id/nik|317...')
    ->count(10)
    ->toArray();

$response = $ss->get('Patient', $query);
```

## Helpers

```php
SearchQueryBuilder::for('Observation')
    ->reference('patient', 'Patient', '123')
    ->token('category', 'http://terminology.hl7.org/CodeSystem/observation-category', 'laboratory')
    ->date('date', 'ge', '2026-01-01')
    ->sort('-date')
    ->count(50)
    ->toArray();
```

## Invalid params fail fast

```php
SearchQueryBuilder::for('Patient')->where('notallowed', 'x');
// InvalidArgumentException: Search parameter 'notallowed' is not allowed for Patient
```

## Why separate builder

PayloadBuilder remains payload-only. Search/query belongs to a query builder so SDK generation stays clean:

- `PayloadBuilderPatient` → create/update body
- `SearchQueryBuilder::for('Patient')` → GET/search params
- `SSRequest::get('Patient', $query)` → transport

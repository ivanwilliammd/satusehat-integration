# R4 conformance audit of the PHP builders

Parity answers one question: do the four ports produce the same payload? It does not
answer a second question: is that payload FHIR? This audit answers the second one, by
comparing what every `PayloadBuilder<X>.php` writes against the official R4 definitions
and by validating the golden payloads against the official R4 JSON Schema.

Reproduce with:

```bash
python3 scripts/audit-r4-conformance.py --list
```

Definitions are read from `$R4_DIR` (default `/tmp/r4def`) and fetched from
`https://hl7.org/fhir/R4/definitions.json.zip` when absent.

## Headline numbers

| Measure | Count |
| --- | --- |
| Builders under `src/Builder` | 141 |
| Resource types that R4 never defined | 7 |
| Builders writing at least one element R4 does not define | 101 |
| Boilerplate-only builders (the same six generic setters, nothing resource-specific) | 61 |
| Golden payloads rejected by the official R4 JSON Schema | 122 of 153 |

Validation errors across the 153 golden payloads:

| Error class | Errors | Files affected |
| --- | --- | --- |
| Unknown element for that resource | 110 | 101 |
| Missing required element | 83 | 46 |
| Wrong type (scalar where array, object where string, …) | 38 | 28 |
| Value outside the bound codeset | 14 | 13 |
| `resourceType` absent from R4 | 7 | 7 |

"Missing required element" is largely a property of the test payloads rather than of
the builders: each golden exercises a handful of setters, so a `Claim` without
`insurance` is expected. The other classes are builder defects.

## Resource types R4 never defined

```
BillingStatus · ChargeItemResponse · GenomicStudy · ImagingManifest
ImagingSelection · PurificationDecision · ResourceGuide
```

`GenomicStudy`, `ImagingManifest` (R4 has `ImagingStudy`, and `ImagingManifest` was
removed after STU3) and `ImagingSelection` (R5) may be deliberate SATUSEHAT additions;
`BillingStatus`, `ChargeItemResponse`, `PurificationDecision` and `ResourceGuide` have
no counterpart anywhere in FHIR R4 or R5. Their builders produce payloads no FHIR
server will accept.

## The recurring defect: generic setters copied into every resource

Most of the unknown-element errors come from a small, repeated set of setters that were
copied into builders where they do not apply:

| Element written | Builders | Why it is wrong |
| --- | --- | --- |
| `code` | 52 | The resource names it `event`, `type`, `category`, `serviceType`, … |
| `encounter` | 49 | R4 uses `context` (or has no encounter at all, e.g. `Binary`) |
| `subject` | 46 | Not every resource is patient-scoped |
| `status` | 33 | Many resources have no `status`; some name it `actuality` |
| `identifier` | 20 | e.g. `OperationOutcome`, `Binary`, `CapabilityStatement` have none |

61 builders consist of exactly these six methods and nothing else — `setId`,
`addIdentifier`, `setStatus`, `setCode`, `setSubject`, `setEncounter`. They count as
"implemented" in `docs/PARITY.md` because a byte-identical port of a wrong setter is
still parity, but they cannot build their own resource.

Nested defects of the same family, each proved by a golden payload:

| Builder | Payload written | R4 requirement |
| --- | --- | --- |
| `Claim` | `item.quantity.currency` | `Quantity` → `value` + `unit` |
| `Claim` | `item.location` as CodeableConcept | `Address` |
| `Claim` | `insurance` as an object holding only `account` | an array of entries, each with `sequence`, `coverage`, `focal` |
| `ClaimResponse` | adjudication objects appended to `item` | `item[].adjudication[]` |
| `ClaimResponse` | `total[].value` | `total[].amount.value` |
| `ClaimResponse` | `payment.reference` | `payment.identifier` |
| `ChargeItem` | `quantity` as a bare number | `Quantity` |
| `ChargeItemDefinition` | `instance[].display`, `instance[].timingDateTime` | inside `instance.reference`, or absent |
| `FamilyMemberHistory` | `ageAge` + `ageUnit` | one `Age` object |
| `ImagingStudy` | `series[].status` | not an R4 element of `series` |
| `Invoice` | `lineItem[].quantity`, `lineItem[].service` | `chargeItem[x]` references |
| `DocumentReference` | `content[].format` as CodeableConcept | `Coding` |
| `Endpoint` | `connectionType` as CodeableConcept | `Coding` |

## What this does not change

Port behaviour is unchanged and parity still holds: all four ports reproduce these
payloads byte for byte, and the golden files record that agreement. What changes is the
claim attached to the number 141. "141 builders implemented" means the surface exists
in every port; it does not mean 141 resources can be sent to a FHIR server.

## Suggested order of repair

1. Fix the seven resource types that R4 does not define — either map them onto the
   resource R4 does define, or mark them as SATUSEHAT-local and stop counting them
   toward R4 coverage.
2. Replace the six generic setters in the 61 boilerplate builders with the elements
   each resource actually declares, starting with resources SATUSEHAT actually accepts.
3. Fix the nested defects in the table above in the canonical PHP builder, then
   regenerate goldens and let the four ports follow in one pass.

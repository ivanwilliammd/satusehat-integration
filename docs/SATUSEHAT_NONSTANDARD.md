# Non-standard SATUSEHAT objects

SATUSEHAT runs on FHIR R4, but not on R4 alone. Two additional layers are legitimate and mandatory,
and neither is **caught** by an R4 conformance audit because neither is part of the HL7 specification.

## 1. Kemenkes identifier systems

| Keperluan | `identifier.system` |
| --- | --- |
| NIK | `https://fhir.kemkes.go.id/id/nik` |
| Paspor | `https://fhir.kemkes.go.id/id/paspor` |
| Kartu Keluarga | `https://fhir.kemkes.go.id/id/kk` |
| IHS Number | `https://fhir.kemkes.go.id/id/patient-ihs-number` |

Source: the SATUSEHAT Patient resource documentation
(<https://satusehat.kemkes.go.id/platform/docs/id/fhir/resources/patient>) — the JSON example on that
page uses `use: official` together with all four systems.

Any other `system` (for example `https://fhir.kemkes.go.id/id/nik-pasien`) produces an identifier that
patient search will never find, even though the payload passes R4 validation.

## 2. Administrative address extensions

| Extension | Canonical |
| --- | --- |
| Administrative code | `https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode` |
| Birth place | `https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace` |
| Citizenship status | `https://fhir.kemkes.go.id/r4/StructureDefinition/citizenshipStatus` |
| Religion | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-religion` |
| Disability | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-disability` |
| Citizenship (patient) | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-citizenship` |
| Birth time | `https://fhir.kemkes.go.id/r4/StructureDefinition/patient-birthTime` |

Religion, disability, and patient-citizenship are `CodeableConcept` (0..1); birthTime is a `dateTime`.
`SatusehatNonStandard::codeableExtension()` and `birthTimeExtension()` build those wrappers and refuse
a canonical that is not one of the listed extensions — a typo here means the extension is silently
ignored by the platform. Sources: the Patient profile page of the SATUSEHAT FHIR R4 Implementation
Guide.

`administrativeCode` nests: one parent extension, then sub-extensions whose `url` is **fixed** to
`province`, `city`, `district`, `village`, `rt`, `rw`, each carrying its value as `valueString`.

```json
{
  "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
  "extension": [
    { "url": "province", "valueString": "32" },
    { "url": "city",     "valueString": "3273" },
    { "url": "district", "valueString": "327301" },
    { "url": "village",  "valueString": "3273010001" }
  ]
}
```

Source: the AdministrativeCode extension page of the SATUSEHAT FHIR R4 Implementation Guide
(<https://simplifier.net/guide/SATUSEHAT-FHIR-R4-Implementation-Guide/Home/Extensions/AdministrativeCode.page.md>).

## How the code guards them

These values are not typed loose in code. `data/satusehat/nonstandard.json` carries them together with
their source and fetch date; `src/Terminology/SatusehatNonStandard.php` provides constants and
helper `administrativeCodeExtension()`; and `tests/Terminology/SatusehatNonStandardTest.php` asserts
that the constants equal the reference file.

The consequence: changing a URL from memory makes the test fail until the reference file changes too —
and a change to the reference is visible in the diff.

## 3. Required rules that can be checked from the payload

An R4 audit answers "does this element exist in R4". It does not answer "is this content the shape
SATUSEHAT accepts". `SatusehatRules::violations()` checks what can be decided without other data.

| Resource | Aturan |
| --- | --- |
| Encounter | `class` is a `Coding` in v3-ActCode; `status` from EncounterStatus |
| Observation | `status` · `code.coding` · `subject` → Patient · `encounter` |
| Patient | `identifier` · `name` |
| Condition | `code.coding` · `subject` → Patient · `encounter` |
| Procedure | `status` · `code.coding` · `subject` → Patient · `encounter` |
| DiagnosticReport | `status` · `code.coding` · `subject` → Patient · `encounter` |
| Immunization | `status` · `vaccineCode.coding` · `patient` → Patient · one of `occurrence[x]` |
| MedicationRequest | `status` · `intent` · `subject` → Patient |

The rules are **not written in the class**. `data/satusehat/required-rules.json` carries each item with
its kind (`code`, `content`, `exists`, `reference`, `system`, `one_of`) and the URL of the documentation
it came from; `SatusehatRules` only executes those items. Adding a resource means adding data, not a new
method. Tests assert that every item has a source, that its kind is known, and that the code constants
do not drift from the file.

`encounter` is required on Observation/Condition/Procedure/DiagnosticReport according to SATUSEHAT even
though R4 calls it optional. The difference is deliberate, and the violation message says so, so it is
not mistaken for a bug in this library.

## 4. All five ports use the same rules

| Port | Cara membawa aturan |
| --- | --- |
| PHP | read from `data/satusehat/required-rules.json` |
| Node | `resolveJsonModule` — JSON disalin `tsc` ke `dist` |
| Python | generated artefact `_rules_generated.py` + SHA |
| Go | `go:embed` |
| Rust | `include_str!` at compile time |

Five copies can drift. `scripts/check-rules-drift.sh` compares the sha256 of all five files (missing
repositories are skipped, so it is safe to run in a single-repository CI) and exits 1 when any differ.

Today all five match: `335561b0a605944d`.

## Limits not yet closed

- The required rules cover 8 resources; the rest are not mapped.
- Conditional rules (for example `Procedure.performer` required only when a procedure exists) cannot be
  decided without context, so they are not checked.
- Rules that need data outside the payload (for example an organisation identifier that must exist in
  the facility master) cannot be decided by this library, and are not claimed.


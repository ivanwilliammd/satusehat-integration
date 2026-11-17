# Parity: legacy facade vs native PayloadBuilder

This document answers one question: **if a resource is built through the legacy facade class
(`Satusehat\Integration\FHIR\*`) and through the native `PayloadBuilder*`, is the FHIR payload the
same?** The answer has to be a number that can be re-run, not a memory.

Test: `tests/FHIR/FacadeParityTest.php` — run with

```bash
vendor/bin/phpunit tests/FHIR/FacadeParityTest.php
```

## Shape of the two paths

| | Legacy facade | Native builder |
|---|---|---|
| Class | `Satusehat\Integration\FHIR\Patient` | `Satusehat\Integration\Builder\PayloadBuilderPatient` |
| Input | scalar arguments (`setCode($code, $display, $text)`) | `DataType` objects (`setCode(CodeableConcept)`) |
| Output | `json()` → **JSON string** (pretty printed) | `json()` → **array** |
| Required-field validation | `json()` throws `FHIRMissingProperty` | no validation; the caller checks |
| Sending to the API | `post()` / `put()` on the facade | `SSRequest::post()` in the caller |

Because the output types differ, the parity test **decodes** the facade output and compares arrays
(`json_decode($facade->json(), true)` against `$builder->json()`), after canonicalising both with a
recursive `ksort` so key order is not part of the comparison.

## Measured status (2026-11-17)

- Facade classes in `src/FHIR/`: **50**
- Facades carrying a `public static function build(): PayloadBuilder…` shim: **6**
- Resources with a payload parity test (facade vs builder, identical input): **6**
- Facades **without** a shim: **44** — every one of them already has its `PayloadBuilder` counterpart,
  so the work is additive: one `build()` method plus one parity case per resource

Shimmed and tested — **this list is guarded by a test**
(`test_shim_list_matches_documented_parity_matrix`): adding a shim without updating this file fails the
suite.

- shim: `AllergyIntolerance`
- shim: `Device`
- shim: `DiagnosticReport`
- shim: `Organization`
- shim: `Patient`
- shim: `Procedure`

## Pitfalls already proven

1. **`Annotation::__construct($author, ?string $text, ?string $time)` — the first argument is the
   AUTHOR, not the text.** Writing `new Annotation('note')` produces `note: [{"author":"note"}]`, and
   FHIR R4 requires `Annotation.text`; `author` is optional filler. The payload "looks right" until a
   FHIR checker rejects it. Use `new Annotation(null, 'note')` or pass the string directly:
   `addNote('note')`.
2. **`addNote()` used to be inconsistent between builders** — 5 accepted `string`, 8 accepted
   `Annotation`. Since 2026-10-28 both accept `Annotation|string` (a type widening, not a behaviour
   change), so old code keeps working and plain text no longer lands in `author`.
3. **Key order must not be tested.** `json_encode` preserves insertion order; facade and builder insert
   in different orders for the same resource. Compare arrays after a recursive `ksort`, never the JSON
   string.
4. **`meta.profile` is not parity.** Some facades insert the SATUSEHAT profile into `meta`; builders do
   not add it automatically. If a facade with `meta` appears later, that difference must be stated as a
   DELIBERATE difference in this document — never normalised away silently inside the test.

## Adding the next resource (44 left)

1. Add `public static function build(): PayloadBuilderX` to `src/FHIR/X.php` (pattern:
   `Device::build()`).
2. Add one case to `tests/FHIR/FacadeParityTest.php`: run the SAME setter sequence on the facade and the
   builder, then `assertParitySame()`.
3. Add a `- shim: `X`` line to this document; without it
   `test_shim_list_matches_documented_parity_matrix` fails.
4. Prove it red first when in doubt: remove the builder-side setter call temporarily, and the test must
   fail on the right path (`/note/0/text: facade=… builder=<absent>`).

## Defects found while building this parity test

1. **`Satusehat\Integration\FHIR\Element` never existed.** `Immunization`,
   `MedicationAdministration`, `MedicationDispense`, and `Specimen` declare `extends Element`, but that
   class is not in the repository **and never was in git history**
   (`git log --all -- src/FHIR/Element.php` is empty). All four facades therefore died on use:
   `Class "Satusehat\Integration\FHIR\Element" not found`. The facades were never tested directly —
   the tests covered their `PayloadBuilder`, not the facade class. `src/FHIR/Element.php` was added (a
   six-line base: `$data` + `toArray()`) and is guarded by
   `FacadeParityTest::test_element_facades_can_be_instantiated()`.
2. **Two `json()` styles among facades.** Facade classes inheriting from `OAuth2Client` return a
   **string** (`Device`, `AllergyIntolerance`, `DiagnosticReport`), while the four `Element` subclasses
   return an **array**. A caller treating facades uniformly will break. Do not "normalise" by changing
   the return type of old classes — that breaks existing callers; the parity test decodes both, and new
   code should use `PayloadBuilder`, which always returns an array.

## Measured technical debt (not addressed in this cycle)

- **754 parameters typed `string|array|int|bool|float … = null`** across 172 files in `src/` trigger
  `Implicitly marking parameter … as nullable is deprecated` on PHP 8.4. The suite stays green
  (deprecation is not a warning), but this becomes an error in PHP 9. The fix is mechanical
  (`?string $x = null`) and touches almost all 49 facade classes; do it per domain, with before/after
  test numbers.
- **44 facades have no `build()` shim** (table above); all of them already have their `PayloadBuilder`,
  so no resource lacks a native path.

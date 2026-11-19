# FHIR versioning: R4 is the reference, R5 is prepared

SATUSEHAT runs on FHIR R4, so **R4 is the primary reference of this SDK**: every class without a
version prefix — `Satusehat\Integration\Builder\*`, `Satusehat\Integration\DataType\*` — is R4.

R5 is prepared without changing a single existing call site.

## The promise that holds

Using R5 means changing the `use` line:

```php
// R4 (reference, unchanged)
use Satusehat\Integration\Builder\PayloadBuilderPatient;

// R5 — the only line that changes
use Satusehat\Integration\R5\Builder\PayloadBuilderPatient;
```

Everything else stays: class name, method names, argument order, payload shape. That is not a hope —
`tests/Version/R5NamespaceTest.php` enforces it for every class:

1. an R5 class exists for all 142 builders and 34 datatypes;
2. the R5 class **extends** the R4 class (`is_subclass_of`);
3. its public method surface is **identical** to the R4 class (reflection);
4. generated output may not diverge from the canonical source (`--check`).

## Why inheritance, not a copy

A copy forks: a fix in R4 never reaches R5, and two behaviours start to differ with nobody deciding
it. Inheritance makes R5 carry R4 unchanged — as long as R5 really changes nothing, nothing can drift.

## When R5 genuinely differs

One resource, one file. Remove the `@generated` marker from
`src/R5/Builder/PayloadBuilderX.php` and write the implementation. The generator stops touching that
file (it only manages files carrying `@generated`), and the reflection test then demands that the
difference be stated rather than hidden.

The same rule applies to datatypes: `src/R5/DataType/CodeableConcept.php`, and so on.

**No R5 difference is written yet**, because none has been verified against the R5 specification.
Copying a guess into code produces an R5 class that claims something nobody checked. Differences are
added one at a time, each with its R5 element reference.

## Limits worth stating

- The R5 layer does **not** yet carry elements that exist only in R5. Until that is written,
  `R5\Builder\X` produces an R4 payload — and that is the tested behaviour.
- Validation, parsing, and queueing are not split per version; they work on the payload shape, not on
  the version definition.

## R6: tracked, deliberately not built

As of 2026-11-19 the published directory lists R6 as **Draft only** — `6.0.0-snapshot1` and
`6.0.0-ballot5`, both dated 2026-07-17 (https://hl7.org/fhir/directory.html). The current published
specification is still `5.0.0` (R5, trial-use). Nothing in R6 is normative, so nothing here targets it.

The plan is one sentence: **R6 becomes work the day a normative version exists**, and until then the only
allowed R6 activity is the isolation work R5 already has — a namespace, a generated subclass layer, and a
test that proves each class exists and changes nothing. No `src/R6/` directory is created before that, because
an empty namespace that "prepares for R6" is a promise the tests cannot check.

Trigger to start, in order:

1. `hl7.org/fhir/directory.html` lists an R6 version with status **N** (normative) or **T** (trial-use);
2. a diff pass over `CapabilityStatement`/resource shapes names what actually differs from R4;
3. each difference is written one at a time with its element reference — never copied from a draft;
4. `Version/R6NamespaceTest` mirrors the R5 test before any R6 class is used.

Until step 1 happens, `satusehat-integration` stays an R4 SDK with an R5 layer and says so in every README.

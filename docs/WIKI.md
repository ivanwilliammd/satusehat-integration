# Wiki: what it guarantees, and how to update it

The GitHub wiki (`ivanwilliammd/satusehat-integration.wiki.git`) is the user-facing documentation of
the package. Pages: `Home`, `Installation`, `Onboarding`, `Usage`, `Features`, `PayloadBuilder`,
`FHIR-Resources`, `FHIR-DataTypes`, `SSRequest-SSResponse`, `Validation-Rule-Numbers`,
`Error-Handling`, `Parity`, `Status-Parity`.

## Numbers that must never be typed by hand

The PayloadBuilder count is the fastest-rotting number in this repository: the wiki once said **112**
while `src/Builder/` held **141**, and the README said **51**. All three are now guarded by a machine,
not by memory.

| Source of a number | Guarded by |
|---|---|
| Wiki `PayloadBuilder` (index per class) | `docs/scripts/build-wiki-builder-index.php` — the block between `GENERATED:builder-index` markers |
| Counts in `Home`, `Features`, `FHIR-Resources` | generated and checked by the same script |
| Numbers in `README.md` | `tests/Unit/WikiBuilderIndexTest.php` (fails when they differ) |

## Commands

```bash
# print the index without writing anything
php docs/scripts/build-wiki-builder-index.php

# rewrite the block in a local wiki page (wiki-clone/ or a git clone of .wiki.git)
php docs/scripts/build-wiki-builder-index.php --splice=wiki/PayloadBuilder.md

# check whether the wiki is stale; exit 1 when it is (usable in CI / pre-push)
php docs/scripts/build-wiki-builder-index.php --check=wiki/PayloadBuilder.md

# Status-Parity page: R5 numbers are measured from src/R5 (files without @generated differ from R4)
php docs/scripts/build-wiki-status-parity.php --splice=Status-Parity.md
php docs/scripts/build-wiki-status-parity.php --check=Status-Parity.md
```

The target file **must** carry these markers; without them the script exits 1 instead of guessing a
position:

```
<!-- GENERATED:builder-index:start -->
<!-- GENERATED:builder-index:end -->
```

`Status-Parity` uses the same pattern (`GENERATED:status-parity`). Its numbers come from `src/R5`, and
the distinction it draws is not a file count but the **presence of the `@generated` marker** — the same
definition `scripts/generate_r5_namespace.py` uses to stop overwriting files people own. The recipe for
adding an R5 difference lives on that page.

## Rules when adding a PayloadBuilder

1. Add `src/Builder/PayloadBuilder<Type>.php` as usual.
2. A NON-FHIR builder **must** say `NON-FHIR` in its class docblock — that marker is how the script
   separates 139 FHIR R4 builders from the 2 SATUSEHAT-specific ones.
3. Run `--splice` on the wiki page, then `--check` before committing.

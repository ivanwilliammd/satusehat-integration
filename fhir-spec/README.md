# FHIR Spec — Canonical Source of Truth

This directory is the **single source of truth** for FHIR R4 type definitions and SATUSEHAT-specific resource profiles.

Used across all language SDKs:
- `satusehat-integration` — PHP SDK (primary)
- `satusehat-integration-node` — Node.js/TypeScript SDK
- `satusehat-integration-go` — Go SDK
- `satusehat-integration-python` — Python SDK

## Structure

```
fhir-spec/
├── types/       # Platform-agnostic FHIR DataType definitions
└── profiles/    # SATUSEHAT-specific resource profiles (50 resources scaffolded)
```

Related canonical docs live in:

```
fhir-docs/
├── sources/hl7-r4/                 # downloaded HL7 R4 source bundles
├── resource-cardinality.md         # generated FHIR R4 cardinality matrix
├── resource-search-parameters.md   # generated FHIR R4 search parameter matrix
├── resource-method-matrix.md       # current SDK method coverage
└── resource-operation-gap.md       # get/search/query gap analysis
```

## Usage

Each YAML file defines the canonical shape of a FHIR type or resource. SDK generators consume these files to produce typed classes/models in each target language.

Current profile YAMLs are **scaffolds**. Cardinality comes from HL7 R4 in `fhir-docs/resource-cardinality.md`; SATUSEHAT-specific required fields, terminology options, and supported search parameters must be mapped from SATUSEHAT docs into each profile YAML.

## Maintenance

When SATUSEHAT API or FHIR R4 spec changes:
1. Update `fhir-docs/sources/` from the official source
2. Regenerate `fhir-docs/resource-cardinality.md` and `resource-search-parameters.md`
3. Update the relevant `fhir-spec/profiles/*.yaml`
4. Then propagate changes across all language SDKs

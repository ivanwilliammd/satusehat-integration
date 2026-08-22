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
└── profiles/    # SATUSEHAT-specific resource profiles
```

## Usage

Each YAML file defines the canonical shape of a FHIR type or resource. SDK generators consume these files to produce typed classes/models in each target language.

## Maintenance

When SATUSEHAT API or FHIR R4 spec changes:
1. Update the relevant `.yaml` file here first
2. Then propagate changes across all language SDKs

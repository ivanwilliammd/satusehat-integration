# SATUSEHAT Resource Profiles

This directory defines SATUSEHAT-specific constraints on top of standard FHIR R4 resources.

Each profile:
- Extends a FHIR base resource type
- Adds SATUSEHAT-specific field requirements (e.g. NIK as required identifier)
- Documents enumerations specific to SATUSEHAT's implementation

## Profiles

| Resource | Description |
|----------|-------------|
| `patient.yaml` | Indonesian national health patient (NIK/KTP) |
| `observation.yaml` | Lab results, vital signs |
| `encounter.yaml` | Patient encounter/visit |
| `specimen.yaml` | Lab specimen |
| `medication.yaml` | Drug/medicine with KFA coding |

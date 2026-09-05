# SATUSEHAT Integration — Migration Roadmap

Tracking migration of FHIR resources + features from `fhirvel-ss` → `satusehat-integration`.

---

## Gap Analysis

**fhirvel-ss**: 38 FHIR classes  
**satusehat-integration**: 49 FHIR classes + 142 PayloadBuilder classes  
**Missing**: 0 core resources (all migrated)

---

## Missing Resources (Core) — ALL DONE

| # | Resource | Priority | Status | Notes |
|---|---------|---------|--------|-------|
| 1 | Medication | P1 | done | Drug/medication reference |
| 2 | MedicationRequest | P1 | done | Prescription order |
| 3 | MedicationAdministration | P1 | done | Drug administration event |
| 4 | MedicationDispense | P1 | done | Dispensing event |
|  5 | MedicationStatement | P1 | done | Medication record |
|  6 | Procedure | P1 | done | Clinical procedure |
|  7 | Immunization | P1 | done | Immunization record |
|  8 | Observation | P1 | done | Lab results, vitals |
|  9 | Specimen | P1 | done | Lab specimen |
| 10 | Task | P1 | done | Workflow task |
| 11 | Group | P2 | done | Patient grouping |
| 12 | RelatedPerson | P1 | done | Personal relationship |
| 13 | ServiceRequest | P2 | done | Request for procedure |
| 14 | QuestionnaireResponse | P2 | done | Form response |
| 15 | Substance | P3 | done | Substance reference (v4.11.x) |
| 16 | MolecularSequence | P3 | done | Genomic sequence (v4.11.x) |
| 17 | GenomicStudy | P3 | done | Genomic study (v4.11.x) |
| 18 | FamilyMemberHistory | P2 | done | Family history (v4.11.x) |
| 19 | RiskAssessment | P2 | done | Risk evaluation (v4.11.x) |
| 20 | DocumentReference | P3 | done | Document reference (v4.11.x) |

---

## Non-Core Resources (Builder-only, no FHIR class)

| # | Resource | Builder | Notes |
|---|---------|---------|-------|
| 21 | Endpoint | ✅ | SATUSEHAT non-FHIR JSON payloads (BillingStatus, PurificationDecision) |
| 22 | Appointment | ✅ | Builder-only |
| 23 | Schedule | ✅ | Builder-only |
| 24 | Slot | ✅ | Builder-only |
| 25 | CareTeam | ✅ | Builder-only |
| 26 | ExplanationOfBenefit | ✅ | Builder-only |
| 27 | VerificationResult | ✅ | Builder-only |

---

## Migration Strategy

Each release: **2 resources per 2 days**

| Release | Resources | Target Date |
|---------|-----------|-------------|
| v4.7.0 | Medication + MedicationRequest | 2026-08-20 |
| v4.8.0 | MedicationAdministration + MedicationDispense | 2026-08-22 |
| v4.9.0 | MedicationStatement + Procedure | 2026-08-24 |
| v5.0.0 | Immunization + Specimen | 2026-08-26 |
| v5.1.0 | Task + RelatedPerson | 2026-08-28 |
| v5.2.0 | Group + ServiceRequest | 2026-08-30 |
| v5.3.0 | QuestionnaireResponse + FamilyMemberHistory | 2026-09-01 |
| v5.4.0 | Substance + RiskAssessment | 2026-09-03 |
| v5.5.0 | MolecularSequence + GenomicStudy | 2026-09-05 |
| v5.6.0 | DocumentReference + remaining | 2026-09-07 |

---

## Release Cadence

- Cron: every 2 days at 09:00 WIB
- Contents per release: 2 FHIR classes + 2 PayloadBuilder classes
- Auto-tag + GitHub Release
- CHANGELOG + README update

---

## Version History

| Version | Date | Resources |
|---------|------|-----------|
| v4.0.0 | 2026-08-15 | Initial Phase 1 refactor |
| v4.3.3 | 2026-08-18 | PHP 7.4 compat + CI fix |
| v4.4.0 | 2026-08-18 | Account, DiagnosticReport, EpisodeOfCare |
| v4.5.0 | 2026-08-18 | Non-core resources (12) + FHIR PATCH + BundleResponse |
| v4.6.0 | 2026-08-18 | Phase 2: Durable queue + transaction logger |
| v4.9.0 | 2026-08-29 | MedicationRequest + Procedure |
| v4.13.0 | 2026-09-03 | ServiceRequest + QuestionnaireResponse |
| v4.14.0 | 2026-09-04 | 6 remaining FHIR classes (Substance, MolecularSequence, GenomicStudy, FamilyMemberHistory, RiskAssessment, DocumentReference) — parity complete |
| v4.14.1 | 2026-09-05 | SDK parity bump (node/python/go) |

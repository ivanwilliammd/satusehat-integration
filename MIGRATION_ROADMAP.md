# SATUSEHAT Integration — Migration Roadmap

Tracking migration of FHIR resources + features from `fhirvel-ss` → `satusehat-integration`.

---

## Gap Analysis

**fhirvel-ss**: 38 FHIR classes  
**satusehat-integration**: 30 FHIR classes + 51 PayloadBuilder classes  
**Missing**: 19 resources

---

## Missing Resources (Core)

| # | Resource | Priority | Status | Notes |
|---|---------|---------|--------|-------|
| 1 | Medication | P1 | done | Drug/medication reference |
| 2 | MedicationRequest | P1 | pending | Prescription order |
| 3 | MedicationAdministration | P1 | pending | Drug administration event |
| 4 | MedicationDispense | P1 | pending | Dispensing event |
| 5 | MedicationStatement | P1 | pending | Medication record |
| 6 | Procedure | P1 | pending | Clinical procedure |
| 7 | Immunization | P1 | pending | Immunization record |
| 8 | Observation | P1 | done | Lab results, vitals |
| 9 | Specimen | P1 | pending | Lab specimen |
| 10 | Task | P1 | pending | Workflow task |
| 11 | Group | P2 | pending | Patient grouping |
| 12 | RelatedPerson | P1 | pending | Personal relationship |
| 13 | ServiceRequest | P2 | pending | Request for procedure |
| 14 | QuestionnaireResponse | P2 | pending | Form response |
| 15 | Substance | P3 | pending | Substance reference |
| 16 | MolecularSequence | P3 | pending | Genomic sequence |
| 17 | GenomicStudy | P3 | pending | Genomic study |
| 18 | FamilyMemberHistory | P2 | pending | Family history |
| 19 | RiskAssessment | P2 | pending | Risk evaluation |

---

## Missing Resources (Non-Core / Future)

| # | Resource | Priority | Status |
|---|---------|---------|--------|
| 20 | DocumentReference | P3 | pending |
| 21 | Endpoint | — | not in fhirvel-ss |
| 22 | Appointment | — | not in fhirvel-ss |
| 23 | Schedule | — | not in fhirvel-ss |
| 24 | Slot | — | not in fhirvel-ss |
| 25 | CareTeam | — | not in fhirvel-ss |
| 26 | ExplanationOfBenefit | — | not in fhirvel-ss |
| 27 | VerificationResult | — | not in fhirvel-ss |

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

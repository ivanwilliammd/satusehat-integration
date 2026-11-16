# Triase konformansi R4 — builder kanon

Dihasilkan `scripts/audit-r4-conformance.py --triage`; jangan disunting tangan.
Elemen pilihan (`start[x]`, `deceased[x]`) sudah diperluas ke nama konkretnya, sehingga
`deceasedBoolean` tidak lagi dilaporkan sebagai pelanggaran.

- builder: **141**
- bersih terhadap R4: **46**
- kategori A (kebocoran setter generik): **68**
- kategori B (elemen khas resource): **20**
- kategori C (resource tidak ada di R4): **7**

## Kategori C — resource di luar R4

| Builder | Tindakan |
| --- | --- |
| `PayloadBuilderBillingStatus` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderChargeItemResponse` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderGenomicStudy` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderImagingManifest` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderImagingSelection` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderPurificationDecision` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |
| `PayloadBuilderResourceGuide` | ditandai `@deprecated`; jangan dipakai untuk SATUSEHAT |

## Kategori A — setter generik pada resource yang tidak punya elemen itu

Pola sama di semua baris: `setStatus`/`setCode`/`setSubject`/`setEncounter`/`addIdentifier`
menulis elemen yang tidak ada di resource tersebut. Setter itu tetap berguna untuk resource
lain, jadi yang salah adalah PEMAKAIANNYA di builder ini, bukan setter-nya.

| Builder | Elemen asing |
| --- | --- |
| `PayloadBuilderAdverseEvent` | `code`, `status` |
| `PayloadBuilderAppointment` | `code`, `encounter`, `subject` |
| `PayloadBuilderAppointmentResponse` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderAuditEvent` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderBasic` | `encounter`, `status` |
| `PayloadBuilderBinary` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderBiologicallyDerivedProduct` | `code`, `encounter`, `subject` |
| `PayloadBuilderBodyStructure` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderCapabilityStatement` | `identifier` |
| `PayloadBuilderCareTeam` | `code` |
| `PayloadBuilderCommunication` | `code` |
| `PayloadBuilderCommunicationRequest` | `code` |
| `PayloadBuilderConsent` | `code`, `encounter`, `subject` |
| `PayloadBuilderContract` | `code`, `encounter` |
| `PayloadBuilderDetectedIssue` | `encounter`, `subject` |
| `PayloadBuilderDeviceDefinition` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderDeviceMetric` | `status` |
| `PayloadBuilderDeviceUseStatement` | `code`, `encounter` |
| `PayloadBuilderEffectEvidenceSynthesis` | `code`, `encounter`, `subject` |
| `PayloadBuilderEnrollmentRequest` | `code`, `encounter`, `subject` |
| `PayloadBuilderEventDefinition` | `code`, `encounter` |
| `PayloadBuilderEvidence` | `code`, `encounter`, `subject` |
| `PayloadBuilderEvidenceVariable` | `code`, `encounter`, `subject` |
| `PayloadBuilderExampleScenario` | `code`, `encounter`, `subject` |
| `PayloadBuilderGuidanceResponse` | `code` |
| `PayloadBuilderHealthcareService` | `status` |
| `PayloadBuilderImmunizationEvaluation` | `code`, `encounter`, `subject` |
| `PayloadBuilderImmunizationRecommendation` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderLibrary` | `code`, `encounter` |
| `PayloadBuilderLinkage` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderMeasure` | `code`, `encounter` |
| `PayloadBuilderMeasureReport` | `code`, `encounter` |
| `PayloadBuilderMedia` | `code` |
| `PayloadBuilderMedicationKnowledge` | `identifier` |
| `PayloadBuilderMedicinalProductContraindication` | `identifier`, `status` |
| `PayloadBuilderMedicinalProductInteraction` | `identifier`, `status` |
| `PayloadBuilderMedicinalProductManufactured` | `identifier`, `status` |
| `PayloadBuilderMedicinalProductPackaged` | `status` |
| `PayloadBuilderMedicinalProductUndesirableEffect` | `identifier`, `status` |
| `PayloadBuilderMessageDefinition` | `code`, `encounter`, `subject` |
| `PayloadBuilderMessageHeader` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderObservationDefinition` | `status` |
| `PayloadBuilderOperationOutcome` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderOrganizationAffiliation` | `status` |
| `PayloadBuilderPerson` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderPlanDefinition` | `code`, `encounter` |
| `PayloadBuilderProvenance` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderQuestionnaire` | `encounter`, `subject` |
| `PayloadBuilderResearchDefinition` | `code`, `encounter` |
| `PayloadBuilderResearchElementDefinition` | `code`, `encounter` |
| `PayloadBuilderResearchSubject` | `code`, `encounter`, `subject` |
| `PayloadBuilderRiskEvidenceSynthesis` | `code`, `encounter`, `subject` |
| `PayloadBuilderSchedule` | `code`, `encounter`, `status`, `subject` |
| `PayloadBuilderSearchParameter` | `encounter`, `identifier`, `subject` |
| `PayloadBuilderSlot` | `code`, `encounter`, `subject` |
| `PayloadBuilderSubscription` | `code`, `encounter`, `identifier`, `subject` |
| `PayloadBuilderSubstanceNucleicAcid` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderSubstancePolymer` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderSubstanceProtein` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderSubstanceReferenceInformation` | `identifier`, `status` |
| `PayloadBuilderSubstanceSourceMaterial` | `code`, `encounter`, `identifier`, `status`, `subject` |
| `PayloadBuilderSubstanceSpecification` | `encounter`, `subject` |
| `PayloadBuilderSupplyDelivery` | `code`, `encounter`, `subject` |
| `PayloadBuilderSupplyRequest` | `code`, `encounter`, `subject` |
| `PayloadBuilderTestReport` | `code`, `encounter`, `subject` |
| `PayloadBuilderTestScript` | `code`, `encounter`, `subject` |
| `PayloadBuilderVerificationResult` | `code`, `encounter`, `identifier`, `subject` |
| `PayloadBuilderVisionPrescription` | `code`, `subject` |

## Kategori B — perlu keputusan per elemen

Tidak ada perbaikan borongan di sini: sebagian elemen adalah nama yang salah, sebagian
salinan dari versi FHIR lain (R5), sebagian lagi memang quirk yang dipertahankan demi
kompatibilitas. Setiap baris harus diputuskan sendiri, dan keputusannya dicatat di sini.

| Builder | Elemen asing | Status |
| --- | --- | --- |
| `PayloadBuilderCarePlan` | `addressee` | belum diputuskan |
| `PayloadBuilderCatalogEntry` | `reference` | belum diputuskan |
| `PayloadBuilderChargeItem` | `focus`, `performedDateTime` | belum diputuskan |
| `PayloadBuilderChargeItemDefinition` | `applies`, `name`, `property` | belum diputuskan |
| `PayloadBuilderClaim` | `coverage`, `prescriber`, `priorityPayor` | belum diputuskan |
| `PayloadBuilderClaimResponse` | `coverage` | belum diputuskan |
| `PayloadBuilderClinicalImpression` | `previousOpinion` | belum diputuskan |
| `PayloadBuilderCoverageEligibilityRequest` | `coverage` | belum diputuskan |
| `PayloadBuilderCoverageEligibilityResponse` | `coverage`, `requestProvider` | belum diputuskan |
| `PayloadBuilderFamilyMemberHistory` | `ageUnit`, `deceasedAgeUnit`, `deceasedCodeableConcept` | belum diputuskan |
| `PayloadBuilderGoal` | `startAge`, `startDateTime`, `startPeriod` | belum diputuskan |
| `PayloadBuilderImagingStudy` | `ended`, `partOf` | belum diputuskan |
| `PayloadBuilderMedicinalProduct` | `manufacturer`, `productClass` | belum diputuskan |
| `PayloadBuilderMedicinalProductIndication` | `disease` | belum diputuskan |
| `PayloadBuilderMedicinalProductIngredient` | `quantity` | belum diputuskan |
| `PayloadBuilderMedicinalProductPharmaceutical` | `doseForm`, `route` | belum diputuskan |
| `PayloadBuilderNutritionOrder` | `allergyType` | belum diputuskan |
| `PayloadBuilderPaymentNotice` | `insurer`, `paymentReconciliation`, `statusDate` | belum diputuskan |
| `PayloadBuilderPaymentReconciliation` | `insurer`, `outcomeCode`, `requestMatchDate`, `requestProvider`, `type` | belum diputuskan |
| `PayloadBuilderSpecimenDefinition` | `preparation`, `type` | belum diputuskan |

## Catatan

Bersih terhadap R4 berarti elemen tingkat atasnya ada di definisi resource — bukan berarti
payload lolos validasi penuh (kardinalitas, tipe, binding). Untuk itu ada korpus contoh di
`satusehat-conformance`.

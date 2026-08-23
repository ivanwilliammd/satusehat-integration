# FHIR R4 Search Parameter Matrix

Source: `fhir-docs/sources/hl7-r4/search-parameters.json`. SATUSEHAT supported subset still needs mapping from SATUSEHAT docs.

## Account

| Code | Type | Expression | Description |
|---|---|---|---|
| `identifier` | token | `Account.identifier` | Account number |
| `name` | string | `Account.name` | Human-readable label |
| `owner` | reference | `Account.owner` | Entity managing the Account |
| `patient` | reference | `Account.subject.where(resolve() is Patient)` | The entity that caused the expenses |
| `period` | date | `Account.servicePeriod` | Transaction window |
| `status` | token | `Account.status` | active / inactive / entered-in-error / on-hold / unknown |
| `subject` | reference | `Account.subject` | The entity that caused the expenses |
| `type` | token | `Account.type` | E.g. patient, expense, depreciation |

## AllergyIntolerance

| Code | Type | Expression | Description |
|---|---|---|---|
| `asserter` | reference | `AllergyIntolerance.asserter` | Source of the information about the allergy |
| `category` | token | `AllergyIntolerance.category` | food / medication / environment / biologic |
| `clinical-status` | token | `AllergyIntolerance.clinicalStatus` | active / inactive / resolved |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `criticality` | token | `AllergyIntolerance.criticality` | low / high / unable-to-assess |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `last-date` | date | `AllergyIntolerance.lastOccurrence` | Date(/time) of last known occurrence of a reaction |
| `manifestation` | token | `AllergyIntolerance.reaction.manifestation` | Clinical symptoms/signs associated with the Event |
| `onset` | date | `AllergyIntolerance.reaction.onset` | Date(/time) when manifestations showed |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `recorder` | reference | `AllergyIntolerance.recorder` | Who recorded the sensitivity |
| `route` | token | `AllergyIntolerance.reaction.exposureRoute` | How the subject was exposed to the substance |
| `severity` | token | `AllergyIntolerance.reaction.severity` | mild / moderate / severe (of event as a whole) |
| `type` | token | `AllergyIntolerance.type | Composition.type | DocumentManifest.type | DocumentReference.type | Encounter.type | EpisodeOfCare.type` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): allergy / intolerance - Underlying mechanism (if known)
* [Composition](composition.ht |
| `verification-status` | token | `AllergyIntolerance.verificationStatus` | unconfirmed / confirmed / refuted / entered-in-error |

## Bundle

| Code | Type | Expression | Description |
|---|---|---|---|
| `composition` | reference | `Bundle.entry[0].resource` | The first resource in the bundle, if the bundle type is "document" - this is a composition, and this parameter provides access to search its contents |
| `identifier` | token | `Bundle.identifier` | Persistent identifier for the bundle |
| `message` | reference | `Bundle.entry[0].resource` | The first resource in the bundle, if the bundle type is "message" - this is a message header, and this parameter provides access to search its contents |
| `timestamp` | date | `Bundle.timestamp` | When the bundle was assembled |
| `type` | token | `Bundle.type` | document / message / transaction / transaction-response / batch / batch-response / history / searchset / collection |

## CarePlan

| Code | Type | Expression | Description |
|---|---|---|---|
| `activity-code` | token | `CarePlan.activity.detail.code` | Detail type of activity |
| `activity-date` | date | `CarePlan.activity.detail.scheduled` | Specified date occurs within period specified by CarePlan.activity.detail.scheduled[x] |
| `activity-reference` | reference | `CarePlan.activity.reference` | Activity details defined in specific resource |
| `based-on` | reference | `CarePlan.basedOn` | Fulfills CarePlan |
| `care-team` | reference | `CarePlan.careTeam` | Who's involved in plan? |
| `category` | token | `CarePlan.category` | Type of plan |
| `condition` | reference | `CarePlan.addresses` | Health issues this plan addresses |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `CarePlan.encounter` | Encounter created as part of |
| `goal` | reference | `CarePlan.goal` | Desired outcome of plan |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instantiates-canonical` | reference | `CarePlan.instantiatesCanonical` | Instantiates FHIR protocol or definition |
| `instantiates-uri` | uri | `CarePlan.instantiatesUri` | Instantiates external protocol or definition |
| `intent` | token | `CarePlan.intent` | proposal / plan / order / option |
| `part-of` | reference | `CarePlan.partOf` | Part of referenced CarePlan |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `CarePlan.activity.detail.performer` | Matches if the practitioner is listed as a performer in any of the "simple" activities.  (For performers of the detailed activities, chain through the activityd |
| `replaces` | reference | `CarePlan.replaces` | CarePlan replaced by this CarePlan |
| `status` | token | `CarePlan.status` | draft / active / on-hold / revoked / completed / entered-in-error / unknown |
| `subject` | reference | `CarePlan.subject` | Who the care plan is for |

## ChargeItem

| Code | Type | Expression | Description |
|---|---|---|---|
| `account` | reference | `ChargeItem.account` | Account to place this charge |
| `code` | token | `ChargeItem.code` | A code that identifies the charge, like a billing code |
| `context` | reference | `ChargeItem.context` | Encounter / Episode associated with event |
| `entered-date` | date | `ChargeItem.enteredDate` | Date the charge item was entered |
| `enterer` | reference | `ChargeItem.enterer` | Individual who was entering |
| `factor-override` | number | `ChargeItem.factorOverride` | Factor overriding the associated rules |
| `identifier` | token | `ChargeItem.identifier` | Business Identifier for item |
| `occurrence` | date | `ChargeItem.occurrence` | When the charged service was applied |
| `patient` | reference | `ChargeItem.subject.where(resolve() is Patient)` | Individual service was done for/to |
| `performer-actor` | reference | `ChargeItem.performer.actor` | Individual who was performing |
| `performer-function` | token | `ChargeItem.performer.function` | What type of performance was done |
| `performing-organization` | reference | `ChargeItem.performingOrganization` | Organization providing the charged service |
| `price-override` | quantity | `ChargeItem.priceOverride` | Price overriding the associated rules |
| `quantity` | quantity | `ChargeItem.quantity` | Quantity of which the charge item has been serviced |
| `requesting-organization` | reference | `ChargeItem.requestingOrganization` | Organization requesting the charged service |
| `service` | reference | `ChargeItem.service` | Which rendered service is being charged? |
| `subject` | reference | `ChargeItem.subject` | Individual service was done for/to |

## ChargeItemDefinition

| Code | Type | Expression | Description |
|---|---|---|---|
| `context` | token | `(ChargeItemDefinition.useContext.value as CodeableConcept)` | A use context assigned to the charge item definition |
| `context-quantity` | quantity | `(ChargeItemDefinition.useContext.value as Quantity) | (ChargeItemDefinition.useContext.value as Range)` | A quantity- or range-valued use context assigned to the charge item definition |
| `context-type` | token | `ChargeItemDefinition.useContext.code` | A type of use context assigned to the charge item definition |
| `context-type-quantity` | composite | `ChargeItemDefinition.useContext` | A use context type and quantity- or range-based value assigned to the charge item definition |
| `context-type-value` | composite | `ChargeItemDefinition.useContext` | A use context type and value assigned to the charge item definition |
| `date` | date | `ChargeItemDefinition.date` | The charge item definition publication date |
| `description` | string | `ChargeItemDefinition.description` | The description of the charge item definition |
| `effective` | date | `ChargeItemDefinition.effectivePeriod` | The time during which the charge item definition is intended to be in use |
| `identifier` | token | `ChargeItemDefinition.identifier` | External identifier for the charge item definition |
| `jurisdiction` | token | `ChargeItemDefinition.jurisdiction` | Intended jurisdiction for the charge item definition |
| `publisher` | string | `ChargeItemDefinition.publisher` | Name of the publisher of the charge item definition |
| `status` | token | `ChargeItemDefinition.status` | The current status of the charge item definition |
| `title` | string | `ChargeItemDefinition.title` | The human-friendly name of the charge item definition |
| `url` | uri | `ChargeItemDefinition.url` | The uri that identifies the charge item definition |
| `version` | token | `ChargeItemDefinition.version` | The business version of the charge item definition |

## ChargeItemResponse

No standard search parameters found.

## Claim

| Code | Type | Expression | Description |
|---|---|---|---|
| `care-team` | reference | `Claim.careTeam.provider` | Member of the CareTeam |
| `created` | date | `Claim.created` | The creation date for the Claim |
| `detail-udi` | reference | `Claim.item.detail.udi` | UDI associated with a line item, detail product or service |
| `encounter` | reference | `Claim.item.encounter` | Encounters associated with a billed line item |
| `enterer` | reference | `Claim.enterer` | The party responsible for the entry of the Claim |
| `facility` | reference | `Claim.facility` | Facility where the products or services have been or will be provided |
| `identifier` | token | `Claim.identifier` | The primary identifier of the financial resource |
| `insurer` | reference | `Claim.insurer` | The target payor/insurer for the Claim |
| `item-udi` | reference | `Claim.item.udi` | UDI associated with a line item product or service |
| `patient` | reference | `Claim.patient` | Patient receiving the products or services |
| `payee` | reference | `Claim.payee.party` | The party receiving any payment for the Claim |
| `priority` | token | `Claim.priority` | Processing priority requested |
| `procedure-udi` | reference | `Claim.procedure.udi` | UDI associated with a procedure |
| `provider` | reference | `Claim.provider` | Provider responsible for the Claim |
| `status` | token | `Claim.status` | The status of the Claim instance. |
| `subdetail-udi` | reference | `Claim.item.detail.subDetail.udi` | UDI associated with a line item, detail, subdetail product or service |
| `use` | token | `Claim.use` | The kind of financial resource |

## ClaimResponse

| Code | Type | Expression | Description |
|---|---|---|---|
| `created` | date | `ClaimResponse.created` | The creation date |
| `disposition` | string | `ClaimResponse.disposition` | The contents of the disposition message |
| `identifier` | token | `ClaimResponse.identifier` | The identity of the ClaimResponse |
| `insurer` | reference | `ClaimResponse.insurer` | The organization which generated this resource |
| `outcome` | token | `ClaimResponse.outcome` | The processing outcome |
| `patient` | reference | `ClaimResponse.patient` | The subject of care |
| `payment-date` | date | `ClaimResponse.payment.date` | The expected payment date |
| `request` | reference | `ClaimResponse.request` | The claim reference |
| `requestor` | reference | `ClaimResponse.requestor` | The Provider of the claim |
| `status` | token | `ClaimResponse.status` | The status of the ClaimResponse |
| `use` | token | `ClaimResponse.use` | The type of claim |

## ClinicalImpression

| Code | Type | Expression | Description |
|---|---|---|---|
| `assessor` | reference | `ClinicalImpression.assessor` | The clinician performing the assessment |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `ClinicalImpression.encounter` | Encounter created as part of |
| `finding-code` | token | `ClinicalImpression.finding.itemCodeableConcept` | What was found |
| `finding-ref` | reference | `ClinicalImpression.finding.itemReference` | What was found |
| `identifier` | token | `ClinicalImpression.identifier` | Business identifier |
| `investigation` | reference | `ClinicalImpression.investigation.item` | Record of a specific investigation |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `previous` | reference | `ClinicalImpression.previous` | Reference to last assessment |
| `problem` | reference | `ClinicalImpression.problem` | Relevant impressions of patient state |
| `status` | token | `ClinicalImpression.status` | in-progress / completed / entered-in-error |
| `subject` | reference | `ClinicalImpression.subject` | Patient or group assessed |
| `supporting-info` | reference | `ClinicalImpression.supportingInfo` | Information supporting the clinical impression |

## Composition

| Code | Type | Expression | Description |
|---|---|---|---|
| `attester` | reference | `Composition.attester.party` | Who attested the composition |
| `author` | reference | `Composition.author` | Who and/or what authored the composition |
| `category` | token | `Composition.category` | Categorization of Composition |
| `confidentiality` | token | `Composition.confidentiality` | As defined by affinity domain |
| `context` | token | `Composition.event.code` | Code(s) that apply to the event being documented |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `entry` | reference | `Composition.section.entry` | A reference to data that supports this section |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `period` | date | `Composition.event.period` | The period covered by the documentation |
| `related-id` | token | `(Composition.relatesTo.target as Identifier)` | Target of the relationship |
| `related-ref` | reference | `(Composition.relatesTo.target as Reference)` | Target of the relationship |
| `section` | token | `Composition.section.code` | Classification of section (recommended) |
| `status` | token | `Composition.status` | preliminary / final / amended / entered-in-error |
| `subject` | reference | `Composition.subject` | Who and/or what the composition is about |
| `title` | string | `Composition.title` | Human Readable name/title |
| `type` | token | `AllergyIntolerance.type | Composition.type | DocumentManifest.type | DocumentReference.type | Encounter.type | EpisodeOfCare.type` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): allergy / intolerance - Underlying mechanism (if known)
* [Composition](composition.ht |

## Condition

| Code | Type | Expression | Description |
|---|---|---|---|
| `abatement-age` | quantity | `Condition.abatement.as(Age) | Condition.abatement.as(Range)` | Abatement as age or age range |
| `abatement-date` | date | `Condition.abatement.as(dateTime) | Condition.abatement.as(Period)` | Date-related abatements (dateTime and period) |
| `abatement-string` | string | `Condition.abatement.as(string)` | Abatement as a string |
| `asserter` | reference | `Condition.asserter` | Person who asserts this condition |
| `body-site` | token | `Condition.bodySite` | Anatomical location, if relevant |
| `category` | token | `Condition.category` | The category of the condition |
| `clinical-status` | token | `Condition.clinicalStatus` | The clinical status of the condition |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `encounter` | reference | `Condition.encounter` | Encounter created as part of |
| `evidence` | token | `Condition.evidence.code` | Manifestation/symptom |
| `evidence-detail` | reference | `Condition.evidence.detail` | Supporting information found elsewhere |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `onset-age` | quantity | `Condition.onset.as(Age) | Condition.onset.as(Range)` | Onsets as age or age range |
| `onset-date` | date | `Condition.onset.as(dateTime) | Condition.onset.as(Period)` | Date related onsets (dateTime and Period) |
| `onset-info` | string | `Condition.onset.as(string)` | Onsets as a string |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `recorded-date` | date | `Condition.recordedDate` | Date record was first recorded |
| `severity` | token | `Condition.severity` | The severity of the condition |
| `stage` | token | `Condition.stage.summary` | Simple summary (disease specific) |
| `subject` | reference | `Condition.subject` | Who has the condition? |
| `verification-status` | token | `Condition.verificationStatus` | unconfirmed / provisional / differential / confirmed / refuted / entered-in-error |

## Coverage

| Code | Type | Expression | Description |
|---|---|---|---|
| `beneficiary` | reference | `Coverage.beneficiary` | Covered party |
| `class-type` | token | `Coverage.class.type` | Coverage class (eg. plan, group) |
| `class-value` | string | `Coverage.class.value` | Value of the class (eg. Plan number, group number) |
| `dependent` | string | `Coverage.dependent` | Dependent number |
| `identifier` | token | `Coverage.identifier` | The primary identifier of the insured and the coverage |
| `patient` | reference | `Coverage.beneficiary` | Retrieve coverages for a patient |
| `payor` | reference | `Coverage.payor` | The identity of the insurer or party paying for services |
| `policy-holder` | reference | `Coverage.policyHolder` | Reference to the policyholder |
| `status` | token | `Coverage.status` | The status of the Coverage |
| `subscriber` | reference | `Coverage.subscriber` | Reference to the subscriber |
| `type` | token | `Coverage.type` | The kind of coverage (health plan, auto, Workers Compensation) |

## CoverageEligibilityRequest

| Code | Type | Expression | Description |
|---|---|---|---|
| `created` | date | `CoverageEligibilityRequest.created` | The creation date for the EOB |
| `enterer` | reference | `CoverageEligibilityRequest.enterer` | The party who is responsible for the request |
| `facility` | reference | `CoverageEligibilityRequest.facility` | Facility responsible for the goods and services |
| `identifier` | token | `CoverageEligibilityRequest.identifier` | The business identifier of the Eligibility |
| `patient` | reference | `CoverageEligibilityRequest.patient` | The reference to the patient |
| `provider` | reference | `CoverageEligibilityRequest.provider` | The reference to the provider |
| `status` | token | `CoverageEligibilityRequest.status` | The status of the EligibilityRequest |

## CoverageEligibilityResponse

| Code | Type | Expression | Description |
|---|---|---|---|
| `created` | date | `CoverageEligibilityResponse.created` | The creation date |
| `disposition` | string | `CoverageEligibilityResponse.disposition` | The contents of the disposition message |
| `identifier` | token | `CoverageEligibilityResponse.identifier` | The business identifier |
| `insurer` | reference | `CoverageEligibilityResponse.insurer` | The organization which generated this resource |
| `outcome` | token | `CoverageEligibilityResponse.outcome` | The processing outcome |
| `patient` | reference | `CoverageEligibilityResponse.patient` | The reference to the patient |
| `request` | reference | `CoverageEligibilityResponse.request` | The EligibilityRequest reference |
| `requestor` | reference | `CoverageEligibilityResponse.requestor` | The EligibilityRequest provider |
| `status` | token | `CoverageEligibilityResponse.status` | The EligibilityRequest status |

## Device

| Code | Type | Expression | Description |
|---|---|---|---|
| `device-name` | string | `Device.deviceName.name | Device.type.coding.display | Device.type.text` | A server defined search that may match any of the string fields in Device.deviceName or Device.type. |
| `identifier` | token | `Device.identifier` | Instance id from manufacturer, owner, and others |
| `location` | reference | `Device.location` | A location, where the resource is found |
| `manufacturer` | string | `Device.manufacturer` | The manufacturer of the device |
| `model` | string | `Device.modelNumber` | The model of the device |
| `organization` | reference | `Device.owner` | The organization responsible for the device |
| `patient` | reference | `Device.patient` | Patient information, if the resource is affixed to a person |
| `status` | token | `Device.status` | active / inactive / entered-in-error / unknown |
| `type` | token | `Device.type` | The type of the device |
| `udi-carrier` | string | `Device.udiCarrier.carrierHRF` | UDI Barcode (RFID or other technology) string in *HRF* format. |
| `udi-di` | string | `Device.udiCarrier.deviceIdentifier` | The udi Device Identifier (DI) |
| `url` | uri | `Device.url` | Network address to contact device |

## DiagnosticReport

| Code | Type | Expression | Description |
|---|---|---|---|
| `based-on` | reference | `DiagnosticReport.basedOn` | Reference to the service request. |
| `category` | token | `DiagnosticReport.category` | Which diagnostic discipline/department created the report |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `conclusion` | token | `DiagnosticReport.conclusionCode` | A coded conclusion (interpretation/impression) on the report |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `issued` | date | `DiagnosticReport.issued` | When the report was issued |
| `media` | reference | `DiagnosticReport.media.link` | A reference to the image source. |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `DiagnosticReport.performer` | Who is responsible for the report |
| `result` | reference | `DiagnosticReport.result` | Link to an atomic result (observation resource) |
| `results-interpreter` | reference | `DiagnosticReport.resultsInterpreter` | Who was the source of the report |
| `specimen` | reference | `DiagnosticReport.specimen` | The specimen details |
| `status` | token | `DiagnosticReport.status` | The status of the report |
| `subject` | reference | `DiagnosticReport.subject` | The subject of the report |

## DocumentReference

| Code | Type | Expression | Description |
|---|---|---|---|
| `authenticator` | reference | `DocumentReference.authenticator` | Who/what authenticated the document |
| `author` | reference | `DocumentReference.author` | Who and/or what authored the document |
| `category` | token | `DocumentReference.category` | Categorization of document |
| `contenttype` | token | `DocumentReference.content.attachment.contentType` | Mime type of the content, with charset etc. |
| `custodian` | reference | `DocumentReference.custodian` | Organization which maintains the document |
| `date` | date | `DocumentReference.date` | When this document reference was created |
| `description` | string | `DocumentReference.description` | Human-readable description |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `event` | token | `DocumentReference.context.event` | Main clinical acts documented |
| `facility` | token | `DocumentReference.context.facilityType` | Kind of facility where patient was seen |
| `format` | token | `DocumentReference.content.format` | Format/content rules for the document |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `language` | token | `DocumentReference.content.attachment.language` | Human language of the content (BCP-47) |
| `location` | uri | `DocumentReference.content.attachment.url` | Uri where the data can be found |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `period` | date | `DocumentReference.context.period` | Time of service that is being documented |
| `related` | reference | `DocumentReference.context.related` | Related identifiers or resources |
| `relatesto` | reference | `DocumentReference.relatesTo.target` | Target of the relationship |
| `relation` | token | `DocumentReference.relatesTo.code` | replaces / transforms / signs / appends |
| `relationship` | composite | `DocumentReference.relatesTo` | Combination of relation and relatesTo |
| `security-label` | token | `DocumentReference.securityLabel` | Document security-tags |
| `setting` | token | `DocumentReference.context.practiceSetting` | Additional details about where the content was created (e.g. clinical specialty) |
| `status` | token | `DocumentReference.status` | current / superseded / entered-in-error |
| `subject` | reference | `DocumentReference.subject` | Who/what is the subject of the document |
| `type` | token | `AllergyIntolerance.type | Composition.type | DocumentManifest.type | DocumentReference.type | Encounter.type | EpisodeOfCare.type` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): allergy / intolerance - Underlying mechanism (if known)
* [Composition](composition.ht |

## Encounter

| Code | Type | Expression | Description |
|---|---|---|---|
| `account` | reference | `Encounter.account` | The set of accounts that may be used for billing for this Encounter |
| `appointment` | reference | `Encounter.appointment` | The appointment that scheduled this encounter |
| `based-on` | reference | `Encounter.basedOn` | The ServiceRequest that initiated this encounter |
| `class` | token | `Encounter.class` | Classification of patient encounter |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `diagnosis` | reference | `Encounter.diagnosis.condition` | The diagnosis or procedure relevant to the encounter |
| `episode-of-care` | reference | `Encounter.episodeOfCare` | Episode(s) of care that this encounter should be recorded against |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `length` | quantity | `Encounter.length` | Length of encounter in days |
| `location` | reference | `Encounter.location.location` | Location the encounter takes place |
| `location-period` | date | `Encounter.location.period` | Time period during which the patient was present at the location |
| `part-of` | reference | `Encounter.partOf` | Another Encounter this encounter is part of |
| `participant` | reference | `Encounter.participant.individual` | Persons involved in the encounter other than the patient |
| `participant-type` | token | `Encounter.participant.type` | Role of participant in encounter |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `practitioner` | reference | `Encounter.participant.individual.where(resolve() is Practitioner)` | Persons involved in the encounter other than the patient |
| `reason-code` | token | `Encounter.reasonCode` | Coded reason the encounter takes place |
| `reason-reference` | reference | `Encounter.reasonReference` | Reason the encounter takes place (reference) |
| `service-provider` | reference | `Encounter.serviceProvider` | The organization (facility) responsible for this encounter |
| `special-arrangement` | token | `Encounter.hospitalization.specialArrangement` | Wheelchair, translator, stretcher, etc. |
| `status` | token | `Encounter.status` | planned / arrived / triaged / in-progress / onleave / finished / cancelled + |
| `subject` | reference | `Encounter.subject` | The patient or group present at the encounter |
| `type` | token | `AllergyIntolerance.type | Composition.type | DocumentManifest.type | DocumentReference.type | Encounter.type | EpisodeOfCare.type` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): allergy / intolerance - Underlying mechanism (if known)
* [Composition](composition.ht |

## EpisodeOfCare

| Code | Type | Expression | Description |
|---|---|---|---|
| `care-manager` | reference | `EpisodeOfCare.careManager.where(resolve() is Practitioner)` | Care manager/care coordinator for the patient |
| `condition` | reference | `EpisodeOfCare.diagnosis.condition` | Conditions/problems/diagnoses this episode of care is for |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `incoming-referral` | reference | `EpisodeOfCare.referralRequest` | Incoming Referral Request |
| `organization` | reference | `EpisodeOfCare.managingOrganization` | The organization that has assumed the specific responsibilities of this EpisodeOfCare |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `status` | token | `EpisodeOfCare.status` | The current status of the Episode of Care as provided (does not check the status history collection) |
| `type` | token | `AllergyIntolerance.type | Composition.type | DocumentManifest.type | DocumentReference.type | Encounter.type | EpisodeOfCare.type` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): allergy / intolerance - Underlying mechanism (if known)
* [Composition](composition.ht |

## FamilyMemberHistory

| Code | Type | Expression | Description |
|---|---|---|---|
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instantiates-canonical` | reference | `FamilyMemberHistory.instantiatesCanonical` | Instantiates FHIR protocol or definition |
| `instantiates-uri` | uri | `FamilyMemberHistory.instantiatesUri` | Instantiates external protocol or definition |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `relationship` | token | `FamilyMemberHistory.relationship` | A search by a relationship type |
| `sex` | token | `FamilyMemberHistory.sex` | A search by a sex code of a family member |
| `status` | token | `FamilyMemberHistory.status` | partial / completed / entered-in-error / health-unknown |

## GenomicStudy

No standard search parameters found.

## Goal

| Code | Type | Expression | Description |
|---|---|---|---|
| `achievement-status` | token | `Goal.achievementStatus` | in-progress / improving / worsening / no-change / achieved / sustaining / not-achieved / no-progress / not-attainable |
| `category` | token | `Goal.category` | E.g. Treatment, dietary, behavioral, etc. |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `lifecycle-status` | token | `Goal.lifecycleStatus` | proposed / planned / accepted / active / on-hold / completed / cancelled / entered-in-error / rejected |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `start-date` | date | `(Goal.start as date)` | When goal pursuit begins |
| `subject` | reference | `Goal.subject` | Who this goal is intended for |
| `target-date` | date | `(Goal.target.due as date)` | Reach goal on or before |

## Group

| Code | Type | Expression | Description |
|---|---|---|---|
| `actual` | token | `Group.actual` | Descriptive or actual |
| `characteristic` | token | `Group.characteristic.code` | Kind of characteristic |
| `characteristic-value` | composite | `Group.characteristic` | A composite of both characteristic and value |
| `code` | token | `Group.code` | The kind of resources contained |
| `exclude` | token | `Group.characteristic.exclude` | Group includes or excludes |
| `identifier` | token | `Group.identifier` | Unique id |
| `managing-entity` | reference | `Group.managingEntity` | Entity that is the custodian of the Group's definition |
| `member` | reference | `Group.member.entity` | Reference to the group member |
| `type` | token | `Group.type` | The type of resources the group contains |
| `value` | token | `(Group.characteristic.value as CodeableConcept) | (Group.characteristic.value as boolean)` | Value held by characteristic |

## ImagingStudy

| Code | Type | Expression | Description |
|---|---|---|---|
| `basedon` | reference | `ImagingStudy.basedOn` | The order for the image |
| `bodysite` | token | `ImagingStudy.series.bodySite` | The body site studied |
| `dicom-class` | token | `ImagingStudy.series.instance.sopClass` | The type of the instance |
| `encounter` | reference | `ImagingStudy.encounter` | The context of the study |
| `endpoint` | reference | `ImagingStudy.endpoint | ImagingStudy.series.endpoint` | The endpoint for the study or series |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instance` | token | `ImagingStudy.series.instance.uid` | SOP Instance UID for an instance |
| `interpreter` | reference | `ImagingStudy.interpreter` | Who interpreted the images |
| `modality` | token | `ImagingStudy.series.modality` | The modality of the series |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `ImagingStudy.series.performer.actor` | The person who performed the study |
| `reason` | token | `ImagingStudy.reasonCode` | The reason for the study |
| `referrer` | reference | `ImagingStudy.referrer` | The referring physician |
| `series` | token | `ImagingStudy.series.uid` | DICOM Series Instance UID for a series |
| `started` | date | `ImagingStudy.started` | When the study was started |
| `status` | token | `ImagingStudy.status` | The status of the study |
| `subject` | reference | `ImagingStudy.subject` | Who the study is about |

## Immunization

| Code | Type | Expression | Description |
|---|---|---|---|
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `location` | reference | `Immunization.location` | The service delivery location or facility in which the vaccine was / was to be administered |
| `lot-number` | string | `Immunization.lotNumber` | Vaccine Lot Number |
| `manufacturer` | reference | `Immunization.manufacturer` | Vaccine Manufacturer |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `Immunization.performer.actor` | The practitioner or organization who played a role in the vaccination |
| `reaction` | reference | `Immunization.reaction.detail` | Additional information on reaction |
| `reaction-date` | date | `Immunization.reaction.date` | When reaction started |
| `reason-code` | token | `Immunization.reasonCode` | Reason why the vaccine was administered |
| `reason-reference` | reference | `Immunization.reasonReference` | Why immunization occurred |
| `series` | string | `Immunization.protocolApplied.series` | The series being followed by the provider |
| `status` | token | `Immunization.status` | Immunization event status |
| `status-reason` | token | `Immunization.statusReason` | Reason why the vaccine was not administered |
| `target-disease` | token | `Immunization.protocolApplied.targetDisease` | The target disease the dose is being administered against |
| `vaccine-code` | token | `Immunization.vaccineCode` | Vaccine Product Administered |

## Invoice

| Code | Type | Expression | Description |
|---|---|---|---|
| `account` | reference | `Invoice.account` | Account that is being balanced |
| `date` | date | `Invoice.date` | Invoice date / posting date |
| `identifier` | token | `Invoice.identifier` | Business Identifier for item |
| `issuer` | reference | `Invoice.issuer` | Issuing Organization of Invoice |
| `participant` | reference | `Invoice.participant.actor` | Individual who was involved |
| `participant-role` | token | `Invoice.participant.role` | Type of involvement in creation of this Invoice |
| `patient` | reference | `Invoice.subject.where(resolve() is Patient)` | Recipient(s) of goods and services |
| `recipient` | reference | `Invoice.recipient` | Recipient of this invoice |
| `status` | token | `Invoice.status` | draft / issued / balanced / cancelled / entered-in-error |
| `subject` | reference | `Invoice.subject` | Recipient(s) of goods and services |
| `totalgross` | quantity | `Invoice.totalGross` | Gross total of this Invoice |
| `totalnet` | quantity | `Invoice.totalNet` | Net total of this Invoice |
| `type` | token | `Invoice.type` | Type of Invoice |

## Location

| Code | Type | Expression | Description |
|---|---|---|---|
| `address` | string | `Location.address` | A (part of the) address of the location |
| `address-city` | string | `Location.address.city` | A city specified in an address |
| `address-country` | string | `Location.address.country` | A country specified in an address |
| `address-postalcode` | string | `Location.address.postalCode` | A postal code specified in an address |
| `address-state` | string | `Location.address.state` | A state specified in an address |
| `address-use` | token | `Location.address.use` | A use code specified in an address |
| `endpoint` | reference | `Location.endpoint` | Technical endpoints providing access to services operated for the location |
| `identifier` | token | `Location.identifier` | An identifier for the location |
| `name` | string | `Location.name | Location.alias` | A portion of the location's name or alias |
| `near` | special | `Location.position` | Search for locations where the location.position is near to, or within a specified distance of, the provided coordinates expressed as [latitude]/[longitude]/[di |
| `operational-status` | token | `Location.operationalStatus` | Searches for locations (typically bed/room) that have an operational status (e.g. contaminated, housekeeping) |
| `organization` | reference | `Location.managingOrganization` | Searches for locations that are managed by the provided organization |
| `partof` | reference | `Location.partOf` | A location of which this location is a part |
| `status` | token | `Location.status` | Searches for locations with a specific kind of status |
| `type` | token | `Location.type` | A code for the type of location |

## Medication

| Code | Type | Expression | Description |
|---|---|---|---|
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `expiration-date` | date | `Medication.batch.expirationDate` | Returns medications in a batch with this expiration date |
| `form` | token | `Medication.form` | Returns medications for a specific dose form |
| `identifier` | token | `Medication.identifier` | Returns medications with this external identifier |
| `ingredient` | reference | `(Medication.ingredient.item as Reference)` | Returns medications for this ingredient reference |
| `ingredient-code` | token | `(Medication.ingredient.item as CodeableConcept)` | Returns medications for this ingredient code |
| `lot-number` | token | `Medication.batch.lotNumber` | Returns medications in a batch with this lot number |
| `manufacturer` | reference | `Medication.manufacturer` | Returns medications made or sold for this manufacturer |
| `status` | token | `Medication.status` | Returns medications for this status |

## MedicationAdministration

| Code | Type | Expression | Description |
|---|---|---|---|
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `context` | reference | `MedicationAdministration.context` | Return administrations that share this encounter or episode of care |
| `device` | reference | `MedicationAdministration.device` | Return administrations with this administration device identity |
| `effective-time` | date | `MedicationAdministration.effective` | Date administration happened (or did not happen) |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `medication` | reference | `(MedicationAdministration.medication as Reference) | (MedicationDispense.medication as Reference) | (MedicationRequest.medication as Reference) | (MedicationStatement.medication as Reference)` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): Return administrations of this medication resource
* [MedicationDispense]( |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `MedicationAdministration.performer.actor` | The identity of the individual who administered the medication |
| `reason-given` | token | `MedicationAdministration.reasonCode` | Reasons for administering the medication |
| `reason-not-given` | token | `MedicationAdministration.statusReason` | Reasons for not administering the medication |
| `request` | reference | `MedicationAdministration.request` | The identity of a request to list administrations from |
| `status` | token | `MedicationAdministration.status | MedicationDispense.status | MedicationRequest.status | MedicationStatement.status` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): MedicationAdministration event status (for example one of active/paused/com |
| `subject` | reference | `MedicationAdministration.subject` | The identity of the individual or group to list administrations for |

## MedicationDispense

| Code | Type | Expression | Description |
|---|---|---|---|
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `context` | reference | `MedicationDispense.context` | Returns dispenses with a specific context (episode or episode of care) |
| `destination` | reference | `MedicationDispense.destination` | Returns dispenses that should be sent to a specific destination |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `medication` | reference | `(MedicationAdministration.medication as Reference) | (MedicationDispense.medication as Reference) | (MedicationRequest.medication as Reference) | (MedicationStatement.medication as Reference)` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): Return administrations of this medication resource
* [MedicationDispense]( |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `MedicationDispense.performer.actor` | Returns dispenses performed by a specific individual |
| `prescription` | reference | `MedicationDispense.authorizingPrescription` | Multiple Resources: 

* [MedicationDispense](medicationdispense.html): The identity of a prescription to list dispenses from
 |
| `receiver` | reference | `MedicationDispense.receiver` | The identity of a receiver to list dispenses for |
| `responsibleparty` | reference | `MedicationDispense.substitution.responsibleParty` | Returns dispenses with the specified responsible party |
| `status` | token | `MedicationAdministration.status | MedicationDispense.status | MedicationRequest.status | MedicationStatement.status` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): MedicationAdministration event status (for example one of active/paused/com |
| `subject` | reference | `MedicationDispense.subject` | The identity of a patient for whom to list dispenses |
| `type` | token | `MedicationDispense.type` | Returns dispenses of a specific type |
| `whenhandedover` | date | `MedicationDispense.whenHandedOver` | Returns dispenses handed over on this date |
| `whenprepared` | date | `MedicationDispense.whenPrepared` | Returns dispenses prepared on this date |

## MedicationRequest

| Code | Type | Expression | Description |
|---|---|---|---|
| `authoredon` | date | `MedicationRequest.authoredOn` | Return prescriptions written on this date |
| `category` | token | `MedicationRequest.category` | Returns prescriptions with different categories |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `date` | date | `MedicationRequest.dosageInstruction.timing.event` | Multiple Resources: 

* [MedicationRequest](medicationrequest.html): Returns medication request to be administered on a specific date
 |
| `encounter` | reference | `MedicationRequest.encounter` | Multiple Resources: 

* [MedicationRequest](medicationrequest.html): Return prescriptions with this encounter identifier
 |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `intended-dispenser` | reference | `MedicationRequest.dispenseRequest.performer` | Returns prescriptions intended to be dispensed by this Organization |
| `intended-performer` | reference | `MedicationRequest.performer` | Returns the intended performer of the administration of the medication request |
| `intended-performertype` | token | `MedicationRequest.performerType` | Returns requests for a specific type of performer |
| `intent` | token | `MedicationRequest.intent` | Returns prescriptions with different intents |
| `medication` | reference | `(MedicationAdministration.medication as Reference) | (MedicationDispense.medication as Reference) | (MedicationRequest.medication as Reference) | (MedicationStatement.medication as Reference)` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): Return administrations of this medication resource
* [MedicationDispense]( |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `priority` | token | `MedicationRequest.priority` | Returns prescriptions with different priorities |
| `requester` | reference | `MedicationRequest.requester` | Returns prescriptions prescribed by this prescriber |
| `status` | token | `MedicationAdministration.status | MedicationDispense.status | MedicationRequest.status | MedicationStatement.status` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): MedicationAdministration event status (for example one of active/paused/com |
| `subject` | reference | `MedicationRequest.subject` | The identity of a patient to list orders  for |

## MedicationStatement

| Code | Type | Expression | Description |
|---|---|---|---|
| `category` | token | `MedicationStatement.category` | Returns statements of this category of medicationstatement |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `context` | reference | `MedicationStatement.context` | Returns statements for a specific context (episode or episode of Care). |
| `effective` | date | `MedicationStatement.effective` | Date when patient was taking (or not taking) the medication |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `medication` | reference | `(MedicationAdministration.medication as Reference) | (MedicationDispense.medication as Reference) | (MedicationRequest.medication as Reference) | (MedicationStatement.medication as Reference)` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): Return administrations of this medication resource
* [MedicationDispense]( |
| `part-of` | reference | `MedicationStatement.partOf` | Returns statements that are part of another event. |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `source` | reference | `MedicationStatement.informationSource` | Who or where the information in the statement came from |
| `status` | token | `MedicationAdministration.status | MedicationDispense.status | MedicationRequest.status | MedicationStatement.status` | Multiple Resources: 

* [MedicationAdministration](medicationadministration.html): MedicationAdministration event status (for example one of active/paused/com |
| `subject` | reference | `MedicationStatement.subject` | The identity of a patient, animal or group to list statements for |

## MolecularSequence

| Code | Type | Expression | Description |
|---|---|---|---|
| `chromosome` | token | `MolecularSequence.referenceSeq.chromosome` | Chromosome number of the reference sequence |
| `chromosome-variant-coordinate` | composite | `MolecularSequence.variant` | Search parameter by chromosome and variant coordinate. This will refer to part of a locus or part of a gene where search region will be represented in 1-based s |
| `chromosome-window-coordinate` | composite | `MolecularSequence.referenceSeq` | Search parameter by chromosome and window. This will refer to part of a locus or part of a gene where search region will be represented in 1-based system. Since |
| `identifier` | token | `MolecularSequence.identifier` | The unique identity for a particular sequence |
| `patient` | reference | `MolecularSequence.patient` | The subject that the observation is about |
| `referenceseqid` | token | `MolecularSequence.referenceSeq.referenceSeqId` | Reference Sequence of the sequence |
| `referenceseqid-variant-coordinate` | composite | `MolecularSequence.variant` | Search parameter by reference sequence and variant coordinate. This will refer to part of a locus or part of a gene where search region will be represented in 1 |
| `referenceseqid-window-coordinate` | composite | `MolecularSequence.referenceSeq` | Search parameter by reference sequence and window. This will refer to part of a locus or part of a gene where search region will be represented in 1-based syste |
| `type` | token | `MolecularSequence.type` | Amino Acid Sequence/ DNA Sequence / RNA Sequence |
| `variant-end` | number | `MolecularSequence.variant.end` | End position (0-based exclusive, which menas the acid at this position will not be included, 1-based inclusive, which means the acid at this position will be in |
| `variant-start` | number | `MolecularSequence.variant.start` | Start position (0-based inclusive, 1-based inclusive, that means the nucleic acid or amino acid at this position will be included) of the variant. |
| `window-end` | number | `MolecularSequence.referenceSeq.windowEnd` | End position (0-based exclusive, which menas the acid at this position will not be included, 1-based inclusive, which means the acid at this position will be in |
| `window-start` | number | `MolecularSequence.referenceSeq.windowStart` | Start position (0-based inclusive, 1-based inclusive, that means the nucleic acid or amino acid at this position will be included) of the reference sequence. |

## NutritionOrder

| Code | Type | Expression | Description |
|---|---|---|---|
| `additive` | token | `NutritionOrder.enteralFormula.additiveType` | Type of module component to add to the feeding |
| `datetime` | date | `NutritionOrder.dateTime` | Return nutrition orders requested on this date |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `formula` | token | `NutritionOrder.enteralFormula.baseFormulaType` | Type of enteral or infant formula |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instantiates-canonical` | reference | `NutritionOrder.instantiatesCanonical` | Instantiates FHIR protocol or definition |
| `instantiates-uri` | uri | `NutritionOrder.instantiatesUri` | Instantiates external protocol or definition |
| `oraldiet` | token | `NutritionOrder.oralDiet.type` | Type of diet that can be consumed orally (i.e., take via the mouth). |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `provider` | reference | `NutritionOrder.orderer` | The identity of the provider who placed the nutrition order |
| `status` | token | `NutritionOrder.status` | Status of the nutrition order. |
| `supplement` | token | `NutritionOrder.supplement.type` | Type of supplement product requested |

## Observation

| Code | Type | Expression | Description |
|---|---|---|---|
| `based-on` | reference | `Observation.basedOn` | Reference to the service request. |
| `category` | token | `Observation.category` | The classification of the type of observation |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `code-value-concept` | composite | `Observation` | Code and coded value parameter pair |
| `code-value-date` | composite | `Observation` | Code and date/time value parameter pair |
| `code-value-quantity` | composite | `Observation` | Code and quantity value parameter pair |
| `code-value-string` | composite | `Observation` | Code and string value parameter pair |
| `combo-code` | token | `Observation.code | Observation.component.code` | The code of the observation type or component type |
| `combo-code-value-concept` | composite | `Observation | Observation.component` | Code and coded value parameter pair, including in components |
| `combo-code-value-quantity` | composite | `Observation | Observation.component` | Code and quantity value parameter pair, including in components |
| `combo-data-absent-reason` | token | `Observation.dataAbsentReason | Observation.component.dataAbsentReason` | The reason why the expected value in the element Observation.value[x] or Observation.component.value[x] is missing. |
| `combo-value-concept` | token | `(Observation.value as CodeableConcept) | (Observation.component.value as CodeableConcept)` | The value or component value of the observation, if the value is a CodeableConcept |
| `combo-value-quantity` | quantity | `(Observation.value as Quantity) | (Observation.value as SampledData) | (Observation.component.value as Quantity) | (Observation.component.value as SampledData)` | The value or component value of the observation, if the value is a Quantity, or a SampledData (just search on the bounds of the values in sampled data) |
| `component-code` | token | `Observation.component.code` | The component code of the observation type |
| `component-code-value-concept` | composite | `Observation.component` | Component code and component coded value parameter pair |
| `component-code-value-quantity` | composite | `Observation.component` | Component code and component quantity value parameter pair |
| `component-data-absent-reason` | token | `Observation.component.dataAbsentReason` | The reason why the expected value in the element Observation.component.value[x] is missing. |
| `component-value-concept` | token | `(Observation.component.value as CodeableConcept)` | The value of the component observation, if the value is a CodeableConcept |
| `component-value-quantity` | quantity | `(Observation.component.value as Quantity) | (Observation.component.value as SampledData)` | The value of the component observation, if the value is a Quantity, or a SampledData (just search on the bounds of the values in sampled data) |
| `data-absent-reason` | token | `Observation.dataAbsentReason` | The reason why the expected value in the element Observation.value[x] is missing. |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `derived-from` | reference | `Observation.derivedFrom` | Related measurements the observation is made from |
| `device` | reference | `Observation.device` | The Device that generated the observation data. |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `focus` | reference | `Observation.focus` | The focus of an observation when the focus is not the patient of record. |
| `has-member` | reference | `Observation.hasMember` | Related resource that belongs to the Observation group |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `method` | token | `Observation.method` | The method used for the observation |
| `part-of` | reference | `Observation.partOf` | Part of referenced event |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `Observation.performer` | Who performed the observation |
| `specimen` | reference | `Observation.specimen` | Specimen used for this observation |
| `status` | token | `Observation.status` | The status of the observation |
| `subject` | reference | `Observation.subject` | The subject that the observation is about |
| `value-concept` | token | `(Observation.value as CodeableConcept)` | The value of the observation, if the value is a CodeableConcept |
| `value-date` | date | `(Observation.value as dateTime) | (Observation.value as Period)` | The value of the observation, if the value is a date or period of time |
| `value-quantity` | quantity | `(Observation.value as Quantity) | (Observation.value as SampledData)` | The value of the observation, if the value is a Quantity, or a SampledData (just search on the bounds of the values in sampled data) |
| `value-string` | string | `(Observation.value as string) | (Observation.value as CodeableConcept).text` | The value of the observation, if the value is a string, and also searches in CodeableConcept.text |

## Organization

| Code | Type | Expression | Description |
|---|---|---|---|
| `active` | token | `Organization.active` | Is the Organization record active |
| `address` | string | `Organization.address` | A server defined search that may match any of the string fields in the Address, including line, city, district, state, country, postalCode, and/or text |
| `address-city` | string | `Organization.address.city` | A city specified in an address |
| `address-country` | string | `Organization.address.country` | A country specified in an address |
| `address-postalcode` | string | `Organization.address.postalCode` | A postal code specified in an address |
| `address-state` | string | `Organization.address.state` | A state specified in an address |
| `address-use` | token | `Organization.address.use` | A use code specified in an address |
| `endpoint` | reference | `Organization.endpoint` | Technical endpoints providing access to services operated for the organization |
| `identifier` | token | `Organization.identifier` | Any identifier for the organization (not the accreditation issuer's identifier) |
| `name` | string | `Organization.name | Organization.alias` | A portion of the organization's name or alias |
| `partof` | reference | `Organization.partOf` | An organization of which this organization forms a part |
| `phonetic` | string | `Organization.name` | A portion of the organization's name using some kind of phonetic matching algorithm |
| `type` | token | `Organization.type` | A code for the type of organization |

## Patient

| Code | Type | Expression | Description |
|---|---|---|---|
| `active` | token | `Patient.active` | Whether the patient record is active |
| `address` | string | `Patient.address | Person.address | Practitioner.address | RelatedPerson.address` | Multiple Resources: 

* [Patient](patient.html): A server defined search that may match any of the string fields in the Address, including line, city, distric |
| `address-city` | string | `Patient.address.city | Person.address.city | Practitioner.address.city | RelatedPerson.address.city` | Multiple Resources: 

* [Patient](patient.html): A city specified in an address
* [Person](person.html): A city specified in an address
* [Practitioner](pra |
| `address-country` | string | `Patient.address.country | Person.address.country | Practitioner.address.country | RelatedPerson.address.country` | Multiple Resources: 

* [Patient](patient.html): A country specified in an address
* [Person](person.html): A country specified in an address
* [Practitione |
| `address-postalcode` | string | `Patient.address.postalCode | Person.address.postalCode | Practitioner.address.postalCode | RelatedPerson.address.postalCode` | Multiple Resources: 

* [Patient](patient.html): A postalCode specified in an address
* [Person](person.html): A postal code specified in an address
* [Prac |
| `address-state` | string | `Patient.address.state | Person.address.state | Practitioner.address.state | RelatedPerson.address.state` | Multiple Resources: 

* [Patient](patient.html): A state specified in an address
* [Person](person.html): A state specified in an address
* [Practitioner](p |
| `address-use` | token | `Patient.address.use | Person.address.use | Practitioner.address.use | RelatedPerson.address.use` | Multiple Resources: 

* [Patient](patient.html): A use code specified in an address
* [Person](person.html): A use code specified in an address
* [Practitio |
| `birthdate` | date | `Patient.birthDate | Person.birthDate | RelatedPerson.birthDate` | Multiple Resources: 

* [Patient](patient.html): The patient's date of birth
* [Person](person.html): The person's date of birth
* [RelatedPerson](relatedpe |
| `death-date` | date | `(Patient.deceased as dateTime)` | The date of death has been provided and satisfies this search value |
| `deceased` | token | `Patient.deceased.exists() and Patient.deceased != false` | This patient has been marked as deceased, or as a death date entered |
| `email` | token | `Patient.telecom.where(system='email') | Person.telecom.where(system='email') | Practitioner.telecom.where(system='email') | PractitionerRole.telecom.where(system='email') | RelatedPerson.telecom.where(system='email')` | Multiple Resources: 

* [Patient](patient.html): A value in an email contact
* [Person](person.html): A value in an email contact
* [Practitioner](practitio |
| `family` | string | `Patient.name.family | Practitioner.name.family` | Multiple Resources: 

* [Patient](patient.html): A portion of the family name of the patient
* [Practitioner](practitioner.html): A portion of the family nam |
| `gender` | token | `Patient.gender | Person.gender | Practitioner.gender | RelatedPerson.gender` | Multiple Resources: 

* [Patient](patient.html): Gender of the patient
* [Person](person.html): The gender of the person
* [Practitioner](practitioner.html) |
| `general-practitioner` | reference | `Patient.generalPractitioner` | Patient's nominated general practitioner, not the organization that manages the record |
| `given` | string | `Patient.name.given | Practitioner.name.given` | Multiple Resources: 

* [Patient](patient.html): A portion of the given name of the patient
* [Practitioner](practitioner.html): A portion of the given name |
| `identifier` | token | `Patient.identifier` | A patient identifier |
| `language` | token | `Patient.communication.language` | Language code (irrespective of use value) |
| `link` | reference | `Patient.link.other` | All patients linked to the given patient |
| `name` | string | `Patient.name` | A server defined search that may match any of the string fields in the HumanName, including family, give, prefix, suffix, suffix, and/or text |
| `organization` | reference | `Patient.managingOrganization` | The organization that is the custodian of the patient record |
| `phone` | token | `Patient.telecom.where(system='phone') | Person.telecom.where(system='phone') | Practitioner.telecom.where(system='phone') | PractitionerRole.telecom.where(system='phone') | RelatedPerson.telecom.where(system='phone')` | Multiple Resources: 

* [Patient](patient.html): A value in a phone contact
* [Person](person.html): A value in a phone contact
* [Practitioner](practitione |
| `phonetic` | string | `Patient.name | Person.name | Practitioner.name | RelatedPerson.name` | Multiple Resources: 

* [Patient](patient.html): A portion of either family or given name using some kind of phonetic matching algorithm
* [Person](person.ht |
| `telecom` | token | `Patient.telecom | Person.telecom | Practitioner.telecom | PractitionerRole.telecom | RelatedPerson.telecom` | Multiple Resources: 

* [Patient](patient.html): The value in any kind of telecom details of the patient
* [Person](person.html): The value in any kind of co |

## PaymentNotice

| Code | Type | Expression | Description |
|---|---|---|---|
| `created` | date | `PaymentNotice.created` | Creation date fro the notice |
| `identifier` | token | `PaymentNotice.identifier` | The business identifier of the notice |
| `payment-status` | token | `PaymentNotice.paymentStatus` | The type of payment notice |
| `provider` | reference | `PaymentNotice.provider` | The reference to the provider |
| `request` | reference | `PaymentNotice.request` | The Claim |
| `response` | reference | `PaymentNotice.response` | The ClaimResponse |
| `status` | token | `PaymentNotice.status` | The status of the payment notice |

## PaymentReconciliation

| Code | Type | Expression | Description |
|---|---|---|---|
| `created` | date | `PaymentReconciliation.created` | The creation date |
| `disposition` | string | `PaymentReconciliation.disposition` | The contents of the disposition message |
| `identifier` | token | `PaymentReconciliation.identifier` | The business identifier of the ExplanationOfBenefit |
| `outcome` | token | `PaymentReconciliation.outcome` | The processing outcome |
| `payment-issuer` | reference | `PaymentReconciliation.paymentIssuer` | The organization which generated this resource |
| `request` | reference | `PaymentReconciliation.request` | The reference to the claim |
| `requestor` | reference | `PaymentReconciliation.requestor` | The reference to the provider who submitted the claim |
| `status` | token | `PaymentReconciliation.status` | The status of the payment reconciliation |

## Practitioner

| Code | Type | Expression | Description |
|---|---|---|---|
| `active` | token | `Practitioner.active` | Whether the practitioner record is active |
| `address` | string | `Patient.address | Person.address | Practitioner.address | RelatedPerson.address` | Multiple Resources: 

* [Patient](patient.html): A server defined search that may match any of the string fields in the Address, including line, city, distric |
| `address-city` | string | `Patient.address.city | Person.address.city | Practitioner.address.city | RelatedPerson.address.city` | Multiple Resources: 

* [Patient](patient.html): A city specified in an address
* [Person](person.html): A city specified in an address
* [Practitioner](pra |
| `address-country` | string | `Patient.address.country | Person.address.country | Practitioner.address.country | RelatedPerson.address.country` | Multiple Resources: 

* [Patient](patient.html): A country specified in an address
* [Person](person.html): A country specified in an address
* [Practitione |
| `address-postalcode` | string | `Patient.address.postalCode | Person.address.postalCode | Practitioner.address.postalCode | RelatedPerson.address.postalCode` | Multiple Resources: 

* [Patient](patient.html): A postalCode specified in an address
* [Person](person.html): A postal code specified in an address
* [Prac |
| `address-state` | string | `Patient.address.state | Person.address.state | Practitioner.address.state | RelatedPerson.address.state` | Multiple Resources: 

* [Patient](patient.html): A state specified in an address
* [Person](person.html): A state specified in an address
* [Practitioner](p |
| `address-use` | token | `Patient.address.use | Person.address.use | Practitioner.address.use | RelatedPerson.address.use` | Multiple Resources: 

* [Patient](patient.html): A use code specified in an address
* [Person](person.html): A use code specified in an address
* [Practitio |
| `communication` | token | `Practitioner.communication` | One of the languages that the practitioner can communicate with |
| `email` | token | `Patient.telecom.where(system='email') | Person.telecom.where(system='email') | Practitioner.telecom.where(system='email') | PractitionerRole.telecom.where(system='email') | RelatedPerson.telecom.where(system='email')` | Multiple Resources: 

* [Patient](patient.html): A value in an email contact
* [Person](person.html): A value in an email contact
* [Practitioner](practitio |
| `family` | string | `Patient.name.family | Practitioner.name.family` | Multiple Resources: 

* [Patient](patient.html): A portion of the family name of the patient
* [Practitioner](practitioner.html): A portion of the family nam |
| `gender` | token | `Patient.gender | Person.gender | Practitioner.gender | RelatedPerson.gender` | Multiple Resources: 

* [Patient](patient.html): Gender of the patient
* [Person](person.html): The gender of the person
* [Practitioner](practitioner.html) |
| `given` | string | `Patient.name.given | Practitioner.name.given` | Multiple Resources: 

* [Patient](patient.html): A portion of the given name of the patient
* [Practitioner](practitioner.html): A portion of the given name |
| `identifier` | token | `Practitioner.identifier` | A practitioner's Identifier |
| `name` | string | `Practitioner.name` | A server defined search that may match any of the string fields in the HumanName, including family, give, prefix, suffix, suffix, and/or text |
| `phone` | token | `Patient.telecom.where(system='phone') | Person.telecom.where(system='phone') | Practitioner.telecom.where(system='phone') | PractitionerRole.telecom.where(system='phone') | RelatedPerson.telecom.where(system='phone')` | Multiple Resources: 

* [Patient](patient.html): A value in a phone contact
* [Person](person.html): A value in a phone contact
* [Practitioner](practitione |
| `phonetic` | string | `Patient.name | Person.name | Practitioner.name | RelatedPerson.name` | Multiple Resources: 

* [Patient](patient.html): A portion of either family or given name using some kind of phonetic matching algorithm
* [Person](person.ht |
| `telecom` | token | `Patient.telecom | Person.telecom | Practitioner.telecom | PractitionerRole.telecom | RelatedPerson.telecom` | Multiple Resources: 

* [Patient](patient.html): The value in any kind of telecom details of the patient
* [Person](person.html): The value in any kind of co |

## PractitionerRole

| Code | Type | Expression | Description |
|---|---|---|---|
| `active` | token | `PractitionerRole.active` | Whether this practitioner role record is in active use |
| `date` | date | `PractitionerRole.period` | The period during which the practitioner is authorized to perform in these role(s) |
| `email` | token | `Patient.telecom.where(system='email') | Person.telecom.where(system='email') | Practitioner.telecom.where(system='email') | PractitionerRole.telecom.where(system='email') | RelatedPerson.telecom.where(system='email')` | Multiple Resources: 

* [Patient](patient.html): A value in an email contact
* [Person](person.html): A value in an email contact
* [Practitioner](practitio |
| `endpoint` | reference | `PractitionerRole.endpoint` | Technical endpoints providing access to services operated for the practitioner with this role |
| `identifier` | token | `PractitionerRole.identifier` | A practitioner's Identifier |
| `location` | reference | `PractitionerRole.location` | One of the locations at which this practitioner provides care |
| `organization` | reference | `PractitionerRole.organization` | The identity of the organization the practitioner represents / acts on behalf of |
| `phone` | token | `Patient.telecom.where(system='phone') | Person.telecom.where(system='phone') | Practitioner.telecom.where(system='phone') | PractitionerRole.telecom.where(system='phone') | RelatedPerson.telecom.where(system='phone')` | Multiple Resources: 

* [Patient](patient.html): A value in a phone contact
* [Person](person.html): A value in a phone contact
* [Practitioner](practitione |
| `practitioner` | reference | `PractitionerRole.practitioner` | Practitioner that is able to provide the defined services for the organization |
| `role` | token | `PractitionerRole.code` | The practitioner can perform this role at for the organization |
| `service` | reference | `PractitionerRole.healthcareService` | The list of healthcare services that this worker provides for this role's Organization/Location(s) |
| `specialty` | token | `PractitionerRole.specialty` | The practitioner has this specialty at an organization |
| `telecom` | token | `Patient.telecom | Person.telecom | Practitioner.telecom | PractitionerRole.telecom | RelatedPerson.telecom` | Multiple Resources: 

* [Patient](patient.html): The value in any kind of telecom details of the patient
* [Person](person.html): The value in any kind of co |

## Procedure

| Code | Type | Expression | Description |
|---|---|---|---|
| `based-on` | reference | `Procedure.basedOn` | A request for this procedure |
| `category` | token | `Procedure.category` | Classification of the procedure |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instantiates-canonical` | reference | `Procedure.instantiatesCanonical` | Instantiates FHIR protocol or definition |
| `instantiates-uri` | uri | `Procedure.instantiatesUri` | Instantiates external protocol or definition |
| `location` | reference | `Procedure.location` | Where the procedure happened |
| `part-of` | reference | `Procedure.partOf` | Part of referenced event |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `Procedure.performer.actor` | The reference to the practitioner |
| `reason-code` | token | `Procedure.reasonCode` | Coded reason procedure performed |
| `reason-reference` | reference | `Procedure.reasonReference` | The justification that the procedure was performed |
| `status` | token | `Procedure.status` | preparation / in-progress / not-done / on-hold / stopped / completed / entered-in-error / unknown |
| `subject` | reference | `Procedure.subject` | Search by subject |

## QuestionnaireResponse

| Code | Type | Expression | Description |
|---|---|---|---|
| `author` | reference | `QuestionnaireResponse.author` | The author of the questionnaire response |
| `authored` | date | `QuestionnaireResponse.authored` | When the questionnaire response was last changed |
| `based-on` | reference | `QuestionnaireResponse.basedOn` | Plan/proposal/order fulfilled by this questionnaire response |
| `encounter` | reference | `QuestionnaireResponse.encounter` | Encounter associated with the questionnaire response |
| `identifier` | token | `QuestionnaireResponse.identifier` | The unique identifier for the questionnaire response |
| `part-of` | reference | `QuestionnaireResponse.partOf` | Procedure or observation this questionnaire response was performed as a part of |
| `patient` | reference | `QuestionnaireResponse.subject.where(resolve() is Patient)` | The patient that is the subject of the questionnaire response |
| `questionnaire` | reference | `QuestionnaireResponse.questionnaire` | The questionnaire the answers are provided for |
| `source` | reference | `QuestionnaireResponse.source` | The individual providing the information reflected in the questionnaire respose |
| `status` | token | `QuestionnaireResponse.status` | The status of the questionnaire response |
| `subject` | reference | `QuestionnaireResponse.subject` | The subject of the questionnaire response |

## RelatedPerson

| Code | Type | Expression | Description |
|---|---|---|---|
| `active` | token | `RelatedPerson.active` | Indicates if the related person record is active |
| `address` | string | `Patient.address | Person.address | Practitioner.address | RelatedPerson.address` | Multiple Resources: 

* [Patient](patient.html): A server defined search that may match any of the string fields in the Address, including line, city, distric |
| `address-city` | string | `Patient.address.city | Person.address.city | Practitioner.address.city | RelatedPerson.address.city` | Multiple Resources: 

* [Patient](patient.html): A city specified in an address
* [Person](person.html): A city specified in an address
* [Practitioner](pra |
| `address-country` | string | `Patient.address.country | Person.address.country | Practitioner.address.country | RelatedPerson.address.country` | Multiple Resources: 

* [Patient](patient.html): A country specified in an address
* [Person](person.html): A country specified in an address
* [Practitione |
| `address-postalcode` | string | `Patient.address.postalCode | Person.address.postalCode | Practitioner.address.postalCode | RelatedPerson.address.postalCode` | Multiple Resources: 

* [Patient](patient.html): A postalCode specified in an address
* [Person](person.html): A postal code specified in an address
* [Prac |
| `address-state` | string | `Patient.address.state | Person.address.state | Practitioner.address.state | RelatedPerson.address.state` | Multiple Resources: 

* [Patient](patient.html): A state specified in an address
* [Person](person.html): A state specified in an address
* [Practitioner](p |
| `address-use` | token | `Patient.address.use | Person.address.use | Practitioner.address.use | RelatedPerson.address.use` | Multiple Resources: 

* [Patient](patient.html): A use code specified in an address
* [Person](person.html): A use code specified in an address
* [Practitio |
| `birthdate` | date | `Patient.birthDate | Person.birthDate | RelatedPerson.birthDate` | Multiple Resources: 

* [Patient](patient.html): The patient's date of birth
* [Person](person.html): The person's date of birth
* [RelatedPerson](relatedpe |
| `email` | token | `Patient.telecom.where(system='email') | Person.telecom.where(system='email') | Practitioner.telecom.where(system='email') | PractitionerRole.telecom.where(system='email') | RelatedPerson.telecom.where(system='email')` | Multiple Resources: 

* [Patient](patient.html): A value in an email contact
* [Person](person.html): A value in an email contact
* [Practitioner](practitio |
| `gender` | token | `Patient.gender | Person.gender | Practitioner.gender | RelatedPerson.gender` | Multiple Resources: 

* [Patient](patient.html): Gender of the patient
* [Person](person.html): The gender of the person
* [Practitioner](practitioner.html) |
| `identifier` | token | `RelatedPerson.identifier` | An Identifier of the RelatedPerson |
| `name` | string | `RelatedPerson.name` | A server defined search that may match any of the string fields in the HumanName, including family, give, prefix, suffix, suffix, and/or text |
| `patient` | reference | `RelatedPerson.patient` | The patient this related person is related to |
| `phone` | token | `Patient.telecom.where(system='phone') | Person.telecom.where(system='phone') | Practitioner.telecom.where(system='phone') | PractitionerRole.telecom.where(system='phone') | RelatedPerson.telecom.where(system='phone')` | Multiple Resources: 

* [Patient](patient.html): A value in a phone contact
* [Person](person.html): A value in a phone contact
* [Practitioner](practitione |
| `phonetic` | string | `Patient.name | Person.name | Practitioner.name | RelatedPerson.name` | Multiple Resources: 

* [Patient](patient.html): A portion of either family or given name using some kind of phonetic matching algorithm
* [Person](person.ht |
| `relationship` | token | `RelatedPerson.relationship` | The relationship between the patient and the relatedperson |
| `telecom` | token | `Patient.telecom | Person.telecom | Practitioner.telecom | PractitionerRole.telecom | RelatedPerson.telecom` | Multiple Resources: 

* [Patient](patient.html): The value in any kind of telecom details of the patient
* [Person](person.html): The value in any kind of co |

## RiskAssessment

| Code | Type | Expression | Description |
|---|---|---|---|
| `condition` | reference | `RiskAssessment.condition` | Condition assessed |
| `date` | date | `AllergyIntolerance.recordedDate | CarePlan.period | CareTeam.period | ClinicalImpression.date | Composition.date | Consent.dateTime | DiagnosticReport.effective | Encounter.period | EpisodeOfCare.period | FamilyMemberHistory.date | Flag.period | Immunization.occurrence | List.date | Observation.effective | Procedure.performed | (RiskAssessment.occurrence as dateTime) | SupplyRequest.authoredOn` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Date first version of the resource instance was recorded
* [CarePlan](careplan.html):  |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `method` | token | `RiskAssessment.method` | Evaluation mechanism |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `RiskAssessment.performer` | Who did assessment? |
| `probability` | number | `RiskAssessment.prediction.probability` | Likelihood of specified outcome |
| `risk` | token | `RiskAssessment.prediction.qualitativeRisk` | Likelihood of specified outcome as a qualitative value |
| `subject` | reference | `RiskAssessment.subject` | Who/what does assessment apply to? |

## ServiceRequest

| Code | Type | Expression | Description |
|---|---|---|---|
| `authored` | date | `ServiceRequest.authoredOn` | Date request signed |
| `based-on` | reference | `ServiceRequest.basedOn` | What request fulfills |
| `body-site` | token | `ServiceRequest.bodySite` | Where procedure is going to be done |
| `category` | token | `ServiceRequest.category` | Classification of service |
| `code` | token | `AllergyIntolerance.code | AllergyIntolerance.reaction.substance | Condition.code | (DeviceRequest.code as CodeableConcept) | DiagnosticReport.code | FamilyMemberHistory.condition.code | List.code | Medication.code | (MedicationAdministration.medication as CodeableConcept) | (MedicationDispense.medication as CodeableConcept) | (MedicationRequest.medication as CodeableConcept) | (MedicationStatement.medication as CodeableConcept) | Observation.code | Procedure.code | ServiceRequest.code` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Code that identifies the allergy or intolerance
* [Condition](condition.html): Code fo |
| `encounter` | reference | `Composition.encounter | DeviceRequest.encounter | DiagnosticReport.encounter | DocumentReference.context.encounter | Flag.encounter | List.encounter | NutritionOrder.encounter | Observation.encounter | Procedure.encounter | RiskAssessment.encounter | ServiceRequest.encounter | VisionPrescription.encounter` | Multiple Resources: 

* [Composition](composition.html): Context of the Composition
* [DeviceRequest](devicerequest.html): Encounter during which request was |
| `identifier` | token | `AllergyIntolerance.identifier | CarePlan.identifier | CareTeam.identifier | Composition.identifier | Condition.identifier | Consent.identifier | DetectedIssue.identifier | DeviceRequest.identifier | DiagnosticReport.identifier | DocumentManifest.masterIdentifier | DocumentManifest.identifier | DocumentReference.masterIdentifier | DocumentReference.identifier | Encounter.identifier | EpisodeOfCare.identifier | FamilyMemberHistory.identifier | Goal.identifier | ImagingStudy.identifier | Immunization.identifier | List.identifier | MedicationAdministration.identifier | MedicationDispense.identifier | MedicationRequest.identifier | MedicationStatement.identifier | NutritionOrder.identifier | Observation.identifier | Procedure.identifier | RiskAssessment.identifier | ServiceRequest.identifier | SupplyDelivery.identifier | SupplyRequest.identifier | VisionPrescription.identifier` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): External ids for this item
* [CarePlan](careplan.html): External Ids for this plan
*  |
| `instantiates-canonical` | reference | `ServiceRequest.instantiatesCanonical` | Instantiates FHIR protocol or definition |
| `instantiates-uri` | uri | `ServiceRequest.instantiatesUri` | Instantiates external protocol or definition |
| `intent` | token | `ServiceRequest.intent` | proposal / plan / directive / order / original-order / reflex-order / filler-order / instance-order / option |
| `occurrence` | date | `ServiceRequest.occurrence` | When service should occur |
| `patient` | reference | `AllergyIntolerance.patient | CarePlan.subject.where(resolve() is Patient) | CareTeam.subject.where(resolve() is Patient) | ClinicalImpression.subject.where(resolve() is Patient) | Composition.subject.where(resolve() is Patient) | Condition.subject.where(resolve() is Patient) | Consent.patient | DetectedIssue.patient | DeviceRequest.subject.where(resolve() is Patient) | DeviceUseStatement.subject | DiagnosticReport.subject.where(resolve() is Patient) | DocumentManifest.subject.where(resolve() is Patient) | DocumentReference.subject.where(resolve() is Patient) | Encounter.subject.where(resolve() is Patient) | EpisodeOfCare.patient | FamilyMemberHistory.patient | Flag.subject.where(resolve() is Patient) | Goal.subject.where(resolve() is Patient) | ImagingStudy.subject.where(resolve() is Patient) | Immunization.patient | List.subject.where(resolve() is Patient) | MedicationAdministration.subject.where(resolve() is Patient) | MedicationDispense.subject.where(resolve() is Patient) | MedicationRequest.subject.where(resolve() is Patient) | MedicationStatement.subject.where(resolve() is Patient) | NutritionOrder.patient | Observation.subject.where(resolve() is Patient) | Procedure.subject.where(resolve() is Patient) | RiskAssessment.subject.where(resolve() is Patient) | ServiceRequest.subject.where(resolve() is Patient) | SupplyDelivery.patient | VisionPrescription.patient` | Multiple Resources: 

* [AllergyIntolerance](allergyintolerance.html): Who the sensitivity is for
* [CarePlan](careplan.html): Who the care plan is for
* [C |
| `performer` | reference | `ServiceRequest.performer` | Requested performer |
| `performer-type` | token | `ServiceRequest.performerType` | Performer role |
| `priority` | token | `ServiceRequest.priority` | routine / urgent / asap / stat |
| `replaces` | reference | `ServiceRequest.replaces` | What request replaces |
| `requester` | reference | `ServiceRequest.requester` | Who/what is requesting service |
| `requisition` | token | `ServiceRequest.requisition` | Composite Request ID |
| `specimen` | reference | `ServiceRequest.specimen` | Specimen to be tested |
| `status` | token | `ServiceRequest.status` | draft / active / on-hold / revoked / completed / entered-in-error / unknown |
| `subject` | reference | `ServiceRequest.subject` | Search by subject |

## Specimen

| Code | Type | Expression | Description |
|---|---|---|---|
| `accession` | token | `Specimen.accessionIdentifier` | The accession number associated with the specimen |
| `bodysite` | token | `Specimen.collection.bodySite` | The code for the body site from where the specimen originated |
| `collected` | date | `Specimen.collection.collected` | The date the specimen was collected |
| `collector` | reference | `Specimen.collection.collector` | Who collected the specimen |
| `container` | token | `Specimen.container.type` | The kind of specimen container |
| `container-id` | token | `Specimen.container.identifier` | The unique identifier associated with the specimen container |
| `identifier` | token | `Specimen.identifier` | The unique identifier associated with the specimen |
| `parent` | reference | `Specimen.parent` | The parent of the specimen |
| `patient` | reference | `Specimen.subject.where(resolve() is Patient)` | The patient the specimen comes from |
| `status` | token | `Specimen.status` | available / unavailable / unsatisfactory / entered-in-error |
| `subject` | reference | `Specimen.subject` | The subject of the specimen |
| `type` | token | `Specimen.type` | The specimen type |

## Substance

| Code | Type | Expression | Description |
|---|---|---|---|
| `category` | token | `Substance.category` | The category of the substance |
| `code` | token | `Substance.code | (Substance.ingredient.substance as CodeableConcept)` | The code of the substance or ingredient |
| `container-identifier` | token | `Substance.instance.identifier` | Identifier of the package/container |
| `expiry` | date | `Substance.instance.expiry` | Expiry date of package or container of substance |
| `identifier` | token | `Substance.identifier` | Unique identifier for the substance |
| `quantity` | quantity | `Substance.instance.quantity` | Amount of substance in the package |
| `status` | token | `Substance.status` | active / inactive / entered-in-error |
| `substance-reference` | reference | `(Substance.ingredient.substance as Reference)` | A component of the substance |

## Task

| Code | Type | Expression | Description |
|---|---|---|---|
| `authored-on` | date | `Task.authoredOn` | Search by creation date |
| `based-on` | reference | `Task.basedOn` | Search by requests this task is based on |
| `business-status` | token | `Task.businessStatus` | Search by business status |
| `code` | token | `Task.code` | Search by task code |
| `encounter` | reference | `Task.encounter` | Search by encounter |
| `focus` | reference | `Task.focus` | Search by task focus |
| `group-identifier` | token | `Task.groupIdentifier` | Search by group identifier |
| `identifier` | token | `Task.identifier` | Search for a task instance by its business identifier |
| `intent` | token | `Task.intent` | Search by task intent |
| `modified` | date | `Task.lastModified` | Search by last modification date |
| `owner` | reference | `Task.owner` | Search by task owner |
| `part-of` | reference | `Task.partOf` | Search by task this task is part of |
| `patient` | reference | `Task.for.where(resolve() is Patient)` | Search by patient |
| `performer` | token | `Task.performerType` | Search by recommended type of performer (e.g., Requester, Performer, Scheduler). |
| `period` | date | `Task.executionPeriod` | Search by period Task is/was underway |
| `priority` | token | `Task.priority` | Search by task priority |
| `requester` | reference | `Task.requester` | Search by task requester |
| `status` | token | `Task.status` | Search by task status |
| `subject` | reference | `Task.for` | Search by subject |


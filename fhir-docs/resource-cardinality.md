# FHIR R4 Cardinality Matrix

Source: `fhir-docs/sources/hl7-r4/profiles-resources.json` (HL7 FHIR R4). SATUSEHAT profile overrides still need manual mapping.

## Account

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Account number |
| `status` | 1..1 | code | active / inactive / entered-in-error / on-hold / unknown |
| `type` | 0..1 | CodeableConcept | E.g. patient, expense, depreciation |
| `name` | 0..1 | string | Human-readable label |
| `subject` | 0..* | Reference | The entity that caused the expenses |
| `servicePeriod` | 0..1 | Period | Transaction window |
| `coverage` | 0..* | BackboneElement | The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account |
| `owner` | 0..1 | Reference | Entity managing the Account |
| `description` | 0..1 | string | Explanation of purpose/use |
| `guarantor` | 0..* | BackboneElement | The parties ultimately responsible for balancing the Account |
| `partOf` | 0..1 | Reference | Reference to a parent Account |

## AllergyIntolerance

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External ids for this item |
| `clinicalStatus` | 0..1 | CodeableConcept | active / inactive / resolved |
| `verificationStatus` | 0..1 | CodeableConcept | unconfirmed / confirmed / refuted / entered-in-error |
| `type` | 0..1 | code | allergy / intolerance - Underlying mechanism (if known) |
| `category` | 0..* | code | food / medication / environment / biologic |
| `criticality` | 0..1 | code | low / high / unable-to-assess |
| `code` | 0..1 | CodeableConcept | Code that identifies the allergy or intolerance |
| `patient` | 1..1 | Reference | Who the sensitivity is for |
| `encounter` | 0..1 | Reference | Encounter when the allergy or intolerance was asserted |
| `onset[x]` | 0..1 | dateTime,Age,Period,Range,string | When allergy or intolerance was identified |
| `recordedDate` | 0..1 | dateTime | Date first version of the resource instance was recorded |
| `recorder` | 0..1 | Reference | Who recorded the sensitivity |
| `asserter` | 0..1 | Reference | Source of the information about the allergy |
| `lastOccurrence` | 0..1 | dateTime | Date(/time) of last known occurrence of a reaction |
| `note` | 0..* | Annotation | Additional text not captured in other fields |
| `reaction` | 0..* | BackboneElement | Adverse Reaction Events linked to exposure to substance |

## Bundle

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `identifier` | 0..1 | Identifier | Persistent identifier for the bundle |
| `type` | 1..1 | code | document / message / transaction / transaction-response / batch / batch-response / history / searchset / collection |
| `timestamp` | 0..1 | instant | When the bundle was assembled |
| `total` | 0..1 | unsignedInt | If search, the total number of matches |
| `link` | 0..* | BackboneElement | Links related to this Bundle |
| `entry` | 0..* | BackboneElement | Entry in the bundle - will have a resource or information |
| `signature` | 0..1 | Signature | Digital Signature |

## CarePlan

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Ids for this plan |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `basedOn` | 0..* | Reference | Fulfills CarePlan |
| `replaces` | 0..* | Reference | CarePlan replaced by this CarePlan |
| `partOf` | 0..* | Reference | Part of referenced CarePlan |
| `status` | 1..1 | code | draft / active / on-hold / revoked / completed / entered-in-error / unknown |
| `intent` | 1..1 | code | proposal / plan / order / option |
| `category` | 0..* | CodeableConcept | Type of plan |
| `title` | 0..1 | string | Human-friendly name for the care plan |
| `description` | 0..1 | string | Summary of nature of plan |
| `subject` | 1..1 | Reference | Who the care plan is for |
| `encounter` | 0..1 | Reference | Encounter created as part of |
| `period` | 0..1 | Period | Time period plan covers |
| `created` | 0..1 | dateTime | Date record was first recorded |
| `author` | 0..1 | Reference | Who is the designated responsible party |
| `contributor` | 0..* | Reference | Who provided the content of the care plan |
| `careTeam` | 0..* | Reference | Who's involved in plan? |
| `addresses` | 0..* | Reference | Health issues this plan addresses |
| `supportingInfo` | 0..* | Reference | Information considered as part of plan |
| `goal` | 0..* | Reference | Desired outcome of plan |
| `activity` | 0..* | BackboneElement | Action to occur as part of plan |
| `note` | 0..* | Annotation | Comments about the plan |

## ChargeItem

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for item |
| `definitionUri` | 0..* | uri | Defining information about the code of this charge item |
| `definitionCanonical` | 0..* | canonical | Resource defining the code of this ChargeItem |
| `status` | 1..1 | code | planned / billable / not-billable / aborted / billed / entered-in-error / unknown |
| `partOf` | 0..* | Reference | Part of referenced ChargeItem |
| `code` | 1..1 | CodeableConcept | A code that identifies the charge, like a billing code |
| `subject` | 1..1 | Reference | Individual service was done for/to |
| `context` | 0..1 | Reference | Encounter / Episode associated with event |
| `occurrence[x]` | 0..1 | dateTime,Period,Timing | When the charged service was applied |
| `performer` | 0..* | BackboneElement | Who performed charged service |
| `performingOrganization` | 0..1 | Reference | Organization providing the charged service |
| `requestingOrganization` | 0..1 | Reference | Organization requesting the charged service |
| `costCenter` | 0..1 | Reference | Organization that has ownership of the (potential, future) revenue |
| `quantity` | 0..1 | Quantity | Quantity of which the charge item has been serviced |
| `bodysite` | 0..* | CodeableConcept | Anatomical location, if relevant |
| `factorOverride` | 0..1 | decimal | Factor overriding the associated rules |
| `priceOverride` | 0..1 | Money | Price overriding the associated rules |
| `overrideReason` | 0..1 | string | Reason for overriding the list price/factor |
| `enterer` | 0..1 | Reference | Individual who was entering |
| `enteredDate` | 0..1 | dateTime | Date the charge item was entered |
| `reason` | 0..* | CodeableConcept | Why was the charged  service rendered? |
| `service` | 0..* | Reference | Which rendered service is being charged? |
| `product[x]` | 0..1 | Reference,CodeableConcept | Product charged |
| `account` | 0..* | Reference | Account to place this charge |
| `note` | 0..* | Annotation | Comments made about the ChargeItem |
| `supportingInformation` | 0..* | Reference | Further information supporting this charge |

## ChargeItemDefinition

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `url` | 1..1 | uri | Canonical identifier for this charge item definition, represented as a URI (globally unique) |
| `identifier` | 0..* | Identifier | Additional identifier for the charge item definition |
| `version` | 0..1 | string | Business version of the charge item definition |
| `title` | 0..1 | string | Name for this charge item definition (human friendly) |
| `derivedFromUri` | 0..* | uri | Underlying externally-defined charge item definition |
| `partOf` | 0..* | canonical | A larger definition of which this particular definition is a component or step |
| `replaces` | 0..* | canonical | Completed or terminated request(s) whose function is taken by this new request |
| `status` | 1..1 | code | draft / active / retired / unknown |
| `experimental` | 0..1 | boolean | For testing purposes, not real usage |
| `date` | 0..1 | dateTime | Date last changed |
| `publisher` | 0..1 | string | Name of the publisher (organization or individual) |
| `contact` | 0..* | ContactDetail | Contact details for the publisher |
| `description` | 0..1 | markdown | Natural language description of the charge item definition |
| `useContext` | 0..* | UsageContext | The context that the content is intended to support |
| `jurisdiction` | 0..* | CodeableConcept | Intended jurisdiction for charge item definition (if applicable) |
| `copyright` | 0..1 | markdown | Use and/or publishing restrictions |
| `approvalDate` | 0..1 | date | When the charge item definition was approved by publisher |
| `lastReviewDate` | 0..1 | date | When the charge item definition was last reviewed |
| `effectivePeriod` | 0..1 | Period | When the charge item definition is expected to be used |
| `code` | 0..1 | CodeableConcept | Billing codes or product types this definition applies to |
| `instance` | 0..* | Reference | Instances this definition applies to |
| `applicability` | 0..* | BackboneElement | Whether or not the billing code is applicable |
| `propertyGroup` | 0..* | BackboneElement | Group of properties which are applicable under the same conditions |

## ChargeItemResponse

No HL7 StructureDefinition found in downloaded bundle.

## Claim

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for claim |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `type` | 1..1 | CodeableConcept | Category or discipline |
| `subType` | 0..1 | CodeableConcept | More granular claim type |
| `use` | 1..1 | code | claim / preauthorization / predetermination |
| `patient` | 1..1 | Reference | The recipient of the products and services |
| `billablePeriod` | 0..1 | Period | Relevant time frame for the claim |
| `created` | 1..1 | dateTime | Resource creation date |
| `enterer` | 0..1 | Reference | Author of the claim |
| `insurer` | 0..1 | Reference | Target |
| `provider` | 1..1 | Reference | Party responsible for the claim |
| `priority` | 1..1 | CodeableConcept | Desired processing ugency |
| `fundsReserve` | 0..1 | CodeableConcept | For whom to reserve funds |
| `related` | 0..* | BackboneElement | Prior or corollary claims |
| `prescription` | 0..1 | Reference | Prescription authorizing services and products |
| `originalPrescription` | 0..1 | Reference | Original prescription if superseded by fulfiller |
| `payee` | 0..1 | BackboneElement | Recipient of benefits payable |
| `referral` | 0..1 | Reference | Treatment referral |
| `facility` | 0..1 | Reference | Servicing facility |
| `careTeam` | 0..* | BackboneElement | Members of the care team |
| `supportingInfo` | 0..* | BackboneElement | Supporting information |
| `diagnosis` | 0..* | BackboneElement | Pertinent diagnosis information |
| `procedure` | 0..* | BackboneElement | Clinical procedures performed |
| `insurance` | 1..* | BackboneElement | Patient insurance information |
| `accident` | 0..1 | BackboneElement | Details of the event |
| `item` | 0..* | BackboneElement | Product or service provided |
| `total` | 0..1 | Money | Total claim cost |

## ClaimResponse

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for a claim response |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `type` | 1..1 | CodeableConcept | More granular claim type |
| `subType` | 0..1 | CodeableConcept | More granular claim type |
| `use` | 1..1 | code | claim / preauthorization / predetermination |
| `patient` | 1..1 | Reference | The recipient of the products and services |
| `created` | 1..1 | dateTime | Response creation date |
| `insurer` | 1..1 | Reference | Party responsible for reimbursement |
| `requestor` | 0..1 | Reference | Party responsible for the claim |
| `request` | 0..1 | Reference | Id of resource triggering adjudication |
| `outcome` | 1..1 | code | queued / complete / error / partial |
| `disposition` | 0..1 | string | Disposition Message |
| `preAuthRef` | 0..1 | string | Preauthorization reference |
| `preAuthPeriod` | 0..1 | Period | Preauthorization reference effective period |
| `payeeType` | 0..1 | CodeableConcept | Party to be paid any benefits payable |
| `item` | 0..* | BackboneElement | Adjudication for claim line items |
| `addItem` | 0..* | BackboneElement | Insurer added line items |
| `adjudication` | 0..* |  | Header-level adjudication |
| `total` | 0..* | BackboneElement | Adjudication totals |
| `payment` | 0..1 | BackboneElement | Payment Details |
| `fundsReserve` | 0..1 | CodeableConcept | Funds reserved status |
| `formCode` | 0..1 | CodeableConcept | Printed form identifier |
| `form` | 0..1 | Attachment | Printed reference or actual form |
| `processNote` | 0..* | BackboneElement | Note concerning adjudication |
| `communicationRequest` | 0..* | Reference | Request for additional information |
| `insurance` | 0..* | BackboneElement | Patient insurance information |
| `error` | 0..* | BackboneElement | Processing errors |

## ClinicalImpression

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business identifier |
| `status` | 1..1 | code | in-progress / completed / entered-in-error |
| `statusReason` | 0..1 | CodeableConcept | Reason for current status |
| `code` | 0..1 | CodeableConcept | Kind of assessment performed |
| `description` | 0..1 | string | Why/how the assessment was performed |
| `subject` | 1..1 | Reference | Patient or group assessed |
| `encounter` | 0..1 | Reference | Encounter created as part of |
| `effective[x]` | 0..1 | dateTime,Period | Time of assessment |
| `date` | 0..1 | dateTime | When the assessment was documented |
| `assessor` | 0..1 | Reference | The clinician performing the assessment |
| `previous` | 0..1 | Reference | Reference to last assessment |
| `problem` | 0..* | Reference | Relevant impressions of patient state |
| `investigation` | 0..* | BackboneElement | One or more sets of investigations (signs, symptoms, etc.) |
| `protocol` | 0..* | uri | Clinical Protocol followed |
| `summary` | 0..1 | string | Summary of the assessment |
| `finding` | 0..* | BackboneElement | Possible or likely findings and diagnoses |
| `prognosisCodeableConcept` | 0..* | CodeableConcept | Estimate of likely outcome |
| `prognosisReference` | 0..* | Reference | RiskAssessment expressing likely outcome |
| `supportingInfo` | 0..* | Reference | Information supporting the clinical impression |
| `note` | 0..* | Annotation | Comments made about the ClinicalImpression |

## Composition

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..1 | Identifier | Version-independent identifier for the Composition |
| `status` | 1..1 | code | preliminary / final / amended / entered-in-error |
| `type` | 1..1 | CodeableConcept | Kind of composition (LOINC if possible) |
| `category` | 0..* | CodeableConcept | Categorization of Composition |
| `subject` | 0..1 | Reference | Who and/or what the composition is about |
| `encounter` | 0..1 | Reference | Context of the Composition |
| `date` | 1..1 | dateTime | Composition editing time |
| `author` | 1..* | Reference | Who and/or what authored the composition |
| `title` | 1..1 | string | Human Readable name/title |
| `confidentiality` | 0..1 | code | As defined by affinity domain |
| `attester` | 0..* | BackboneElement | Attests to accuracy of composition |
| `custodian` | 0..1 | Reference | Organization which maintains the composition |
| `relatesTo` | 0..* | BackboneElement | Relationships to other compositions/documents |
| `event` | 0..* | BackboneElement | The clinical service(s) being documented |
| `section` | 0..* | BackboneElement | Composition is broken into sections |

## Condition

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Ids for this condition |
| `clinicalStatus` | 0..1 | CodeableConcept | active / recurrence / relapse / inactive / remission / resolved |
| `verificationStatus` | 0..1 | CodeableConcept | unconfirmed / provisional / differential / confirmed / refuted / entered-in-error |
| `category` | 0..* | CodeableConcept | problem-list-item / encounter-diagnosis |
| `severity` | 0..1 | CodeableConcept | Subjective severity of condition |
| `code` | 0..1 | CodeableConcept | Identification of the condition, problem or diagnosis |
| `bodySite` | 0..* | CodeableConcept | Anatomical location, if relevant |
| `subject` | 1..1 | Reference | Who has the condition? |
| `encounter` | 0..1 | Reference | Encounter created as part of |
| `onset[x]` | 0..1 | dateTime,Age,Period,Range,string | Estimated or actual date,  date-time, or age |
| `abatement[x]` | 0..1 | dateTime,Age,Period,Range,string | When in resolution/remission |
| `recordedDate` | 0..1 | dateTime | Date record was first recorded |
| `recorder` | 0..1 | Reference | Who recorded the condition |
| `asserter` | 0..1 | Reference | Person who asserts this condition |
| `stage` | 0..* | BackboneElement | Stage/grade, usually assessed formally |
| `evidence` | 0..* | BackboneElement | Supporting evidence |
| `note` | 0..* | Annotation | Additional information about the Condition |

## Coverage

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for the coverage |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `type` | 0..1 | CodeableConcept | Coverage category such as medical or accident |
| `policyHolder` | 0..1 | Reference | Owner of the policy |
| `subscriber` | 0..1 | Reference | Subscriber to the policy |
| `subscriberId` | 0..1 | string | ID assigned to the subscriber |
| `beneficiary` | 1..1 | Reference | Plan beneficiary |
| `dependent` | 0..1 | string | Dependent number |
| `relationship` | 0..1 | CodeableConcept | Beneficiary relationship to the subscriber |
| `period` | 0..1 | Period | Coverage start and end dates |
| `payor` | 1..* | Reference | Issuer of the policy |
| `class` | 0..* | BackboneElement | Additional coverage classifications |
| `order` | 0..1 | positiveInt | Relative order of the coverage |
| `network` | 0..1 | string | Insurer network |
| `costToBeneficiary` | 0..* | BackboneElement | Patient payments for services/products |
| `subrogation` | 0..1 | boolean | Reimbursement to insurer |
| `contract` | 0..* | Reference | Contract details |

## CoverageEligibilityRequest

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for coverage eligiblity request |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `priority` | 0..1 | CodeableConcept | Desired processing priority |
| `purpose` | 1..* | code | auth-requirements / benefits / discovery / validation |
| `patient` | 1..1 | Reference | Intended recipient of products and services |
| `serviced[x]` | 0..1 | date,Period | Estimated date or dates of service |
| `created` | 1..1 | dateTime | Creation date |
| `enterer` | 0..1 | Reference | Author |
| `provider` | 0..1 | Reference | Party responsible for the request |
| `insurer` | 1..1 | Reference | Coverage issuer |
| `facility` | 0..1 | Reference | Servicing facility |
| `supportingInfo` | 0..* | BackboneElement | Supporting information |
| `insurance` | 0..* | BackboneElement | Patient insurance information |
| `item` | 0..* | BackboneElement | Item to be evaluated for eligibiity |

## CoverageEligibilityResponse

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for coverage eligiblity request |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `purpose` | 1..* | code | auth-requirements / benefits / discovery / validation |
| `patient` | 1..1 | Reference | Intended recipient of products and services |
| `serviced[x]` | 0..1 | date,Period | Estimated date or dates of service |
| `created` | 1..1 | dateTime | Response creation date |
| `requestor` | 0..1 | Reference | Party responsible for the request |
| `request` | 1..1 | Reference | Eligibility request reference |
| `outcome` | 1..1 | code | queued / complete / error / partial |
| `disposition` | 0..1 | string | Disposition Message |
| `insurer` | 1..1 | Reference | Coverage issuer |
| `insurance` | 0..* | BackboneElement | Patient insurance information |
| `preAuthRef` | 0..1 | string | Preauthorization reference |
| `form` | 0..1 | CodeableConcept | Printed form identifier |
| `error` | 0..* | BackboneElement | Processing errors |

## Device

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Instance identifier |
| `definition` | 0..1 | Reference | The reference to the definition for the device |
| `udiCarrier` | 0..* | BackboneElement | Unique Device Identifier (UDI) Barcode string |
| `status` | 0..1 | code | active / inactive / entered-in-error / unknown |
| `statusReason` | 0..* | CodeableConcept | online / paused / standby / offline / not-ready / transduc-discon / hw-discon / off |
| `distinctIdentifier` | 0..1 | string | The distinct identification string |
| `manufacturer` | 0..1 | string | Name of device manufacturer |
| `manufactureDate` | 0..1 | dateTime | Date when the device was made |
| `expirationDate` | 0..1 | dateTime | Date and time of expiry of this device (if applicable) |
| `lotNumber` | 0..1 | string | Lot number of manufacture |
| `serialNumber` | 0..1 | string | Serial number assigned by the manufacturer |
| `deviceName` | 0..* | BackboneElement | The name of the device as given by the manufacturer |
| `modelNumber` | 0..1 | string | The model number for the device |
| `partNumber` | 0..1 | string | The part number of the device |
| `type` | 0..1 | CodeableConcept | The kind or type of device |
| `specialization` | 0..* | BackboneElement | The capabilities supported on a  device, the standards to which the device conforms for a particular purpose, and used for the communication |
| `version` | 0..* | BackboneElement | The actual design of the device or software version running on the device |
| `property` | 0..* | BackboneElement | The actual configuration settings of a device as it actually operates, e.g., regulation status, time properties |
| `patient` | 0..1 | Reference | Patient to whom Device is affixed |
| `owner` | 0..1 | Reference | Organization responsible for device |
| `contact` | 0..* | ContactPoint | Details for human/organization for support |
| `location` | 0..1 | Reference | Where the device is found |
| `url` | 0..1 | uri | Network address to contact device |
| `note` | 0..* | Annotation | Device notes and comments |
| `safety` | 0..* | CodeableConcept | Safety Characteristics of Device |
| `parent` | 0..1 | Reference | The parent device |

## DiagnosticReport

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business identifier for report |
| `basedOn` | 0..* | Reference | What was requested |
| `status` | 1..1 | code | registered / partial / preliminary / final + |
| `category` | 0..* | CodeableConcept | Service category |
| `code` | 1..1 | CodeableConcept | Name/Code for this diagnostic report |
| `subject` | 0..1 | Reference | The subject of the report - usually, but not always, the patient |
| `encounter` | 0..1 | Reference | Health care event when test ordered |
| `effective[x]` | 0..1 | dateTime,Period | Clinically relevant time/time-period for report |
| `issued` | 0..1 | instant | DateTime this version was made |
| `performer` | 0..* | Reference | Responsible Diagnostic Service |
| `resultsInterpreter` | 0..* | Reference | Primary result interpreter |
| `specimen` | 0..* | Reference | Specimens this report is based on |
| `result` | 0..* | Reference | Observations |
| `imagingStudy` | 0..* | Reference | Reference to full details of imaging associated with the diagnostic report |
| `media` | 0..* | BackboneElement | Key images associated with this report |
| `conclusion` | 0..1 | string | Clinical conclusion (interpretation) of test results |
| `conclusionCode` | 0..* | CodeableConcept | Codes for the clinical conclusion of test results |
| `presentedForm` | 0..* | Attachment | Entire report as issued |

## DocumentReference

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `masterIdentifier` | 0..1 | Identifier | Master Version Specific Identifier |
| `identifier` | 0..* | Identifier | Other identifiers for the document |
| `status` | 1..1 | code | current / superseded / entered-in-error |
| `docStatus` | 0..1 | code | preliminary / final / amended / entered-in-error |
| `type` | 0..1 | CodeableConcept | Kind of document (LOINC if possible) |
| `category` | 0..* | CodeableConcept | Categorization of document |
| `subject` | 0..1 | Reference | Who/what is the subject of the document |
| `date` | 0..1 | instant | When this document reference was created |
| `author` | 0..* | Reference | Who and/or what authored the document |
| `authenticator` | 0..1 | Reference | Who/what authenticated the document |
| `custodian` | 0..1 | Reference | Organization which maintains the document |
| `relatesTo` | 0..* | BackboneElement | Relationships to other documents |
| `description` | 0..1 | string | Human-readable description |
| `securityLabel` | 0..* | CodeableConcept | Document security-tags |
| `content` | 1..* | BackboneElement | Document referenced |
| `context` | 0..1 | BackboneElement | Clinical context of document |

## Encounter

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Identifier(s) by which this encounter is known |
| `status` | 1..1 | code | planned / arrived / triaged / in-progress / onleave / finished / cancelled + |
| `statusHistory` | 0..* | BackboneElement | List of past encounter statuses |
| `class` | 1..1 | Coding | Classification of patient encounter |
| `classHistory` | 0..* | BackboneElement | List of past encounter classes |
| `type` | 0..* | CodeableConcept | Specific type of encounter |
| `serviceType` | 0..1 | CodeableConcept | Specific type of service |
| `priority` | 0..1 | CodeableConcept | Indicates the urgency of the encounter |
| `subject` | 0..1 | Reference | The patient or group present at the encounter |
| `episodeOfCare` | 0..* | Reference | Episode(s) of care that this encounter should be recorded against |
| `basedOn` | 0..* | Reference | The ServiceRequest that initiated this encounter |
| `participant` | 0..* | BackboneElement | List of participants involved in the encounter |
| `appointment` | 0..* | Reference | The appointment that scheduled this encounter |
| `period` | 0..1 | Period | The start and end time of the encounter |
| `length` | 0..1 | Duration | Quantity of time the encounter lasted (less time absent) |
| `reasonCode` | 0..* | CodeableConcept | Coded reason the encounter takes place |
| `reasonReference` | 0..* | Reference | Reason the encounter takes place (reference) |
| `diagnosis` | 0..* | BackboneElement | The list of diagnosis relevant to this encounter |
| `account` | 0..* | Reference | The set of accounts that may be used for billing for this Encounter |
| `hospitalization` | 0..1 | BackboneElement | Details about the admission to a healthcare service |
| `location` | 0..* | BackboneElement | List of locations where the patient has been |
| `serviceProvider` | 0..1 | Reference | The organization (facility) responsible for this encounter |
| `partOf` | 0..1 | Reference | Another Encounter this encounter is part of |

## EpisodeOfCare

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier(s) relevant for this EpisodeOfCare |
| `status` | 1..1 | code | planned / waitlist / active / onhold / finished / cancelled / entered-in-error |
| `statusHistory` | 0..* | BackboneElement | Past list of status codes (the current status may be included to cover the start date of the status) |
| `type` | 0..* | CodeableConcept | Type/class  - e.g. specialist referral, disease management |
| `diagnosis` | 0..* | BackboneElement | The list of diagnosis relevant to this episode of care |
| `patient` | 1..1 | Reference | The patient who is the focus of this episode of care |
| `managingOrganization` | 0..1 | Reference | Organization that assumes care |
| `period` | 0..1 | Period | Interval during responsibility is assumed |
| `referralRequest` | 0..* | Reference | Originating Referral Request(s) |
| `careManager` | 0..1 | Reference | Care manager/care coordinator for the patient |
| `team` | 0..* | Reference | Other practitioners facilitating this episode of care |
| `account` | 0..* | Reference | The set of accounts that may be used for billing for this EpisodeOfCare |

## FamilyMemberHistory

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Id(s) for this record |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `status` | 1..1 | code | partial / completed / entered-in-error / health-unknown |
| `dataAbsentReason` | 0..1 | CodeableConcept | subject-unknown / withheld / unable-to-obtain / deferred |
| `patient` | 1..1 | Reference | Patient history is about |
| `date` | 0..1 | dateTime | When history was recorded or last updated |
| `name` | 0..1 | string | The family member described |
| `relationship` | 1..1 | CodeableConcept | Relationship to the subject |
| `sex` | 0..1 | CodeableConcept | male / female / other / unknown |
| `born[x]` | 0..1 | Period,date,string | (approximate) date of birth |
| `age[x]` | 0..1 | Age,Range,string | (approximate) age |
| `estimatedAge` | 0..1 | boolean | Age is estimated? |
| `deceased[x]` | 0..1 | boolean,Age,Range,date,string | Dead? How old/when? |
| `reasonCode` | 0..* | CodeableConcept | Why was family member history performed? |
| `reasonReference` | 0..* | Reference | Why was family member history performed? |
| `note` | 0..* | Annotation | General note about related person |
| `condition` | 0..* | BackboneElement | Condition that the related person had |

## GenomicStudy

No HL7 StructureDefinition found in downloaded bundle.

## Goal

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Ids for this goal |
| `lifecycleStatus` | 1..1 | code | proposed / planned / accepted / active / on-hold / completed / cancelled / entered-in-error / rejected |
| `achievementStatus` | 0..1 | CodeableConcept | in-progress / improving / worsening / no-change / achieved / sustaining / not-achieved / no-progress / not-attainable |
| `category` | 0..* | CodeableConcept | E.g. Treatment, dietary, behavioral, etc. |
| `priority` | 0..1 | CodeableConcept | high-priority / medium-priority / low-priority |
| `description` | 1..1 | CodeableConcept | Code or text describing goal |
| `subject` | 1..1 | Reference | Who this goal is intended for |
| `start[x]` | 0..1 | date,CodeableConcept | When goal pursuit begins |
| `target` | 0..* | BackboneElement | Target outcome for the goal |
| `statusDate` | 0..1 | date | When goal status took effect |
| `statusReason` | 0..1 | string | Reason for current status |
| `expressedBy` | 0..1 | Reference | Who's responsible for creating Goal? |
| `addresses` | 0..* | Reference | Issues addressed by this goal |
| `note` | 0..* | Annotation | Comments about the goal |
| `outcomeCode` | 0..* | CodeableConcept | What result was achieved regarding the goal? |
| `outcomeReference` | 0..* | Reference | Observation that resulted from goal |

## Group

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Unique id |
| `active` | 0..1 | boolean | Whether this group's record is in active use |
| `type` | 1..1 | code | person / animal / practitioner / device / medication / substance |
| `actual` | 1..1 | boolean | Descriptive or actual |
| `code` | 0..1 | CodeableConcept | Kind of Group members |
| `name` | 0..1 | string | Label for Group |
| `quantity` | 0..1 | unsignedInt | Number of members |
| `managingEntity` | 0..1 | Reference | Entity that is the custodian of the Group's definition |
| `characteristic` | 0..* | BackboneElement | Include / Exclude group members by Trait |
| `member` | 0..* | BackboneElement | Who or what is in group |

## ImagingStudy

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Identifiers for the whole study |
| `status` | 1..1 | code | registered / available / cancelled / entered-in-error / unknown |
| `modality` | 0..* | Coding | All series modality if actual acquisition modalities |
| `subject` | 1..1 | Reference | Who or what is the subject of the study |
| `encounter` | 0..1 | Reference | Encounter with which this imaging study is associated |
| `started` | 0..1 | dateTime | When the study was started |
| `basedOn` | 0..* | Reference | Request fulfilled |
| `referrer` | 0..1 | Reference | Referring physician |
| `interpreter` | 0..* | Reference | Who interpreted images |
| `endpoint` | 0..* | Reference | Study access endpoint |
| `numberOfSeries` | 0..1 | unsignedInt | Number of Study Related Series |
| `numberOfInstances` | 0..1 | unsignedInt | Number of Study Related Instances |
| `procedureReference` | 0..1 | Reference | The performed Procedure reference |
| `procedureCode` | 0..* | CodeableConcept | The performed procedure code |
| `location` | 0..1 | Reference | Where ImagingStudy occurred |
| `reasonCode` | 0..* | CodeableConcept | Why the study was requested |
| `reasonReference` | 0..* | Reference | Why was study performed |
| `note` | 0..* | Annotation | User-defined comments |
| `description` | 0..1 | string | Institution-generated description |
| `series` | 0..* | BackboneElement | Each study has one or more series of instances |

## Immunization

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business identifier |
| `status` | 1..1 | code | completed / entered-in-error / not-done |
| `statusReason` | 0..1 | CodeableConcept | Reason not done |
| `vaccineCode` | 1..1 | CodeableConcept | Vaccine product administered |
| `patient` | 1..1 | Reference | Who was immunized |
| `encounter` | 0..1 | Reference | Encounter immunization was part of |
| `occurrence[x]` | 1..1 | dateTime,string | Vaccine administration date |
| `recorded` | 0..1 | dateTime | When the immunization was first captured in the subject's record |
| `primarySource` | 0..1 | boolean | Indicates context the data was recorded in |
| `reportOrigin` | 0..1 | CodeableConcept | Indicates the source of a secondarily reported record |
| `location` | 0..1 | Reference | Where immunization occurred |
| `manufacturer` | 0..1 | Reference | Vaccine manufacturer |
| `lotNumber` | 0..1 | string | Vaccine lot number |
| `expirationDate` | 0..1 | date | Vaccine expiration date |
| `site` | 0..1 | CodeableConcept | Body site vaccine  was administered |
| `route` | 0..1 | CodeableConcept | How vaccine entered body |
| `doseQuantity` | 0..1 | Quantity | Amount of vaccine administered |
| `performer` | 0..* | BackboneElement | Who performed event |
| `note` | 0..* | Annotation | Additional immunization notes |
| `reasonCode` | 0..* | CodeableConcept | Why immunization occurred |
| `reasonReference` | 0..* | Reference | Why immunization occurred |
| `isSubpotent` | 0..1 | boolean | Dose potency |
| `subpotentReason` | 0..* | CodeableConcept | Reason for being subpotent |
| `education` | 0..* | BackboneElement | Educational material presented to patient |
| `programEligibility` | 0..* | CodeableConcept | Patient eligibility for a vaccination program |
| `fundingSource` | 0..1 | CodeableConcept | Funding source for the vaccine |
| `reaction` | 0..* | BackboneElement | Details of a reaction that follows immunization |
| `protocolApplied` | 0..* | BackboneElement | Protocol followed by the provider |

## Invoice

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for item |
| `status` | 1..1 | code | draft / issued / balanced / cancelled / entered-in-error |
| `cancelledReason` | 0..1 | string | Reason for cancellation of this Invoice |
| `type` | 0..1 | CodeableConcept | Type of Invoice |
| `subject` | 0..1 | Reference | Recipient(s) of goods and services |
| `recipient` | 0..1 | Reference | Recipient of this invoice |
| `date` | 0..1 | dateTime | Invoice date / posting date |
| `participant` | 0..* | BackboneElement | Participant in creation of this Invoice |
| `issuer` | 0..1 | Reference | Issuing Organization of Invoice |
| `account` | 0..1 | Reference | Account that is being balanced |
| `lineItem` | 0..* | BackboneElement | Line items of this Invoice |
| `totalPriceComponent` | 0..* |  | Components of Invoice total |
| `totalNet` | 0..1 | Money | Net total of this Invoice |
| `totalGross` | 0..1 | Money | Gross total of this Invoice |
| `paymentTerms` | 0..1 | markdown | Payment details |
| `note` | 0..* | Annotation | Comments made about the invoice |

## Location

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Unique code or number identifying the location to its users |
| `status` | 0..1 | code | active / suspended / inactive |
| `operationalStatus` | 0..1 | Coding | The operational status of the location (typically only for a bed/room) |
| `name` | 0..1 | string | Name of the location as used by humans |
| `alias` | 0..* | string | A list of alternate names that the location is known as, or was known as, in the past |
| `description` | 0..1 | string | Additional details about the location that could be displayed as further information to identify the location beyond its name |
| `mode` | 0..1 | code | instance / kind |
| `type` | 0..* | CodeableConcept | Type of function performed |
| `telecom` | 0..* | ContactPoint | Contact details of the location |
| `address` | 0..1 | Address | Physical location |
| `physicalType` | 0..1 | CodeableConcept | Physical form of the location |
| `position` | 0..1 | BackboneElement | The absolute geographic location |
| `managingOrganization` | 0..1 | Reference | Organization responsible for provisioning and upkeep |
| `partOf` | 0..1 | Reference | Another Location this one is physically a part of |
| `hoursOfOperation` | 0..* | BackboneElement | What days/times during a week is this location usually open |
| `availabilityExceptions` | 0..1 | string | Description of availability exceptions |
| `endpoint` | 0..* | Reference | Technical endpoints providing access to services operated for the location |

## Medication

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business identifier for this medication |
| `code` | 0..1 | CodeableConcept | Codes that identify this medication |
| `status` | 0..1 | code | active / inactive / entered-in-error |
| `manufacturer` | 0..1 | Reference | Manufacturer of the item |
| `form` | 0..1 | CodeableConcept | powder / tablets / capsule + |
| `amount` | 0..1 | Ratio | Amount of drug in package |
| `ingredient` | 0..* | BackboneElement | Active or inactive ingredient |
| `batch` | 0..1 | BackboneElement | Details about packaged medications |

## MedicationAdministration

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External identifier |
| `instantiates` | 0..* | uri | Instantiates protocol or definition |
| `partOf` | 0..* | Reference | Part of referenced event |
| `status` | 1..1 | code | in-progress / not-done / on-hold / completed / entered-in-error / stopped / unknown |
| `statusReason` | 0..* | CodeableConcept | Reason administration not performed |
| `category` | 0..1 | CodeableConcept | Type of medication usage |
| `medication[x]` | 1..1 | CodeableConcept,Reference | What was administered |
| `subject` | 1..1 | Reference | Who received medication |
| `context` | 0..1 | Reference | Encounter or Episode of Care administered as part of |
| `supportingInformation` | 0..* | Reference | Additional information to support administration |
| `effective[x]` | 1..1 | dateTime,Period | Start and end time of administration |
| `performer` | 0..* | BackboneElement | Who performed the medication administration and what they did |
| `reasonCode` | 0..* | CodeableConcept | Reason administration performed |
| `reasonReference` | 0..* | Reference | Condition or observation that supports why the medication was administered |
| `request` | 0..1 | Reference | Request administration performed against |
| `device` | 0..* | Reference | Device used to administer |
| `note` | 0..* | Annotation | Information about the administration |
| `dosage` | 0..1 | BackboneElement | Details of how medication was taken |
| `eventHistory` | 0..* | Reference | A list of events of interest in the lifecycle |

## MedicationDispense

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External identifier |
| `partOf` | 0..* | Reference | Event that dispense is part of |
| `status` | 1..1 | code | preparation / in-progress / cancelled / on-hold / completed / entered-in-error / stopped / declined / unknown |
| `statusReason[x]` | 0..1 | CodeableConcept,Reference | Why a dispense was not performed |
| `category` | 0..1 | CodeableConcept | Type of medication dispense |
| `medication[x]` | 1..1 | CodeableConcept,Reference | What medication was supplied |
| `subject` | 0..1 | Reference | Who the dispense is for |
| `context` | 0..1 | Reference | Encounter / Episode associated with event |
| `supportingInformation` | 0..* | Reference | Information that supports the dispensing of the medication |
| `performer` | 0..* | BackboneElement | Who performed event |
| `location` | 0..1 | Reference | Where the dispense occurred |
| `authorizingPrescription` | 0..* | Reference | Medication order that authorizes the dispense |
| `type` | 0..1 | CodeableConcept | Trial fill, partial fill, emergency fill, etc. |
| `quantity` | 0..1 | Quantity | Amount dispensed |
| `daysSupply` | 0..1 | Quantity | Amount of medication expressed as a timing amount |
| `whenPrepared` | 0..1 | dateTime | When product was packaged and reviewed |
| `whenHandedOver` | 0..1 | dateTime | When product was given out |
| `destination` | 0..1 | Reference | Where the medication was sent |
| `receiver` | 0..* | Reference | Who collected the medication |
| `note` | 0..* | Annotation | Information about the dispense |
| `dosageInstruction` | 0..* | Dosage | How the medication is to be used by the patient or administered by the caregiver |
| `substitution` | 0..1 | BackboneElement | Whether a substitution was performed on the dispense |
| `detectedIssue` | 0..* | Reference | Clinical issue with action |
| `eventHistory` | 0..* | Reference | A list of relevant lifecycle events |

## MedicationRequest

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External ids for this request |
| `status` | 1..1 | code | active / on-hold / cancelled / completed / entered-in-error / stopped / draft / unknown |
| `statusReason` | 0..1 | CodeableConcept | Reason for current status |
| `intent` | 1..1 | code | proposal / plan / order / original-order / reflex-order / filler-order / instance-order / option |
| `category` | 0..* | CodeableConcept | Type of medication usage |
| `priority` | 0..1 | code | routine / urgent / asap / stat |
| `doNotPerform` | 0..1 | boolean | True if request is prohibiting action |
| `reported[x]` | 0..1 | boolean,Reference | Reported rather than primary record |
| `medication[x]` | 1..1 | CodeableConcept,Reference | Medication to be taken |
| `subject` | 1..1 | Reference | Who or group medication request is for |
| `encounter` | 0..1 | Reference | Encounter created as part of encounter/admission/stay |
| `supportingInformation` | 0..* | Reference | Information to support ordering of the medication |
| `authoredOn` | 0..1 | dateTime | When request was initially authored |
| `requester` | 0..1 | Reference | Who/What requested the Request |
| `performer` | 0..1 | Reference | Intended performer of administration |
| `performerType` | 0..1 | CodeableConcept | Desired kind of performer of the medication administration |
| `recorder` | 0..1 | Reference | Person who entered the request |
| `reasonCode` | 0..* | CodeableConcept | Reason or indication for ordering or not ordering the medication |
| `reasonReference` | 0..* | Reference | Condition or observation that supports why the prescription is being written |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `basedOn` | 0..* | Reference | What request fulfills |
| `groupIdentifier` | 0..1 | Identifier | Composite request this is part of |
| `courseOfTherapyType` | 0..1 | CodeableConcept | Overall pattern of medication administration |
| `insurance` | 0..* | Reference | Associated insurance coverage |
| `note` | 0..* | Annotation | Information about the prescription |
| `dosageInstruction` | 0..* | Dosage | How the medication should be taken |
| `dispenseRequest` | 0..1 | BackboneElement | Medication supply authorization |
| `substitution` | 0..1 | BackboneElement | Any restrictions on medication substitution |
| `priorPrescription` | 0..1 | Reference | An order/prescription that is being replaced |
| `detectedIssue` | 0..* | Reference | Clinical Issue with action |
| `eventHistory` | 0..* | Reference | A list of events of interest in the lifecycle |

## MedicationStatement

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External identifier |
| `basedOn` | 0..* | Reference | Fulfils plan, proposal or order |
| `partOf` | 0..* | Reference | Part of referenced event |
| `status` | 1..1 | code | active / completed / entered-in-error / intended / stopped / on-hold / unknown / not-taken |
| `statusReason` | 0..* | CodeableConcept | Reason for current status |
| `category` | 0..1 | CodeableConcept | Type of medication usage |
| `medication[x]` | 1..1 | CodeableConcept,Reference | What medication was taken |
| `subject` | 1..1 | Reference | Who is/was taking  the medication |
| `context` | 0..1 | Reference | Encounter / Episode associated with MedicationStatement |
| `effective[x]` | 0..1 | dateTime,Period | The date/time or interval when the medication is/was/will be taken |
| `dateAsserted` | 0..1 | dateTime | When the statement was asserted? |
| `informationSource` | 0..1 | Reference | Person or organization that provided the information about the taking of this medication |
| `derivedFrom` | 0..* | Reference | Additional supporting information |
| `reasonCode` | 0..* | CodeableConcept | Reason for why the medication is being/was taken |
| `reasonReference` | 0..* | Reference | Condition or observation that supports why the medication is being/was taken |
| `note` | 0..* | Annotation | Further information about the statement |
| `dosage` | 0..* | Dosage | Details of how medication is/was taken or should be taken |

## MolecularSequence

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Unique ID for this particular sequence. This is a FHIR-defined id |
| `type` | 0..1 | code | aa / dna / rna |
| `coordinateSystem` | 1..1 | integer | Base number of coordinate system (0 for 0-based numbering or coordinates, inclusive start, exclusive end, 1 for 1-based numbering, inclusive start, inclusive end) |
| `patient` | 0..1 | Reference | Who and/or what this is about |
| `specimen` | 0..1 | Reference | Specimen used for sequencing |
| `device` | 0..1 | Reference | The method for sequencing |
| `performer` | 0..1 | Reference | Who should be responsible for test result |
| `quantity` | 0..1 | Quantity | The number of copies of the sequence of interest.  (RNASeq) |
| `referenceSeq` | 0..1 | BackboneElement | A sequence used as reference |
| `variant` | 0..* | BackboneElement | Variant in sequence |
| `observedSeq` | 0..1 | string | Sequence that was observed |
| `quality` | 0..* | BackboneElement | An set of value as quality of sequence |
| `readCoverage` | 0..1 | integer | Average number of reads representing a given nucleotide in the reconstructed sequence |
| `repository` | 0..* | BackboneElement | External repository which contains detailed report related with observedSeq in this resource |
| `pointer` | 0..* | Reference | Pointer to next atomic sequence |
| `structureVariant` | 0..* | BackboneElement | Structural variant |

## NutritionOrder

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Identifiers assigned to this order |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `instantiates` | 0..* | uri | Instantiates protocol or definition |
| `status` | 1..1 | code | draft / active / on-hold / revoked / completed / entered-in-error / unknown |
| `intent` | 1..1 | code | proposal / plan / directive / order / original-order / reflex-order / filler-order / instance-order / option |
| `patient` | 1..1 | Reference | The person who requires the diet, formula or nutritional supplement |
| `encounter` | 0..1 | Reference | The encounter associated with this nutrition order |
| `dateTime` | 1..1 | dateTime | Date and time the nutrition order was requested |
| `orderer` | 0..1 | Reference | Who ordered the diet, formula or nutritional supplement |
| `allergyIntolerance` | 0..* | Reference | List of the patient's food and nutrition-related allergies and intolerances |
| `foodPreferenceModifier` | 0..* | CodeableConcept | Order-specific modifier about the type of food that should be given |
| `excludeFoodModifier` | 0..* | CodeableConcept | Order-specific modifier about the type of food that should not be given |
| `oralDiet` | 0..1 | BackboneElement | Oral diet components |
| `supplement` | 0..* | BackboneElement | Supplement components |
| `enteralFormula` | 0..1 | BackboneElement | Enteral formula components |
| `note` | 0..* | Annotation | Comments |

## Observation

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for observation |
| `basedOn` | 0..* | Reference | Fulfills plan, proposal or order |
| `partOf` | 0..* | Reference | Part of referenced event |
| `status` | 1..1 | code | registered / preliminary / final / amended + |
| `category` | 0..* | CodeableConcept | Classification of  type of observation |
| `code` | 1..1 | CodeableConcept | Type of observation (code / type) |
| `subject` | 0..1 | Reference | Who and/or what the observation is about |
| `focus` | 0..* | Reference | What the observation is about, when it is not about the subject of record |
| `encounter` | 0..1 | Reference | Healthcare event during which this observation is made |
| `effective[x]` | 0..1 | dateTime,Period,Timing,instant | Clinically relevant time/time-period for observation |
| `issued` | 0..1 | instant | Date/Time this version was made available |
| `performer` | 0..* | Reference | Who is responsible for the observation |
| `value[x]` | 0..1 | Quantity,CodeableConcept,string,boolean,integer,Range,Ratio,SampledData,time,dateTime,Period | Actual result |
| `dataAbsentReason` | 0..1 | CodeableConcept | Why the result is missing |
| `interpretation` | 0..* | CodeableConcept | High, low, normal, etc. |
| `note` | 0..* | Annotation | Comments about the observation |
| `bodySite` | 0..1 | CodeableConcept | Observed body part |
| `method` | 0..1 | CodeableConcept | How it was done |
| `specimen` | 0..1 | Reference | Specimen used for this observation |
| `device` | 0..1 | Reference | (Measurement) Device |
| `referenceRange` | 0..* | BackboneElement | Provides guide for interpretation |
| `hasMember` | 0..* | Reference | Related resource that belongs to the Observation group |
| `derivedFrom` | 0..* | Reference | Related measurements the observation is made from |
| `component` | 0..* | BackboneElement | Component results |

## Organization

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Identifies this organization  across multiple systems |
| `active` | 0..1 | boolean | Whether the organization's record is still in active use |
| `type` | 0..* | CodeableConcept | Kind of organization |
| `name` | 0..1 | string | Name used for the organization |
| `alias` | 0..* | string | A list of alternate names that the organization is known as, or was known as in the past |
| `telecom` | 0..* | ContactPoint | A contact detail for the organization |
| `address` | 0..* | Address | An address for the organization |
| `partOf` | 0..1 | Reference | The organization of which this organization forms a part |
| `contact` | 0..* | BackboneElement | Contact for the organization for a certain purpose |
| `endpoint` | 0..* | Reference | Technical endpoints providing access to services operated for the organization |

## Patient

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | An identifier for this patient |
| `active` | 0..1 | boolean | Whether this patient's record is in active use |
| `name` | 0..* | HumanName | A name associated with the patient |
| `telecom` | 0..* | ContactPoint | A contact detail for the individual |
| `gender` | 0..1 | code | male / female / other / unknown |
| `birthDate` | 0..1 | date | The date of birth for the individual |
| `deceased[x]` | 0..1 | boolean,dateTime | Indicates if the individual is deceased or not |
| `address` | 0..* | Address | An address for the individual |
| `maritalStatus` | 0..1 | CodeableConcept | Marital (civil) status of a patient |
| `multipleBirth[x]` | 0..1 | boolean,integer | Whether patient is part of a multiple birth |
| `photo` | 0..* | Attachment | Image of the patient |
| `contact` | 0..* | BackboneElement | A contact party (e.g. guardian, partner, friend) for the patient |
| `communication` | 0..* | BackboneElement | A language which may be used to communicate with the patient about his or her health |
| `generalPractitioner` | 0..* | Reference | Patient's nominated primary care provider |
| `managingOrganization` | 0..1 | Reference | Organization that is the custodian of the patient record |
| `link` | 0..* | BackboneElement | Link to another patient resource that concerns the same actual person |

## PaymentNotice

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for the payment noctice |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `request` | 0..1 | Reference | Request reference |
| `response` | 0..1 | Reference | Response reference |
| `created` | 1..1 | dateTime | Creation date |
| `provider` | 0..1 | Reference | Responsible practitioner |
| `payment` | 1..1 | Reference | Payment reference |
| `paymentDate` | 0..1 | date | Payment or clearing date |
| `payee` | 0..1 | Reference | Party being paid |
| `recipient` | 1..1 | Reference | Party being notified |
| `amount` | 1..1 | Money | Monetary amount of the payment |
| `paymentStatus` | 0..1 | CodeableConcept | Issued or cleared Status of the payment |

## PaymentReconciliation

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifier for a payment reconciliation |
| `status` | 1..1 | code | active / cancelled / draft / entered-in-error |
| `period` | 0..1 | Period | Period covered |
| `created` | 1..1 | dateTime | Creation date |
| `paymentIssuer` | 0..1 | Reference | Party generating payment |
| `request` | 0..1 | Reference | Reference to requesting resource |
| `requestor` | 0..1 | Reference | Responsible practitioner |
| `outcome` | 0..1 | code | queued / complete / error / partial |
| `disposition` | 0..1 | string | Disposition message |
| `paymentDate` | 1..1 | date | When payment issued |
| `paymentAmount` | 1..1 | Money | Total amount of Payment |
| `paymentIdentifier` | 0..1 | Identifier | Business identifier for the payment |
| `detail` | 0..* | BackboneElement | Settlement particulars |
| `formCode` | 0..1 | CodeableConcept | Printed form identifier |
| `processNote` | 0..* | BackboneElement | Note concerning processing |

## Practitioner

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | An identifier for the person as this agent |
| `active` | 0..1 | boolean | Whether this practitioner's record is in active use |
| `name` | 0..* | HumanName | The name(s) associated with the practitioner |
| `telecom` | 0..* | ContactPoint | A contact detail for the practitioner (that apply to all roles) |
| `address` | 0..* | Address | Address(es) of the practitioner that are not role specific (typically home address) |
| `gender` | 0..1 | code | male / female / other / unknown |
| `birthDate` | 0..1 | date | The date  on which the practitioner was born |
| `photo` | 0..* | Attachment | Image of the person |
| `qualification` | 0..* | BackboneElement | Certification, licenses, or training pertaining to the provision of care |
| `communication` | 0..* | CodeableConcept | A language the practitioner can use in patient communication |

## PractitionerRole

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Business Identifiers that are specific to a role/location |
| `active` | 0..1 | boolean | Whether this practitioner role record is in active use |
| `period` | 0..1 | Period | The period during which the practitioner is authorized to perform in these role(s) |
| `practitioner` | 0..1 | Reference | Practitioner that is able to provide the defined services for the organization |
| `organization` | 0..1 | Reference | Organization where the roles are available |
| `code` | 0..* | CodeableConcept | Roles which this practitioner may perform |
| `specialty` | 0..* | CodeableConcept | Specific specialty of the practitioner |
| `location` | 0..* | Reference | The location(s) at which this practitioner provides care |
| `healthcareService` | 0..* | Reference | The list of healthcare services that this worker provides for this role's Organization/Location(s) |
| `telecom` | 0..* | ContactPoint | Contact details that are specific to the role/location/service |
| `availableTime` | 0..* | BackboneElement | Times the Service Site is available |
| `notAvailable` | 0..* | BackboneElement | Not available during this time due to provided reason |
| `availabilityExceptions` | 0..1 | string | Description of availability exceptions |
| `endpoint` | 0..* | Reference | Technical endpoints providing access to services operated for the practitioner with this role |

## Procedure

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Identifiers for this procedure |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `basedOn` | 0..* | Reference | A request for this procedure |
| `partOf` | 0..* | Reference | Part of referenced event |
| `status` | 1..1 | code | preparation / in-progress / not-done / on-hold / stopped / completed / entered-in-error / unknown |
| `statusReason` | 0..1 | CodeableConcept | Reason for current status |
| `category` | 0..1 | CodeableConcept | Classification of the procedure |
| `code` | 0..1 | CodeableConcept | Identification of the procedure |
| `subject` | 1..1 | Reference | Who the procedure was performed on |
| `encounter` | 0..1 | Reference | Encounter created as part of |
| `performed[x]` | 0..1 | dateTime,Period,string,Age,Range | When the procedure was performed |
| `recorder` | 0..1 | Reference | Who recorded the procedure |
| `asserter` | 0..1 | Reference | Person who asserts this procedure |
| `performer` | 0..* | BackboneElement | The people who performed the procedure |
| `location` | 0..1 | Reference | Where the procedure happened |
| `reasonCode` | 0..* | CodeableConcept | Coded reason procedure performed |
| `reasonReference` | 0..* | Reference | The justification that the procedure was performed |
| `bodySite` | 0..* | CodeableConcept | Target body sites |
| `outcome` | 0..1 | CodeableConcept | The result of procedure |
| `report` | 0..* | Reference | Any report resulting from the procedure |
| `complication` | 0..* | CodeableConcept | Complication following the procedure |
| `complicationDetail` | 0..* | Reference | A condition that is a result of the procedure |
| `followUp` | 0..* | CodeableConcept | Instructions for follow up |
| `note` | 0..* | Annotation | Additional information about the procedure |
| `focalDevice` | 0..* | BackboneElement | Manipulated, implanted, or removed device |
| `usedReference` | 0..* | Reference | Items used during procedure |
| `usedCode` | 0..* | CodeableConcept | Coded items used during the procedure |

## QuestionnaireResponse

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..1 | Identifier | Unique id for this set of answers |
| `basedOn` | 0..* | Reference | Request fulfilled by this QuestionnaireResponse |
| `partOf` | 0..* | Reference | Part of this action |
| `questionnaire` | 0..1 | canonical | Form being answered |
| `status` | 1..1 | code | in-progress / completed / amended / entered-in-error / stopped |
| `subject` | 0..1 | Reference | The subject of the questions |
| `encounter` | 0..1 | Reference | Encounter created as part of |
| `authored` | 0..1 | dateTime | Date the answers were gathered |
| `author` | 0..1 | Reference | Person who received and recorded the answers |
| `source` | 0..1 | Reference | The person who answered the questions |
| `item` | 0..* | BackboneElement | Groups and questions |

## RelatedPerson

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | A human identifier for this person |
| `active` | 0..1 | boolean | Whether this related person's record is in active use |
| `patient` | 1..1 | Reference | The patient this person is related to |
| `relationship` | 0..* | CodeableConcept | The nature of the relationship |
| `name` | 0..* | HumanName | A name associated with the person |
| `telecom` | 0..* | ContactPoint | A contact detail for the person |
| `gender` | 0..1 | code | male / female / other / unknown |
| `birthDate` | 0..1 | date | The date on which the related person was born |
| `address` | 0..* | Address | Address where the related person can be contacted or visited |
| `photo` | 0..* | Attachment | Image of the person |
| `period` | 0..1 | Period | Period of time that this relationship is considered valid |
| `communication` | 0..* | BackboneElement | A language which may be used to communicate with about the patient's health |

## RiskAssessment

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Unique identifier for the assessment |
| `basedOn` | 0..1 | Reference | Request fulfilled by this assessment |
| `parent` | 0..1 | Reference | Part of this occurrence |
| `status` | 1..1 | code | registered / preliminary / final / amended + |
| `method` | 0..1 | CodeableConcept | Evaluation mechanism |
| `code` | 0..1 | CodeableConcept | Type of assessment |
| `subject` | 1..1 | Reference | Who/what does assessment apply to? |
| `encounter` | 0..1 | Reference | Where was assessment performed? |
| `occurrence[x]` | 0..1 | dateTime,Period | When was assessment made? |
| `condition` | 0..1 | Reference | Condition assessed |
| `performer` | 0..1 | Reference | Who did assessment? |
| `reasonCode` | 0..* | CodeableConcept | Why the assessment was necessary? |
| `reasonReference` | 0..* | Reference | Why the assessment was necessary? |
| `basis` | 0..* | Reference | Information used in assessment |
| `prediction` | 0..* | BackboneElement | Outcome predicted |
| `mitigation` | 0..1 | string | How to reduce risk |
| `note` | 0..* | Annotation | Comments on the risk assessment |

## ServiceRequest

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Identifiers assigned to this order |
| `instantiatesCanonical` | 0..* | canonical | Instantiates FHIR protocol or definition |
| `instantiatesUri` | 0..* | uri | Instantiates external protocol or definition |
| `basedOn` | 0..* | Reference | What request fulfills |
| `replaces` | 0..* | Reference | What request replaces |
| `requisition` | 0..1 | Identifier | Composite Request ID |
| `status` | 1..1 | code | draft / active / on-hold / revoked / completed / entered-in-error / unknown |
| `intent` | 1..1 | code | proposal / plan / directive / order / original-order / reflex-order / filler-order / instance-order / option |
| `category` | 0..* | CodeableConcept | Classification of service |
| `priority` | 0..1 | code | routine / urgent / asap / stat |
| `doNotPerform` | 0..1 | boolean | True if service/procedure should not be performed |
| `code` | 0..1 | CodeableConcept | What is being requested/ordered |
| `orderDetail` | 0..* | CodeableConcept | Additional order information |
| `quantity[x]` | 0..1 | Quantity,Ratio,Range | Service amount |
| `subject` | 1..1 | Reference | Individual or Entity the service is ordered for |
| `encounter` | 0..1 | Reference | Encounter in which the request was created |
| `occurrence[x]` | 0..1 | dateTime,Period,Timing | When service should occur |
| `asNeeded[x]` | 0..1 | boolean,CodeableConcept | Preconditions for service |
| `authoredOn` | 0..1 | dateTime | Date request signed |
| `requester` | 0..1 | Reference | Who/what is requesting service |
| `performerType` | 0..1 | CodeableConcept | Performer role |
| `performer` | 0..* | Reference | Requested performer |
| `locationCode` | 0..* | CodeableConcept | Requested location |
| `locationReference` | 0..* | Reference | Requested location |
| `reasonCode` | 0..* | CodeableConcept | Explanation/Justification for procedure or service |
| `reasonReference` | 0..* | Reference | Explanation/Justification for service or service |
| `insurance` | 0..* | Reference | Associated insurance coverage |
| `supportingInfo` | 0..* | Reference | Additional clinical information |
| `specimen` | 0..* | Reference | Procedure Samples |
| `bodySite` | 0..* | CodeableConcept | Location on Body |
| `note` | 0..* | Annotation | Comments |
| `patientInstruction` | 0..1 | string | Patient or consumer-oriented instructions |
| `relevantHistory` | 0..* | Reference | Request provenance |

## Specimen

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | External Identifier |
| `accessionIdentifier` | 0..1 | Identifier | Identifier assigned by the lab |
| `status` | 0..1 | code | available / unavailable / unsatisfactory / entered-in-error |
| `type` | 0..1 | CodeableConcept | Kind of material that forms the specimen |
| `subject` | 0..1 | Reference | Where the specimen came from. This may be from patient(s), from a location (e.g., the source of an environmental sample), or a sampling of a substance or a device |
| `receivedTime` | 0..1 | dateTime | The time when specimen was received for processing |
| `parent` | 0..* | Reference | Specimen from which this specimen originated |
| `request` | 0..* | Reference | Why the specimen was collected |
| `collection` | 0..1 | BackboneElement | Collection details |
| `processing` | 0..* | BackboneElement | Processing and processing step details |
| `container` | 0..* | BackboneElement | Direct container of specimen (tube/slide, etc.) |
| `condition` | 0..* | CodeableConcept | State of the specimen |
| `note` | 0..* | Annotation | Comments |

## Substance

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Unique identifier |
| `status` | 0..1 | code | active / inactive / entered-in-error |
| `category` | 0..* | CodeableConcept | What class/type of substance this is |
| `code` | 1..1 | CodeableConcept | What substance this is |
| `description` | 0..1 | string | Textual description of the substance, comments |
| `instance` | 0..* | BackboneElement | If this describes a specific package/container of the substance |
| `ingredient` | 0..* | BackboneElement | Composition information about the substance |

## Task

| Field | Cardinality | Type | Short |
|---|---|---|---|
| `id` | 0..1 | http://hl7.org/fhirpath/System.String | Logical id of this artifact |
| `meta` | 0..1 | Meta | Metadata about the resource |
| `implicitRules` | 0..1 | uri | A set of rules under which this content was created |
| `language` | 0..1 | code | Language of the resource content |
| `text` | 0..1 | Narrative | Text summary of the resource, for human interpretation |
| `contained` | 0..* | Resource | Contained, inline Resources |
| `extension` | 0..* | Extension | Additional content defined by implementations |
| `modifierExtension` | 0..* | Extension | Extensions that cannot be ignored |
| `identifier` | 0..* | Identifier | Task Instance Identifier |
| `instantiatesCanonical` | 0..1 | canonical | Formal definition of task |
| `instantiatesUri` | 0..1 | uri | Formal definition of task |
| `basedOn` | 0..* | Reference | Request fulfilled by this task |
| `groupIdentifier` | 0..1 | Identifier | Requisition or grouper id |
| `partOf` | 0..* | Reference | Composite task |
| `status` | 1..1 | code | draft / requested / received / accepted / + |
| `statusReason` | 0..1 | CodeableConcept | Reason for current status |
| `businessStatus` | 0..1 | CodeableConcept | E.g. "Specimen collected", "IV prepped" |
| `intent` | 1..1 | code | unknown / proposal / plan / order / original-order / reflex-order / filler-order / instance-order / option |
| `priority` | 0..1 | code | routine / urgent / asap / stat |
| `code` | 0..1 | CodeableConcept | Task Type |
| `description` | 0..1 | string | Human-readable explanation of task |
| `focus` | 0..1 | Reference | What task is acting on |
| `for` | 0..1 | Reference | Beneficiary of the Task |
| `encounter` | 0..1 | Reference | Healthcare event during which this task originated |
| `executionPeriod` | 0..1 | Period | Start and end time of execution |
| `authoredOn` | 0..1 | dateTime | Task Creation Date |
| `lastModified` | 0..1 | dateTime | Task Last Modified Date |
| `requester` | 0..1 | Reference | Who is asking for task to be done |
| `performerType` | 0..* | CodeableConcept | Requested performer |
| `owner` | 0..1 | Reference | Responsible individual |
| `location` | 0..1 | Reference | Where task occurs |
| `reasonCode` | 0..1 | CodeableConcept | Why task is needed |
| `reasonReference` | 0..1 | Reference | Why task is needed |
| `insurance` | 0..* | Reference | Associated insurance coverage |
| `note` | 0..* | Annotation | Comments made about the task |
| `relevantHistory` | 0..* | Reference | Key events in history of the Task |
| `restriction` | 0..1 | BackboneElement | Constraints on fulfillment tasks |
| `input` | 0..* | BackboneElement | Information used to perform task |
| `output` | 0..* | BackboneElement | Information produced as part of task |


<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

use Satusehat\Integration\FHIR\Medication;

use Satusehat\Integration\Terminology\Icd10;
use Satusehat\Integration\Terminology\MedicationTerminology;
use Satusehat\Integration\Terminology\Occupation;

class MedicationRequest extends OAuth2Client
{
    # Declare required terminology
    public $medication_form;
    public $drug_form;
    public $medicationrequest_status_reason;
    public $medicationrequest_category;
    public $course_of_therapy_type;
    public $time_interval;
    public $substitution_reason;
    public $route;
    public $timing;

    # Declare general terminology
    public $performer_role;

    public function __construct()
    {
        parent::__construct();

        $medication_terminology = new MedicationTerminology();
        $occupation = new Occupation();

        $this->medication_form = $medication_terminology->medication_form;
        $this->drug_form = $medication_terminology->drug_form;
        $this->medicationrequest_status_reason = $medication_terminology->medicationrequest_status_reason;
        $this->medicationrequest_category = $medication_terminology->medicationrequest_category;
        $this->course_of_therapy_type = $medication_terminology->course_of_therapy_type;
        $this->time_interval = $medication_terminology->time_interval;
        $this->substitution_reason = $medication_terminology->substitution_reason;
        $this->route = $medication_terminology->route;
        $this->timing = $medication_terminology->timing;

        $this->performer_role = $occupation->performer_role;
    }

    public array $medication_request = [
        'resourceType' => 'MedicationRequest',
    ];


    public function addPrescriptionIdentifier($prescription_id)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/prescription/'.$this->organization_id;
        $identifier['value'] = $prescription_id;
        $identifier['use'] = 'official';

        $this->medication_request['identifier'][] = $identifier;
    }

    public function addPrescriptionItemIdentifier($prescription_item_id)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/prescription-item/'.$this->organization_id;
        $identifier['value'] = $prescription_item_id;
        $identifier['use'] = 'official';

        $this->medication_request['identifier'][] = $identifier;
    }

    public function setStatus($status = 'completed')
    {
        # Assert if the status is active | on-hold | cancelled | completed | entered-in-error | stopped | draft | unknown
        $status = strtolower($status);
        if (!in_array($status, ['active', 'on-hold', 'cancelled', 'completed', 'entered-in-error', 'stopped', 'draft', 'unknown'])) {
            throw new FHIRException('Invalid status value');
        }
        $this->medication_request['status'] = $status;
    }

    public function setStatusReason($status_code = null)
    {
        $medicationrequest_status_reason = [
            'system' => 'http://sys-ids.kemkes.go.id/status-reason',
            'code' => $status_code,
            'display' => $this->medicationrequest_status_reason[$status_code],
        ];

        $this->medication_request['statusReason']['coding'][] = $medicationrequest_status_reason;
    }

    public function setIntent($intent = 'order')
    {
        $this->medication_request['intent'] = $intent;
    }

    public function addCategory($category = 'outpatient')
    {
        $this->medication_request['category']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
            'code' => $category,
            'display' => $this->medicationrequest_category[$category],
        ];
    }

    public function setPriority($priority = 'routine')
    {
        $this->medication_request['priority'] = $priority;
    }

    public function setDoNotPerform($do_not_perform = false)
    {
        $this->medication_request['doNotPerform'] = $do_not_perform;
    }

    public function addReportedReference($reference)
    {
        $this->medication_request['reportedReference']['reference'] = $reference;
    }

    public function setMedicationReference($reference)
    {
        $this->medication_request['medicationReference']['reference'] = $reference;
        $this->medication_request['medicationReference']['display'] = $reference;
    }

    public function setSubject($subjectId, $name)
    {
        $this->medication_request['subject']['reference'] = 'Patient/'.$subjectId;
        $this->medication_request['subject']['display'] = $name;
    }

    public function setEncounter($encounterId, $name)
    {
        $this->medication_request['encounter']['reference'] = ($bundle ? 'urn:uuid:' : 'Encounter/').$encounterId;
        $this->medication_request['encounter']['display'] = $display ? $display : 'Kunjungan '.$encounterId;
    }

    public function addSupportingInformation($reference)
    {
        $this->medication_request['supportingInformation']['reference'] = $reference;
    }

    public function setAuthoredOn($authored_on = null)
    {
        $this->medication_request['authoredOn'] = $authored_on ?
            date('Y-m-d\TH:i:sP', strtotime($authored_on)) :
            date('Y-m-d\TH:i:sP');
    }

    public function setRequester($requesterId, $name)
    {
        $this->medication_request['requester']['reference'] = 'Practitioner/'.$requester;
        $this->medication_request['requester']['display'] = $name;
    }

    public function setPerformer($performerId, $name)
    {
        $this->medication_request['performer']['reference'] = 'Practitioner/'.$performer;
        $this->medication_request['performer']['display'] = $name;
    }

    public function setPerformerType($performer_type)
    {
        $this->medication_request['performerType']['coding'][] = [
            'system' => 'http://snomed.info/sct',
            'code' => $performer_type,
            'display' => $this->performer_role[$performer_type],
        ];
    }

    public function setRecorder($recorderId, $name)
    {
        $this->medication_request['recorder']['reference'] = 'Practitioner/'.$recorder;
        $this->medication_request['recorder']['display'] = $name;
    }

    public function addReasonCode($reason_code)
    {
        // Only accept ICD10 code
        $code_check = Icd10::where('icd10_code', $code)->first();

        // Handling if incomplete code / display
        if (! $code_check) {
            throw new FHIRException('Kode ICD10 tidak ditemukan');
        }

        $display = $display ? $display : $code_check->icd10_en;

        $this->medication_request['reasonCode']['coding'][] = [
            'system' => 'http://hl7.org/fhir/sid/icd-10',
            'code' => strtoupper($reason_code),
            'display' => $display,
        ];
    }

    public function addReasonReference($reference)
    {
        $this->medication_request['reasonReference']['reference'] = 'Condition/'.$reference;
    }

    public function addBasedOn($reference)
    {
        $this->medication_request['basedOn']['reference'] = $reference;
    }

    public function setCourseOfTherapyType($course)
    {
        $this->medication_request['courseOfTherapyType']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy',
            'code' => $course,
            'display' => $this->course_of_therapy_type[$course],
        ];
    }

    public function addInsurance($insurance)
    {
        $this->medication_request['insurance']['reference'] = $insurance;
    }

    public function addNote($note)
    {
        $this->medication_request['note'][] = [
            'text' => $note,
        ];
    }

    public function addDosageInstruction($sequence = 1, $route_code, $timing_code, $patientInstruction, $as_needed = false, $dose_value = null, $dose_unit = null)
    {
        $dosage_instruction['sequence'] = $sequence;
        $dosage_instruction['patientInstruction'] = $patientInstruction;
        $dosage_instruction['asNeededBoolean'] = $as_needed;
        $dosage_instruction['timing']['code'] = $timing_code;

        $doseAndRate_type_coding['system'] = 'http://terminology.hl7.org/CodeSystem/dose-rate-type';
        $doseAndRate_type_coding['code'] = 'ordered';
        $doseAndRate_type_coding['display'] = 'Ordered';

        $doseAndRate_type['coding'][] = $doseAndRate_type_coding;
        $doseAndRate_singular['type'] = $doseAndRate_type;

        // If dose_value or dose_unit is not declared, skip
        if (isset($dose_value) && isset($dose_unit)) {
            $doseAndRate_quantity['value'] = $dose_value;
            $doseAndRate_quantity['code'] = $dose_unit;
            $doseAndRate_quantity['system'] = $this->drug_form[$dose_unit]['system'];
            $doseAndRate_quantity['unit'] = $this->drug_form[$dose_unit]['display'];

            $doseAndRate_singular['doseQuantity'] = $doseAndRate_quantity;
        }

        $dosage_instruction['doseAndRate'][] = $doseAndRate_singular;

        $route_coding['system'] = 'http://snomed.info/sct';
        $route_coding['code'] = $route_code;
        $route_coding['display'] = $this->route[$route_code];

        $dosage_instruction['route']['coding'] = $route_coding;

        $timing_coding['system'] = 'http://terminology.hl7.org/CodeSystem/v3-GTSAbbreviation';
        $timing_coding['code'] = $timing_code;
        $timing_coding['display'] = $this->timing[$timing_code];

        $dosage_instruction['timing']['coding'][] = $timing_coding;

        $this->medication_request['dosageInstruction'][] = $dosage_instruction;
    }

    public function setDispenseRequest($quantity, $unit, $iter = 0, $validityPeriodStart, $validityPeriodEnd, $expectedSupplyDurationNum, $expectedSupplyDurationUnit)
    {
        $dispense_request['numberOfRepeatsAllowed'] = $iter;

        $dispense_request['quantity']['value'] = $quantity;
        $dispense_request['quantity']['code'] = $unit;
        $dispense_request['quantity']['system'] = $this->drug_form[$unit]['system'];
        $dispense_request['quantity']['unit'] = $this->drug_form[$unit]['display'];

        $dispense_request['validityPeriod']['start'] = date('Y-m-d\TH:i:sP', strtotime($validityPeriodStart));
        $dispense_request['validityPeriod']['end'] = date('Y-m-d\TH:i:sP', strtotime($validityPeriodEnd));

        # If expectedSupplyDurationUnit or expectedSupplyDurationNum is not declared skip
        if (isset($expectedSupplyDurationUnit) && isset($expectedSupplyDurationNum)) {
            $dispense_request['expectedSupplyDuration']['value'] = $expectedSupplyDurationNum;
            $dispense_request['expectedSupplyDuration']['code'] = $expectedSupplyDurationUnit;
            $dispense_request['expectedSupplyDuration']['system'] = 'http://unitsofmeasure.org';
            $dispense_request['expectedSupplyDuration']['unit'] = $this->time_interval[$expectedSupplyDurationUnit];
        }

        $dispense_request['performer']['reference'] = 'Organization/'.$this->organization_id;

        $this->medication_request['dispenseRequest'] = $dispense_request;
    }

    public function setSubstitution($allowed = true, $reason = 'G')
    {
        $substitution['allowedBoolean'] = $allowed;

        if ($reason) {
            $substitution['reason']['coding'][] = [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-substanceAdminSubstitution',
                'code' => 'G',
                'display' => $this->substitution_reason[$reason],
            ];
        }

        $this->medication_request['substitution'] = $substitution;
    }

    public function addContained(Medication $medication)
    {
        $this->medication_request['contained'][] = $medication;
    }

    public function json()
    {
        # If status not declared, automatically call setStatus() with 'active' as the default value
        if (!isset($this->medication_request['status'])) {
            $this->setStatus();
        }

        # If intent not declared, automatically call setIntent() with 'order' as the default value
        if (!isset($this->medication_request['intent'])) {
            $this->setIntent();
        }

        # If subject not declared, throw FHIRException
        if (!isset($this->medication_request['subject'])) {
            throw new FHIRException('Subject is required');
        }

        return json_encode($this->medication_request, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('MedicationRequest', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->medication['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('MedicationRequest', $id, $payload);

        return [$statusCode, $res];
    }
}

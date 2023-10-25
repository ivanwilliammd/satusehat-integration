<?php

namespace Satusehat\Integration\FHIR;

use SatuSehat\Integration\OAuth2Client;
use Satusehat\Integration\FHIR\Exception\FHIRMissingProperty;

enum ObservationCode: string
{
    case Sistole = "8480-6";
    case Diastole = "8462-4";
    case HeartRate = "8867-4";
    case Temperature = "8310-5";
    case RespiratoryRate = "9279-1";
}

enum ObservationCategory: string
{
    case VitalSigns = "vital-signs";
}

class Observation extends OAuth2Client
{
    private $observation = ['resourceType' => 'Observation'];

    /**
     * Sets a status to the observation.
     *
     * @param string $status The status to add. Defaults to "final".
     *
     * @return Observation Returns the current instance of the Observation class.
     */
    public function setStatus($status = "final"): Observation
    {
        switch ($status) {
            case 'registered':
                $code = 'registered';
                break;
            case 'preliminary':
                $code = 'preliminary';
                break;
            case 'final':
                $code = 'final';
                break;
            case 'amended':
                $code = 'amended';
                break;
            case 'corrected':
                $code = 'corrected';
                break;
            case 'cancelled':
                $code = 'cancelled';
                break;
            case 'entered-in-error':
                $code = 'entered-in-error';
                break;
            case 'unknown':
                $code = 'unknown';
                break;
            default:
                $code = 'final';
        }

        $this->observation['status'] = $code;

        return $this;
    }

    /**
     * Adds a category to the observation.
     *
     * @param string $code The code of the category.
     * @return Observation The updated observation object.
     */
    public function addCategory(ObservationCategory $category): Observation
    {
        match ($category) {
            ObservationCategory::VitalSigns => $display = "Vital Signs"
        };

        // NOTE: we currently only support 'vital-signs'
        $this->observation['category'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code' => $category->value,
                    'display' => $display
                ]
            ]
        ];

        return $this;
    }

    /**
     * Adds an observation code to the observation.
     * If more than one code is added, the last one will be used.
     *
     * @param ObservationCode $code The valid observation code to add.
     * @return Observation Returns the updated observation object.
     */
    public function addCode(ObservationCode $code): Observation
    {
        match ($code) {
            ObservationCode::Sistole => $code = [
                'system' => 'http://loinc.org',
                'code' => ObservationCode::Sistole->value,
                'display' => 'Systolic blood pressure',
            ],
            ObservationCode::Diastole => $code = [
                'system' => 'http://loinc.org',
                'code' => ObservationCode::Diastole->value,
                'display' => 'Diastolic blood pressure',
            ],
            ObservationCode::HeartRate => $code = [
                'system' => 'http://loinc.org',
                'code' => ObservationCode::HeartRate->value,
                'display' => 'Heart rate',
            ],
            ObservationCode::Temperature => $code = [
                'system' => 'http://loinc.org',
                'code' => ObservationCode::Temperature->value,
                'display' => 'Body temperature',
            ],
            ObservationCode::RespiratoryRate => $code = [
                'system' => 'http://loinc.org',
                'code' => ObservationCode::RespiratoryRate->value,
                'display' => 'Respiratory rate',
            ],
        };

        $this->observation['code'] = [
            'coding' => [$code]
        ];

        return $this;
    }

    /**
     * Sets the subject of the observation.
     *
     * @param string $subjectId The Satu Sehat ID of the subject.
     * @param string $name The name of the subject.
     *
     * @return Observation The current observation instance.
     */
    public function setSubject(string $subjectId, string $name): Observation
    {
        $this->observation['subject'] = [
            'reference' => "Patient/{$subjectId}",
            'display' => $name,
        ];

        return $this;
    }

    /**
     * Visit data where observation results are obtained
     *
     * @param string $encounterId The Satu Sehat Encounter ID of the encounter.
     * @param string $display The display name of the encounter.
     */
    public function setEncounter(string $encounterId, string $display = null): Observation
    {
        $this->observation["encounter"] = [
            "reference" => "Encounter/{$encounterId}",
            "display" => !empty($display) ? $display : "Kunjungan {$encounterId}"
        ];

        return $this;
    }

    /**
     * Returns the JSON representation of the observation.
     *
     * @return string The JSON representation of the observation.
     */
    public function json(): string
    {
        if (!array_key_exists('status', $this->observation)) {
            throw new FHIRMissingProperty('Status is required.');
        }

        if (!array_key_exists('category', $this->observation)) {
            throw new FHIRMissingProperty('Category is required.');
        }

        if (!array_key_exists('code', $this->observation)) {
            throw new FHIRMissingProperty('Code is required.');
        }

        if (!array_key_exists('subject', $this->observation)) {
            throw new FHIRMissingProperty('Subject is required.');
        }

        if (!array_key_exists('encounter', $this->observation)) {
            throw new FHIRMissingProperty('Encounter is required.');
        }

        return json_encode($this->observation);
    }
}

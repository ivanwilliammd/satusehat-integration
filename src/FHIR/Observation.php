<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\FHIR\Enum\ObservationCategory;
use Satusehat\Integration\FHIR\Enum\ObservationCode;
use Satusehat\Integration\FHIR\Exception\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Observation extends OAuth2Client
{
    private array $observation = ['resourceType' => 'Observation'];

    /**
     * Sets a status to the observation.
     *
     * @param  string  $status The status to add. Defaults to "final".
     * @return Observation Returns the current instance of the Observation class.
     */
    public function setStatus($status = 'final'): Observation
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
     * @param  string  $category the code of the category
     * @return Observation The updated observation object.
     */
    public function addCategory(string $category): Observation
    {
        $display = '';
        $code = '';
        switch ($category) {
            case 'vital-signs':
                $display = 'Vital Signs';
                $code = 'vital-signs';
                break;
        }

        // NOTE: we currently only support 'vital-signs'
        $this->observation['category'][] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];

        return $this;
    }

    /**
     * Adds an observation code to the observation.
     * If more than one code is added, the last one will be used.
     *
     * @param  string  $code The valid observation code to add.
     * @return Observation Returns the updated observation object.
     */
    public function addCode(string $code): Observation
    {
        $code = [
            'system' => 'http://loinc.org',
            'code' => '',
            'display' => ''
        ];

        $display = '';
        $code = '';
        switch ($code) {
            case '8480-6':
                $display = 'Systolic blood pressure';
                $code = '8480-6';
                break;
            case '8462-4':
                $display = 'Diastolic blood pressure';
                $code = '8462-4';
                break;
            case '8867-4':
                $display = 'Heart rate';
                $code = '8867-4';
                break;
            case '8310-5':
                $display = 'Body temperature';
                $code = '8310-5';
                break;
            case '9279-1':
                $display = 'Respiratory rate';
                $code = '9279-1';
                break;
        }

        $this->observation['code'] = [
            'coding' => [
                [
                    'system' => 'http://loinc.org',
                    'code' => $code,
                    'display' => $display,
                ],

            ],
        ];

        return $this;
    }

    /**
     * Sets the subject of the observation.
     *
     * @param  string  $subjectId The Satu Sehat ID of the subject.
     * @param  string  $name The name of the subject.
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
     * Sets the performer of the observation.
     *
     * @param  string  $performerId The Satu Sehat ID of the performer.
     * @param  string  $name The name of the performer.
     * @return Observation The current observation instance.
     */
    public function setPerformer(string $performerId, string $name)
    {
        $this->observation['performer'][] = [
            'reference' => "Practitioner/{$performerId}",
            'display' => $name,
        ];

        return $this;
    }

    /**
     * Visit data where observation results are obtained
     *
     * @param  string  $encounterId The Satu Sehat Encounter ID of the encounter.
     * @param  string  $display The display name of the encounter.
     */
    public function setEncounter(string $encounterId, string $display = null): Observation
    {
        $this->observation['encounter'] = [
            'reference' => "Encounter/{$encounterId}",
            'display' => !empty($display) ? $display : "Kunjungan {$encounterId}",
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

        return json_encode($this->observation, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Observation', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Observation', $id, $payload);

        return [$statusCode, $res];
    }
}

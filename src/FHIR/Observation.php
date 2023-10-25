<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\FHIR\Exception\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class Observation extends OAuth2Client
{
    private $observation = ['resourceType' => 'Observation'];

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
     * @param  string  $code The code of the category.
     * @param  string  $display The display name of the category.
     * @return Observation The updated observation object.
     */
    public function addCategory($code = 'vital-signs', $display = 'Vital Signs'): Observation
    {
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
     * Returns the JSON representation of the observation.
     *
     * @return string The JSON representation of the observation.
     */
    public function json(): string
    {
        if (! array_key_exists('category', $this->observation)) {
            throw new FHIRMissingProperty('Category is required.');
        }

        return json_encode($this->observation);
    }
}

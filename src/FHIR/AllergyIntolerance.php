<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Builder\PayloadBuilderAllergyIntolerance;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

/**
 * AllergyIntolerance FHIR R4 Resource
 * @link https://www.hl7.org/fhir/allergyintolerance.html
 *
 * Uses PayloadBuilderAllergyIntolerance for clean typed building.
 * Backward compatible: still extends OAuth2Client for old SSRequest pattern.
 */
class AllergyIntolerance extends OAuth2Client
{
    public array $allergyIntolerance = ['resourceType' => 'AllergyIntolerance'];

    public function addIdentifier($system, $value)
    {
        $this->allergyIntolerance['identifier'][] = [
            'system' => $system,
            'value' => $value,
        ];
        return $this;
    }

    public function setClinicalStatus($status)
    {
        $this->allergyIntolerance['clinicalStatus'] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
                    'code' => $status,
                ],
            ],
        ];
        return $this;
    }

    public function setVerificationStatus($status)
    {
        $this->allergyIntolerance['verificationStatus'] = [
            'coding' => [
                [
                    'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
                    'code' => $status,
                ],
            ],
        ];
        return $this;
    }

    public function setType($type)
    {
        $this->allergyIntolerance['type'] = $type;
        return $this;
    }

    public function addCategory($category)
    {
        $this->allergyIntolerance['category'][] = $category;
        return $this;
    }

    public function setCriticality($criticality)
    {
        $this->allergyIntolerance['criticality'] = $criticality;
        return $this;
    }

    public function setCode($code, $display, $text = null)
    {
        $this->allergyIntolerance['code'] = [
            'coding' => [
                [
                    'system' => 'http://snomed.info/sct',
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];

        if ($text !== null) {
            $this->allergyIntolerance['code']['text'] = $text;
        }
        return $this;
    }

    public function setPatient($reference, $display = null)
    {
        $this->allergyIntolerance['patient'] = ['reference' => $reference];
        if ($display !== null) {
            $this->allergyIntolerance['patient']['display'] = $display;
        }
        return $this;
    }

    public function setEncounter($reference, $display = null)
    {
        $this->allergyIntolerance['encounter'] = ['reference' => $reference];
        if ($display !== null) {
            $this->allergyIntolerance['encounter']['display'] = $display;
        }
        return $this;
    }

    public function setOnsetDateTime($dateTime)
    {
        $this->allergyIntolerance['onsetDateTime'] = $dateTime;
        return $this;
    }

    public function setRecordedDate($dateTime)
    {
        $this->allergyIntolerance['recordedDate'] = $dateTime;
        return $this;
    }

    public function setRecorder($reference)
    {
        $this->allergyIntolerance['recorder'] = ['reference' => $reference];
        return $this;
    }

    public function setAsserter($reference)
    {
        $this->allergyIntolerance['asserter'] = ['reference' => $reference];
        return $this;
    }

    public function setLastOccurrence($dateTime)
    {
        $this->allergyIntolerance['lastOccurrence'] = $dateTime;
        return $this;
    }

    public function addNote($text)
    {
        $this->allergyIntolerance['note'][] = ['text' => $text];
        return $this;
    }

    public function addReaction($substance, $manifestation, $description = null, $onset = null, $severity = null, $exposureRoute = null, $note = null)
    {
        $reaction = [
            'substance' => [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $substance['code'],
                        'display' => $substance['display'],
                    ],
                ],
            ],
            'manifestation' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => $manifestation['code'],
                            'display' => $manifestation['display'],
                        ],
                    ],
                ],
            ],
        ];

        if ($description !== null) {
            $reaction['description'] = $description;
        }
        if ($onset !== null) {
            $reaction['onset'] = $onset;
        }
        if ($severity !== null) {
            $reaction['severity'] = $severity;
        }
        if ($exposureRoute !== null) {
            $reaction['exposureRoute'] = [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => $exposureRoute['code'],
                        'display' => $exposureRoute['display'],
                    ],
                ],
            ];
        }
        if ($note !== null) {
            $reaction['note'] = [['text' => $note]];
        }

        $this->allergyIntolerance['reaction'][] = $reaction;
        return $this;
    }

    /**
     * Build using PayloadBuilderAllergyIntolerance (Phase 3 pattern).
     * Returns the builder instance for chaining.
     */
    public static function build(): PayloadBuilderAllergyIntolerance
    {
        return new PayloadBuilderAllergyIntolerance();
    }

    public function json()
    {
        if (! array_key_exists('category', $this->allergyIntolerance)) {
            throw new FHIRMissingProperty('AllergyIntolerance.category is required');
        }

        if (! array_key_exists('code', $this->allergyIntolerance)) {
            throw new FHIRMissingProperty('AllergyIntolerance.code is required');
        }

        if (! array_key_exists('patient', $this->allergyIntolerance)) {
            throw new FHIRMissingProperty('AllergyIntolerance.patient is required');
        }

        return json_encode($this->allergyIntolerance, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        [$statusCode, $res] = $this->ss_post('AllergyIntolerance', $this->json());
        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->allergyIntolerance['id'] = $id;
        [$statusCode, $res] = $this->ss_put('AllergyIntolerance', $id, $this->json());
        return [$statusCode, $res];
    }
}

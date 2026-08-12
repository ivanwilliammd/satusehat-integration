<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\Terminology\CompositionTerminology;

class Composition extends OAuth2Client
{
    public function __construct()
    {
        parent::__construct();

        $composition_terminology = new CompositionTerminology;

        $this->type = $composition_terminology->type;
        $this->category = $composition_terminology->category;
        $this->section = $composition_terminology->section;
    }

    public array $composition = [
        'resourceType' => 'Composition',
    ];

    public $type;

    public $category;

    public $section;

    public function setStatus($status = 'final')
    {
        // Assert if the status is preliminary | final | amended | entered-in-error
        $status = strtolower($status);
        if (! in_array($status, ['preliminary', 'final', 'amended', 'entered-in-error'])) {
            throw new FHIRInvalidPropertyValue('Invalid status value');
        }
        $this->composition['status'] = $status;
    }

    public function setType($code)
    {
        if (! isset($this->type[$code])) {
            throw new FHIRInvalidPropertyValue('Invalid type code');
        }
        $this->composition['type'] = [
            'coding' => [
                [
                    'system' => $this->type[$code]['system'],
                    'code' => $code,
                    'display' => $this->type[$code]['display'],
                ],
            ],
        ];
    }

    public function addCategory($code)
    {
        if (! isset($this->category[$code])) {
            throw new FHIRInvalidPropertyValue('Invalid category code');
        }
        $this->composition['category'][] = [
            'coding' => [
                [
                    'system' => $this->category[$code]['system'],
                    'code' => $code,
                    'display' => $this->category[$code]['display'],
                ],
            ],
        ];
    }

    public function setSubject($subjectId, $name = null)
    {
        $this->composition['subject']['reference'] = 'Patient/'.$subjectId;
        if ($name) {
            $this->composition['subject']['display'] = $name;
        }
    }

    public function setEncounter($encounterId, $display = null)
    {
        $this->composition['encounter']['reference'] = 'Encounter/'.$encounterId;

        if ($display) {
            $this->composition['encounter']['display'] = $display;
        }
    }

    public function setDate($date = null)
    {
        $this->composition['date'] = $date ?
            date('Y-m-d\TH:i:sP', strtotime($date)) :
            date('Y-m-d\TH:i:sP');
    }

    public function addAuthor($authorId, $name = null)
    {
        $author = ['reference' => 'Practitioner/'.$authorId];
        if ($name) {
            $author['display'] = $name;
        }
        $this->composition['author'][] = $author;
    }

    public function setTitle($title)
    {
        $this->composition['title'] = $title;
    }

    public function addAttester($mode, $partyId, $partyName = null)
    {
        $attester = [
            'mode' => $mode,
            'party' => [
                'reference' => 'Organization/'.$partyId,
            ],
        ];
        if ($partyName) {
            $attester['party']['display'] = $partyName;
        }
        $this->composition['attester'][] = $attester;
    }

    public function setCustodian($organizationId)
    {
        $this->composition['custodian']['reference'] = 'Organization/'.$organizationId;
    }

    public function addSection($title, $code, $text = null, $status = 'additional')
    {
        $section = [
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://loinc.org',
                        'code' => $code,
                        'display' => $this->section[$code]['display'],
                    ],
                ],
            ],
        ];

        if ($title) {
            $section['title'] = $title;
        }

        if ($text) {
            $section['text'] = [
                'div' => $text,
                'status' => $status,
            ];
        }

        $this->composition['section'][] = $section;
    }

    public function setIdentifier($system, $value)
    {
        $this->composition['identifier'] = [
            'system' => $system,
            'value' => $value,
        ];
    }

    public function json()
    {
        // If status not declared, automatically call setStatus() with 'final' as the default value
        if (! isset($this->composition['status'])) {
            $this->setStatus();
        }

        // If type not declared, automatically call setType() with '18842-5' as the default value
        if (! isset($this->composition['type'])) {
            $this->setType('18842-5');
        }

        // If date not declared, automatically call setDate() with the current date
        if (! isset($this->composition['date'])) {
            $this->setDate();
        }

        // If subject not declared, throw FHIRMissingProperty
        if (! isset($this->composition['subject'])) {
            throw new FHIRMissingProperty('Subject is required');
        }

        // If author not declared, throw FHIRMissingProperty
        if (! isset($this->composition['author'])) {
            throw new FHIRMissingProperty('Author is required');
        }

        // If title not declared, throw FHIRMissingProperty
        if (! isset($this->composition['title'])) {
            throw new FHIRMissingProperty('Title is required');
        }

        return json_encode($this->composition, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Composition', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->composition['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Composition', $id, $payload);

        return [$statusCode, $res];
    }
}

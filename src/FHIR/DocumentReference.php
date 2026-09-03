<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class DocumentReference extends OAuth2Client
{
    public array $documentReference = ['resourceType' => 'DocumentReference'];

    public function addIdentifier($system, $value, $use = null)
    {
        $identifier = [
            'system' => $system,
            'value' => $value,
        ];

        if ($use !== null) {
            $identifier['use'] = $use;
        }

        $this->documentReference['identifier'][] = $identifier;
    }

    public function setMasterIdentifier($system, $value)
    {
        $this->documentReference['masterIdentifier'] = [
            'system' => $system,
            'value' => $value,
        ];
    }

    public function setStatus($status = 'current')
    {
        $validStatuses = ['current', 'superseded', 'entered-in-error'];
        if (! in_array($status, $validStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid status');
        }
        $this->documentReference['status'] = $status;
    }

    public function setDocStatus($docStatus)
    {
        $validDocStatuses = ['preliminary', 'final', 'amended', 'entered-in-error'];
        if (! in_array($docStatus, $validDocStatuses)) {
            throw new FHIRInvalidPropertyValue('Invalid docStatus');
        }
        $this->documentReference['docStatus'] = $docStatus;
    }

    public function addType($system, $code, $display)
    {
        $this->documentReference['type']['coding'][] = [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ];
    }

    public function addCategory($system, $code, $display)
    {
        $this->documentReference['category'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function setSubject($reference, $display = null)
    {
        $this->documentReference['subject'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->documentReference['subject']['display'] = $display;
        }
    }

    public function setDate($date)
    {
        $this->documentReference['date'] = $date;
    }

    public function addAuthor($reference, $display = null)
    {
        $author = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $author['display'] = $display;
        }

        $this->documentReference['author'][] = $author;
    }

    public function setCustodian($reference, $display = null)
    {
        $this->documentReference['custodian'] = [
            'reference' => $reference,
        ];

        if ($display !== null) {
            $this->documentReference['custodian']['display'] = $display;
        }
    }

    public function addRelatesTo($code, $target)
    {
        $this->documentReference['relatesTo'][] = [
            'code' => $code,
            'target' => [
                'reference' => $target,
            ],
        ];
    }

    public function setDescription($description)
    {
        $this->documentReference['description'] = $description;
    }

    public function addSecurityLabel($system, $code, $display)
    {
        $this->documentReference['securityLabel'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
    }

    public function addContent($attachment, $format)
    {
        $this->documentReference['content'][] = [
            'attachment' => $attachment,
            'format' => $format,
        ];
    }

    public function addContext($context)
    {
        $this->documentReference['context'] = $context;
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (! array_key_exists('identifier', $this->documentReference)) {
            throw new FHIRMissingProperty('DocumentReference.identifier is required');
        }

        if (! array_key_exists('status', $this->documentReference)) {
            $this->setStatus();
        }

        return json_encode($this->documentReference, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}

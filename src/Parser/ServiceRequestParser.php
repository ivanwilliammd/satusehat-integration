<?php

namespace Satusehat\Integration\Parser;

class ServiceRequestParser
{
    private $serviceRequest;

    public function __construct($serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function getIdentifiers()
    {
        return $this->serviceRequest['identifier'] ?? null;
    }

    public function getRequisition()
    {
        return $this->serviceRequest['requisition'] ?? null;
    }

    public function getStatus()
    {
        return $this->serviceRequest['status'] ?? null;
    }

    public function getIntent()
    {
        return $this->serviceRequest['intent'] ?? null;
    }

    public function getCategories()
    {
        return $this->serviceRequest['category'] ?? null;
    }

    public function getPriority()
    {
        return $this->serviceRequest['priority'] ?? null;
    }

    public function getDoNotPerform()
    {
        return $this->serviceRequest['doNotPerform'] ?? null;
    }

    public function getCode()
    {
        return $this->serviceRequest['code'] ?? null;
    }

    public function getQuantityQuantity()
    {
        return $this->serviceRequest['quantityQuantity'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->serviceRequest['subject']['reference'] ?? null;
    }

    public function getSubjectDisplay()
    {
        return $this->serviceRequest['subject']['display'] ?? null;
    }

    public function getEncounterReference()
    {
        return $this->removePrefix($this->serviceRequest['encounter']['reference'] ?? null, 'Encounter/');
    }

    public function getEncounterDisplay()
    {
        return $this->serviceRequest['encounter']['display'] ?? null;
    }

    public function getOccurrenceDateTime()
    {
        return $this->serviceRequest['occurrenceDateTime'] ?? null;
    }

    public function getAuthoredOn()
    {
        return $this->serviceRequest['authoredOn'] ?? null;
    }

    public function getRequesterReference()
    {
        return $this->serviceRequest['requester']['reference'] ?? null;
    }

    public function getRequesterDisplay()
    {
        return $this->serviceRequest['requester']['display'] ?? null;
    }

    public function getPerformers()
    {
        return $this->serviceRequest['performer'] ?? null;
    }

    public function getReasonCodes()
    {
        return $this->serviceRequest['reasonCode'] ?? null;
    }

    public function getSupportingInfo()
    {
        return $this->serviceRequest['supportingInfo'] ?? null;
    }

    public function getSpecimens()
    {
        return $this->serviceRequest['specimen'] ?? null;
    }

    public function getNotes()
    {
        return $this->serviceRequest['note'] ?? null;
    }

    public function getPatientInstruction()
    {
        return $this->serviceRequest['patientInstruction'] ?? null;
    }

    public function getRelevantHistory()
    {
        return $this->serviceRequest['relevantHistory'] ?? null;
    }

    private function removePrefix($value, $prefix)
    {
        if ($value && strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
        return $value;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getCategory()
    {
        return $this->data['category'] ?? null;
    }

    public function getSubject()
    {
        return $this->data['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->data['encounter'] ?? null;
    }

    public function getRequester()
    {
        return $this->data['requester'] ?? null;
    }

    public function getPerformer()
    {
        return $this->data['performer'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->data['reasonCode'] ?? null;
    }

    public function getSpecimen()
    {
        return $this->data['specimen'] ?? null;
    }

    public function getNote()
    {
        return $this->data['note'] ?? null;
    }

    public function getExtension()
    {
        return $this->data['extension'] ?? null;
    }
}

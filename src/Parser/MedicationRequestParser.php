<?php

namespace Satusehat\Integration\Parser;

/**
 * MedicationRequest — typed accessors over the official SATUSEHAT payload.
 *
 * Source of the shape: satusehat-conformance/examples/satusehat/MedicationRequest.json, copied unchanged to
 * tests/Fixtures/satusehat/MedicationRequest.json so the test runs without cloning another repository.
 * Only elements present in that example are exposed; getRaw() always returns the payload, so a
 * caller is never blocked by a missing accessor.
 */
class MedicationRequestParser
{
    private $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function getRaw()
    {
        return $this->payload;
    }

    public function getResourceType()
    {
        return $this->payload['resourceType'] ?? null;
    }

    public function getContained()
    {
        return $this->payload['contained'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->payload['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->payload['status'] ?? null;
    }

    public function getIntent()
    {
        return $this->payload['intent'] ?? null;
    }

    public function getCategory()
    {
        return $this->payload['category'] ?? null;
    }

    public function getPriority()
    {
        return $this->payload['priority'] ?? null;
    }

    public function getMedicationReference()
    {
        return $this->payload['medicationReference'] ?? null;
    }

    public function getSubject()
    {
        return $this->payload['subject'] ?? null;
    }

    public function getEncounter()
    {
        return $this->payload['encounter'] ?? null;
    }

    public function getAuthoredOn()
    {
        return $this->payload['authoredOn'] ?? null;
    }

    public function getRequester()
    {
        return $this->payload['requester'] ?? null;
    }

    public function getCourseOfTherapyType()
    {
        return $this->payload['courseOfTherapyType'] ?? null;
    }

    public function getDosageInstruction()
    {
        return $this->payload['dosageInstruction'] ?? null;
    }

    public function getDispenseRequest()
    {
        return $this->payload['dispenseRequest'] ?? null;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getMedicationCodeableConcept()
    {
        return $this->data['medicationCodeableConcept'] ?? null;
    }

    public function getRecorder()
    {
        return $this->data['recorder'] ?? null;
    }

    public function getReasonCode()
    {
        return $this->data['reasonCode'] ?? null;
    }

    public function getReasonReference()
    {
        return $this->data['reasonReference'] ?? null;
    }

    public function getSubstitution()
    {
        return $this->data['substitution'] ?? null;
    }

    public function getPriorPrescription()
    {
        return $this->data['priorPrescription'] ?? null;
    }

    public function getDetectedIssue()
    {
        return $this->data['detectedIssue'] ?? null;
    }

    public function getEventHistory()
    {
        return $this->data['eventHistory'] ?? null;
    }

    public function getExtension()
    {
        return $this->data['extension'] ?? null;
    }
}

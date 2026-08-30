<?php

namespace Satusehat\Integration\Parser;

class DocumentReferenceParser
{
    private $documentReference;

    public function __construct($documentReference)
    {
        $this->documentReference = $documentReference;
    }

    public function getIdentifiers()
    {
        return $this->documentReference['identifier'] ?? null;
    }

    public function getMasterIdentifier()
    {
        return $this->documentReference['masterIdentifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->documentReference['status'] ?? null;
    }

    public function getDocStatus()
    {
        return $this->documentReference['docStatus'] ?? null;
    }

    public function getTypes()
    {
        return $this->documentReference['type'] ?? null;
    }

    public function getCategories()
    {
        return $this->documentReference['category'] ?? null;
    }

    public function getSubjectReference()
    {
        return $this->documentReference['subject']['reference'] ?? null;
    }

    public function getDate()
    {
        return $this->documentReference['date'] ?? null;
    }

    public function getAuthors()
    {
        return $this->documentReference['author'] ?? null;
    }

    public function getCustodianReference()
    {
        return $this->documentReference['custodian']['reference'] ?? null;
    }

    public function getRelatesTo()
    {
        return $this->documentReference['relatesTo'] ?? null;
    }

    public function getDescription()
    {
        return $this->documentReference['description'] ?? null;
    }

    public function getSecurityLabels()
    {
        return $this->documentReference['securityLabel'] ?? null;
    }

    public function getContents()
    {
        return $this->documentReference['content'] ?? null;
    }

    public function getContext()
    {
        return $this->documentReference['context'] ?? null;
    }
}

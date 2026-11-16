<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 CapabilityStatement.
 *
 * Dihasilkan dari PayloadBuilderCapabilityStatement oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/capabilitystatement.html
 */
class CapabilityStatementParser
{
    private array $data;

    public function __construct($data)
    {
        $this->data = is_array($data) ? $data : [];
    }

    public function getRaw(): array
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    public function getIdentifier()
    {
        return $this->data['identifier'] ?? null;
    }

    public function getStatus()
    {
        return $this->data['status'] ?? null;
    }

    public function getDate()
    {
        return $this->data['date'] ?? null;
    }

    public function getKind()
    {
        return $this->data['kind'] ?? null;
    }

    public function getFhirVersion()
    {
        return $this->data['fhirVersion'] ?? null;
    }

    public function getFormat()
    {
        return $this->data['format'] ?? null;
    }

    public function getRest()
    {
        return $this->data['rest'] ?? null;
    }
}

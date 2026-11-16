<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 HealthcareService.
 *
 * Dihasilkan dari PayloadBuilderHealthcareService oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/healthcareservice.html
 */
class HealthcareServiceParser
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

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getType()
    {
        return $this->data['type'] ?? null;
    }

    public function getLocation()
    {
        return $this->data['location'] ?? null;
    }

    public function getProvidedBy()
    {
        return $this->data['providedBy'] ?? null;
    }
}

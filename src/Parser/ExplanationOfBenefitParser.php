<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 ExplanationOfBenefit.
 *
 * Dihasilkan dari PayloadBuilderExplanationOfBenefit oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/explanationofbenefit.html
 */
class ExplanationOfBenefitParser
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

    public function getType()
    {
        return $this->data['type'] ?? null;
    }

    public function getUse()
    {
        return $this->data['use'] ?? null;
    }

    public function getPatient()
    {
        return $this->data['patient'] ?? null;
    }

    public function getCreated()
    {
        return $this->data['created'] ?? null;
    }

    public function getProvider()
    {
        return $this->data['provider'] ?? null;
    }
}

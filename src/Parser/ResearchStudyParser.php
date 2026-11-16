<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 ResearchStudy.
 *
 * Dihasilkan dari PayloadBuilderResearchStudy oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/researchstudy.html
 */
class ResearchStudyParser
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

    public function getTitle()
    {
        return $this->data['title'] ?? null;
    }

    public function getProtocol()
    {
        return $this->data['protocol'] ?? null;
    }

    public function getSponsor()
    {
        return $this->data['sponsor'] ?? null;
    }

    public function getPhase()
    {
        return $this->data['phase'] ?? null;
    }
}

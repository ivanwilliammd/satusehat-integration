<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 ResourceGuide.
 *
 * Dihasilkan dari PayloadBuilderResourceGuide oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/resourceguide.html
 */
class ResourceGuideParser
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

    public function getDescription()
    {
        return $this->data['description'] ?? null;
    }

    public function getVersion()
    {
        return $this->data['version'] ?? null;
    }

    public function getPublisher()
    {
        return $this->data['publisher'] ?? null;
    }
}

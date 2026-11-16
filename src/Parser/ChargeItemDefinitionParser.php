<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 ChargeItemDefinition.
 *
 * Dihasilkan dari PayloadBuilderChargeItemDefinition oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/chargeitemdefinition.html
 */
class ChargeItemDefinitionParser
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

    public function getUrl()
    {
        return $this->data['url'] ?? null;
    }

    public function getVersion()
    {
        return $this->data['version'] ?? null;
    }

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getTitle()
    {
        return $this->data['title'] ?? null;
    }

    public function getStatus()
    {
        return $this->data['status'] ?? null;
    }

    public function getDate()
    {
        return $this->data['date'] ?? null;
    }

    public function getPublisher()
    {
        return $this->data['publisher'] ?? null;
    }

    public function getDescription()
    {
        return $this->data['description'] ?? null;
    }

    public function getCode()
    {
        return $this->data['code'] ?? null;
    }

    public function getInstance()
    {
        return $this->data['instance'] ?? null;
    }

    public function getProperty()
    {
        return $this->data['property'] ?? null;
    }

    public function getApplies()
    {
        return $this->data['applies'] ?? null;
    }
}

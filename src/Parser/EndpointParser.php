<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 Endpoint.
 *
 * Dihasilkan dari PayloadBuilderEndpoint oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/endpoint.html
 */
class EndpointParser
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

    public function getConnectionType()
    {
        return $this->data['connectionType'] ?? null;
    }

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getManagingOrganization()
    {
        return $this->data['managingOrganization'] ?? null;
    }

    public function getContact()
    {
        return $this->data['contact'] ?? null;
    }

    public function getPeriod()
    {
        return $this->data['period'] ?? null;
    }

    public function getPayloadType()
    {
        return $this->data['payloadType'] ?? null;
    }

    public function getPayloadMimeType()
    {
        return $this->data['payloadMimeType'] ?? null;
    }

    public function getAddress()
    {
        return $this->data['address'] ?? null;
    }

    public function getHeader()
    {
        return $this->data['header'] ?? null;
    }
}

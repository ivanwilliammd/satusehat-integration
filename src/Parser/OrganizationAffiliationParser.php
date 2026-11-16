<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 OrganizationAffiliation.
 *
 * Dihasilkan dari PayloadBuilderOrganizationAffiliation oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/organizationaffiliation.html
 */
class OrganizationAffiliationParser
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

    public function getOrganization()
    {
        return $this->data['organization'] ?? null;
    }

    public function getParticipatingOrganization()
    {
        return $this->data['participatingOrganization'] ?? null;
    }

    public function getNetwork()
    {
        return $this->data['network'] ?? null;
    }

    public function getCode()
    {
        return $this->data['code'] ?? null;
    }
}

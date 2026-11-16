<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 MedicinalProductPharmaceutical.
 *
 * Dihasilkan dari PayloadBuilderMedicinalProductPharmaceutical oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/medicinalproductpharmaceutical.html
 */
class MedicinalProductPharmaceuticalParser
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

    public function getRoute()
    {
        return $this->data['route'] ?? null;
    }

    public function getDoseForm()
    {
        return $this->data['doseForm'] ?? null;
    }

    public function getIngredient()
    {
        return $this->data['ingredient'] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Parser;

/**
 * Parser untuk resource FHIR R4 MedicinalProductContraindication.
 *
 * Dihasilkan dari PayloadBuilderMedicinalProductContraindication oleh scripts/generate_parsers.py — jangan disunting
 * tangan. Setiap getter membaca satu elemen tingkat atas dan mengembalikan null bila tidak ada.
 *
 * @link https://www.hl7.org/fhir/medicinalproductcontraindication.html
 */
class MedicinalProductContraindicationParser
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

    public function getSubject()
    {
        return $this->data['subject'] ?? null;
    }

    public function getDisease()
    {
        return $this->data['disease'] ?? null;
    }

    public function getComorbidity()
    {
        return $this->data['comorbidity'] ?? null;
    }
}

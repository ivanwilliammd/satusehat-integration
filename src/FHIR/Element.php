<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

/**
 * Basis untuk facade FHIR bergaya element: hanya menampung array $data, sedangkan
 * serialisasi dan validasi tetap milik masing-masing kelas (lewat json()).
 *
 * Kelas ini sempat TIDAK ADA sama sekali (juga tidak pernah ada di riwayat git), padahal
 * Immunization, MedicationAdministration, MedicationDispense, dan Specimen menulis
 * `extends Element` — keempat facade itu gagal total saat dipakai dengan
 * "Class "Satusehat\Integration\FHIR\Element" not found". Penjaganya:
 * tests/FHIR/FacadeParityTest.php::test_element_facades_can_be_instantiated().
 */
abstract class Element
{
    /** @var array<string, mixed> */
    public array $data = [];

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->data;
    }
}

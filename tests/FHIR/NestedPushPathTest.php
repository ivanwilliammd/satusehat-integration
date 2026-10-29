<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderClaim;
use Satusehat\Integration\Builder\PayloadBuilderGenomicStudy;
use Satusehat\Integration\Builder\PayloadBuilderGroup;
use Satusehat\Integration\Builder\PayloadBuilderImmunization;
use Satusehat\Integration\Builder\PayloadBuilderNutritionOrder;
use Satusehat\Integration\Builder\PayloadBuilderPatient;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Timing;

/**
 * `Builder::push()` dulu hanya menambah ke kunci datar, sehingga `push('meta/profile', …)`
 * menghasilkan kunci literal `meta/profile` — bukan `meta.profile`. JSON-nya tetap terbentuk,
 * jadi tidak ada galat yang terlihat, tetapi payload-nya bukan FHIR: server menerima properti
 * yang tidak dikenal dan `meta.profile` yang dituntut SATUSEHAT tidak pernah ada.
 *
 * Uji ini menjaga dua hal sekaligus: tidak ada kunci berisi '/' di payload mana pun, dan medan
 * berjenjang benar-benar mendarat di tempatnya.
 */
class NestedPushPathTest extends TestCase
{
    /** Cari kunci yang memuat '/' pada kedalaman berapa pun. */
    private function keysWithSlash(array $payload, string $path = ''): array
    {
        $bad = [];
        foreach ($payload as $key => $value) {
            $p = $path === '' ? (string) $key : $path.'.'.$key;
            if (str_contains((string) $key, '/')) {
                $bad[] = $p;
            }
            if (is_array($value)) {
                $bad = array_merge($bad, $this->keysWithSlash($value, $p));
            }
        }

        return $bad;
    }

    /** @return array<string, array{0: object, 1: string}> */
    private function cases(): array
    {
        $patient = (new PayloadBuilderPatient())->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Patient');
        $group = (new PayloadBuilderGroup())->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Group');
        $immunization = (new PayloadBuilderImmunization())->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/Immunization');
        $genomic = (new PayloadBuilderGenomicStudy())->setMetaProfile('https://fhir.kemkes.go.id/r4/StructureDefinition/GenomicStudy');
        $claim = (new PayloadBuilderClaim())->addInsuranceAccount('Coverage/C1');
        $diet = (new PayloadBuilderNutritionOrder())
            ->setOralDietType((new CodeableConcept())->addCoding(new Coding('http://snomed.info/sct', '386619000', null)));
        $jadwal = (new PayloadBuilderNutritionOrder())->addOralDietSchedule(new Timing());

        return [
            'Patient.meta.profile' => [$patient, 'meta.profile'],
            'Group.meta.profile' => [$group, 'meta.profile'],
            'Immunization.meta.profile' => [$immunization, 'meta.profile'],
            'GenomicStudy.meta.profile' => [$genomic, 'meta.profile'],
            'Claim.insurance' => [$claim, 'insurance'],
            'Claim.insurance.account' => [$claim, 'insurance.account'],
            'NutritionOrder.oralDiet.type' => [$diet, 'oralDiet.type'],
            'NutritionOrder.oralDiet.schedule' => [$jadwal, 'oralDiet.schedule'],
        ];
    }

    public function test_no_payload_has_a_key_containing_a_slash(): void
    {
        foreach ($this->cases() as $label => [$builder, $path]) {
            $payload = $builder->json();
            $this->assertSame([], $this->keysWithSlash($payload), $label.' — payload memuat kunci berisi "/"');
        }
    }

    public function test_nested_fields_land_at_their_real_path(): void
    {
        foreach ($this->cases() as $label => [$builder, $path]) {
            $payload = $builder->json();
            $node = $payload;
            foreach (explode('.', $path) as $seg) {
                // Indeks numerik (mis. `insurance.0.account`) menunjuk elemen daftar, bukan kunci.
                $node = ctype_digit($seg) ? ($node[(int) $seg] ?? null) : ($node[$seg] ?? null);
                $this->assertIsArray($node, $label.' — '.$path.' tidak ada sebagai medan berjenjang');
            }
            $this->assertNotEmpty($node, $label.' — '.$path.' kosong');
        }
    }
}

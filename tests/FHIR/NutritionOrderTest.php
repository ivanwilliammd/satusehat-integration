<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderNutritionOrder;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\DataType\Timing;

class NutritionOrderTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $this->assertSame('NutritionOrder', $builder->build()['resourceType']);
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $result = $builder->setId('no-001')->build();
        $this->assertSame('no-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $id = new Identifier('http://sys-ids.kemkes.go.id/nutrition-order', 'NO-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('NO-001', $result['identifier'][0]['value']);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_intent()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $result = $builder->setIntent('order')->build();
        $this->assertSame('order', $result['intent']);
    }

    public function test_set_patient()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $patient = new Reference('Patient/100000030009', 'Budi Santoso');
        $result = $builder->setPatient($patient)->build();
        $this->assertSame('Patient/100000030009', $result['patient']['reference']);
    }

    public function test_set_encounter()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $encounter = new Reference('Encounter/enc-001', 'Kunjungan Rawat Inap');
        $result = $builder->setEncounter($encounter)->build();
        $this->assertSame('Encounter/enc-001', $result['encounter']['reference']);
    }

    public function test_set_date_time()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $result = $builder->setDateTime('2024-01-15T10:00:00+00:00')->build();
        $this->assertSame('2024-01-15T10:00:00+00:00', $result['dateTime']);
    }

    public function test_set_orderer()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $orderer = new Reference('Practitioner/N10000001', 'Dr. Smith');
        $result = $builder->setOrderer($orderer)->build();
        $this->assertSame('Practitioner/N10000001', $result['orderer']['reference']);
    }

    public function test_add_allergy_type()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $allergy = new CodeableConcept();
        $allergy->addCoding(new Coding('http://snomed.info/sct', '91936005', 'Allergy to nuts'));
        $result = $builder->addAllergyType($allergy)->build();
        $this->assertNotEmpty($result['allergyType']);
    }

    public function test_add_food_preference_modifier()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $pref = new CodeableConcept();
        $pref->addCoding(new Coding('http://snomed.info/sct', '228273006', 'Vegetarian'));
        $result = $builder->addFoodPreferenceModifier($pref)->build();
        $this->assertSame('228273006', $result['foodPreferenceModifier'][0]['coding'][0]['code']);
    }

    public function test_add_exclude_food_modifier()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $exclude = new CodeableConcept();
        $exclude->addCoding(new Coding('http://snomed.info/sct', '102259006', 'Exclude nuts'));
        $result = $builder->addExcludeFoodModifier($exclude)->build();
        $this->assertSame('102259006', $result['excludeFoodModifier'][0]['coding'][0]['code']);
    }

    public function test_set_oral_diet_type()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $type = new CodeableConcept();
        $type->addCoding(new Coding('http://snomed.info/sct', '386128009', 'Low sodium diet'));
        $result = $builder->setOralDietType($type)->build();
        $this->assertIsArray($result);
    }

    public function test_add_oral_diet_schedule()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $schedule = new Timing();
        $result = $builder->addOralDietSchedule($schedule)->build();
        $this->assertIsArray($result);
    }

    public function test_add_supplement()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $supplement = [
            'name' => ['text' => 'Protein Supplement'],
            'quantity' => ['value' => 1, 'unit' => 'serving']
        ];
        $result = $builder->addSupplement($supplement)->build();
        $this->assertSame('Protein Supplement', $result['supplement'][0]['name']['text']);
    }

    public function test_set_enteral_formula()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $formula = [
            'baseFormulaType' => [
                'coding' => [
                    ['system' => 'http://snomed.info/sct', 'code' => '446412006', 'display' => 'Standard formula']
                ]
            ]
        ];
        $result = $builder->setEnteralFormula($formula)->build();
        $this->assertSame('446412006', $result['enteralFormula']['baseFormulaType']['coding'][0]['code']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderNutritionOrder;
        $patient = new Reference('Patient/100000030009');

        $builder->setId('no-002')
            ->setStatus('active')
            ->setIntent('order')
            ->setPatient($patient);

        $this->assertIsArray($builder->build());
        $this->assertSame('no-002', $builder->build()['id']);
    }
}

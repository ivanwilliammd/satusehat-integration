<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderMedication;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Quantity;
use Satusehat\Integration\DataType\Reference;

class MedicationTest extends TestCase
{
    public function test_sets_resource_type()
    {
        $builder = new PayloadBuilderMedication;
        $this->assertSame('Medication', $builder->build()['resourceType']);
    }

    public function test_sets_default_profile()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->build();
        $this->assertSame(
            'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication',
            $result['meta']['profile'][0]
        );
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->setId('med-001')->build();
        $this->assertSame('med-001', $result['id']);
    }

    public function test_add_identifier()
    {
        $builder = new PayloadBuilderMedication;
        $id = new Identifier('http://sys-ids.kemkes.go.id/medication', 'MED-001');
        $result = $builder->addIdentifier($id)->build();
        $this->assertSame('MED-001', $result['identifier'][0]['value']);
    }

    public function test_set_code()
    {
        $builder = new PayloadBuilderMedication;
        $code = new CodeableConcept();
        $code->addCoding(new Coding('http://www.whocc.no/atc', 'N02BE01', 'Paracetamol'));
        $result = $builder->setCode($code)->build();
        $this->assertSame('N02BE01', $result['code']['coding'][0]->code);
    }

    public function test_set_status()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->setStatus('active')->build();
        $this->assertSame('active', $result['status']);
    }

    public function test_set_manufacturer()
    {
        $builder = new PayloadBuilderMedication;
        $mfr = new Reference('Organization/org-mfr-001', 'Kimia Farma');
        $result = $builder->setManufacturer($mfr)->build();
        $this->assertSame('Organization/org-mfr-001', $result['manufacturer']['reference']);
    }

    public function test_set_form()
    {
        $builder = new PayloadBuilderMedication;
        $form = new CodeableConcept();
        $form->addCoding(new Coding('http://standardterms.edqm.eu', '10219000', 'Tablet'));
        $result = $builder->setForm($form)->build();
        $this->assertSame('10219000', $result['form']['coding'][0]->code);
    }

    public function test_add_ingredient_basic()
    {
        $builder = new PayloadBuilderMedication;
        $item = new CodeableConcept();
        $item->addCoding(new Coding('http://www.whocc.no/atc', 'N02BE01', 'Paracetamol'));
        $result = $builder->addIngredient($item, true)->build();
        $this->assertSame('N02BE01', $result['ingredient'][0]['itemCodeableConcept']['coding'][0]->code);
        $this->assertTrue($result['ingredient'][0]['isActive']);
    }

    public function test_add_ingredient_with_strength()
    {
        $builder = new PayloadBuilderMedication;
        $item = new CodeableConcept();
        $item->addCoding(new Coding('http://www.whocc.no/atc', 'N02BE01', 'Paracetamol'));
        $strength = new Quantity(500.0, null, 'mg', 'http://unitsofmeasure.org', 'mg');
        $result = $builder->addIngredient($item, true, $strength)->build();
        $this->assertSame(500.0, $result['ingredient'][0]['strength']['value']);
    }

    public function test_set_batch()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->setBatch('LOT2024001', '2025-12-31')->build();
        $this->assertSame('LOT2024001', $result['batch']['lotNumber']);
        $this->assertSame('2025-12-31', $result['batch']['expirationDate']);
    }

    public function test_add_medication_type_nc()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->addMedicationType('NC', 'Non-compound')->build();
        $this->assertSame('https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType', $result['extension'][0]['url']);
        $this->assertSame('NC', $result['extension'][0]['valueCodeableConcept']['coding'][0]['code']);
    }

    public function test_add_medication_type_sd()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->addMedicationType('SD', 'Gives of such doses')->build();
        $this->assertSame('SD', $result['extension'][0]['valueCodeableConcept']['coding'][0]['code']);
    }

    public function test_add_medication_type_ep()
    {
        $builder = new PayloadBuilderMedication;
        $result = $builder->addMedicationType('EP', 'Divide into equal parts')->build();
        $this->assertSame('EP', $result['extension'][0]['valueCodeableConcept']['coding'][0]['code']);
    }

    public function test_chaining()
    {
        $builder = new PayloadBuilderMedication;
        $code = new CodeableConcept();
        $code->addCoding(new Coding(null, 'ABC', 'Test Med'));
        $builder->setId('med-002')
            ->setStatus('active')
            ->setCode($code);

        $this->assertIsArray($builder->build());
        $this->assertSame('med-002', $builder->build()['id']);
    }
}

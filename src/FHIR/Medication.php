<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRException;
use Satusehat\Integration\OAuth2Client;

class Medication extends OAuth2Client
{
    public array $medication_form = array(
        "BS001" => "Aerosol Foam",
        "BS002" => "Aerosol Metered Dose",
        "BS003" => "Aerosol Spray",
        "BS004" => "Oral Spray",
        "BS005" => "Buscal Spray",
        "BS006" => "Transdermal Spray",
        "BS007" => "Topical Spray",
        "BS008" => "Serbuk Spray",
        "BS009" => "Eliksir",
        "BS010" => "Emulsi",
        "BS011" => "Enema",
        "BS012" => "Gas",
        "BS013" => "Gel",
        "BS014" => "Gel Mata",
        "BS015" => "Granul Effervescent",
        "BS016" => "Granula",
        "BS017" => "Intra Uterine Device (IUD)",
        "BS018" => "Implant",
        "BS019" => "Kapsul",
        "BS020" => "Kapsul Lunak",
        "BS021" => "Kapsul Pelepasan Lambat",
        "BS022" => "Kaplet",
        "BS023" => "Kaplet Salut Selaput",
        "BS024" => "Kaplet Salut Enterik",
        "BS025" => "Kaplet Salut Gula",
        "BS026" => "Kaplet Pelepasan Lambat",
        "BS027" => "Kaplet Pelepasan Cepat",
        "BS028" => "Kaplet Kunyah",
        "BS029" => "Kaplet Kunyah Salut Selaput",
        "BS030" => "Krim",
        "BS031" => "Krim Lemak",
        "BS032" => "Larutan",
        "BS033" => "Larutan Inhalasi",
        "BS034" => "Larutan Injeksi",
        "BS035" => "Infus",
        "BS036" => "Obat Kumur",
        "BS037" => "Ovula",
        "BS038" => "Pasta",
        "BS039" => "Pil",
        "BS040" => "Patch",
        "BS041" => "Pessary",
        "BS042" => "Salep",
        "BS043" => "Salep Mata",
        "BS044" => "Sampo",
        "BS045" => "Semprot Hidung",
        "BS046" => "Serbuk Aerosol",
        "BS047" => "Serbuk Oral",
        "BS048" => "Serbuk Inhaler",
        "BS049" => "Serbuk Injeksi",
        "BS050" => "Serbuk Injeksi Liofilisasi",
        "BS051" => "Serbuk Infus",
        "BS052" => "Serbuk Obat Luar / Serbuk Tabur",
        "BS053" => "Serbuk Steril",
        "BS054" => "Serbuk Effervescent",
        "BS055" => "Sirup",
        "BS056" => "Sirup Kering",
        "BS057" => "Sirup Kering Pelepasan Lambat",
        "BS058" => "Subdermal Implants",
        "BS059" => "Supositoria",
        "BS060" => "Suspensi",
        "BS061" => "Suspensi Injeksi",
        "BS062" => "Suspensi / Cairan Obat Luar",
        "BS063" => "Cairan Steril",
        "BS064" => "Cairan Mata",
        "BS065" => "Cairan Diagnostik",
        "BS066" => "Tablet",
        "BS067" => "Tablet Effervescent",
        "BS068" => "Tablet Hisap",
        "BS069" => "Tablet Kunyah",
        "BS070" => "Tablet Pelepasan Cepat",
        "BS071" => "Tablet Pelepasan Lambat",
        "BS072" => "Tablet Disintegrasi Oral",
        "BS073" => "Tablet Dispersibel",
        "BS074" => "Tablet Cepat Larut",
        "BS075" => "Tablet Salut Gula",
        "BS076" => "Tablet Salut Enterik",
        "BS077" => "Tablet Salut Selaput",
        "BS078" => "Tablet Sublingual",
        "BS079" => "Tablet Sublingual Pelepasan Lambat",
        "BS080" => "Tablet Vaginal",
        "BS081" => "Tablet Lapis",
        "BS082" => "Tablet Lapis Lepas Lambat",
        "BS083" => "Chewing Gum",
        "BS084" => "Tetes Mata",
        "BS085" => "Tetes Hidung",
        "BS086" => "Tetes Telinga",
        "BS087" => "Tetes Oral (Oral Drops)",
        "BS088" => "Tetes Mata Dan Telinga",
        "BS089" => "Transdermal",
        "BS090" => "Transdermal Urethral",
        "BS091" => "Tulle/Plester Obat",
        "BS092" => "Vaginal Cream",
        "BS093" => "Vaginal Gel",
        "BS094" => "Vaginal Douche",
        "BS095" => "Vaginal Ring",
        "BS096" => "Vaginal Tissue",
        "BS097" => "Suspensi Inhalasi",
        "MF000001" => "Orodispersible Film"
    );

    public array $medication = [
        'resourceType' => 'Medication',
        'meta' => [
            'profile' => [
                'https://fhir.kemkes.go.id/r4/StructureDefinition/Medication',
            ],
        ],
    ];

    public function addIdentifier($identifier)
    {
        $identifier['system'] = 'http://sys-ids.kemkes.go.id/medication/'.$this->organization_id;
        $identifier['value'] = $identifier;
        $identifier['use'] = 'official';

        $this->medication['identifier'][] = $identifier;
    }

    public function setCode($code = null, $display = null)
    {
        $coding['system'] = 'http://sys-ids.kemkes.go.id/kfa';
        $coding['code'] = $code;

        if ($display) {
            $coding['display'] = $display;
        }

        $this->medication['code']['coding'][] = $coding;
    }

    public function setStatus($status = 'active')
    {
        $this->medication['status'] = $status;
    }

    public function setManufacturer($manufacturer = null)
    {
        $this->medication['manufacturer']['reference'] = 'Organization/'.($manufacturer ? $manufacturer : $this->organization_id);
    }

    public function setForm($code = null)
    {
        # Check display, if not exist, throw FHIRException
        if (! array_key_exists($code, $this->medication_form)) {
            throw new FHIRException("Medication form code not found");
        }

        $this->medication['form']['coding'][] = [
            'system' => 'http://terminology.hl7.org/CodeSystem/medication-form-codes',
            'code' => $code,
            'display' => $this->medication_form[$code],
        ];
    }

    public function setAmount($numerator = null, $numerator_unit = null, $denominator = null, $denominator_unit = null)
    {
        # If numerator is null, throw FHIRException, similarly with numerator_unit
        if (! $numerator || ! is_numeric($numerator)) {
            throw new FHIRException("Numerator is required in numeric format");
        }

        if (! $numerator_unit) {
            throw new FHIRException("Numerator unit is required (see http://unitsofmeasure.org)");
        }

        # If denominator is not null, denominator_unit is required
        if ($denominator || ! is_numeric($denominator)) {
            throw new FHIRException("Denominator is required in numeric format");
        }

        if (! $denominator_unit) {
            throw new FHIRException("Denominator unit is required (see http://unitsofmeasure.org)");
        }

        $amount['numerator']['value'] = $numerator;
        $amount['numerator']['system'] = 'http://unitsofmeasure.org';
        $amount['numerator']['code'] = $numerator_unit;

        $amount['denominator']['value'] = $denominator;
        $amount['denominator']['system'] = 'http://unitsofmeasure.org';
        $amount['denominator']['code'] = $denominator_unit;
    }



    public function setBatch($lotNumber = null, $expirationDate = null)
    {
        # If lotNumber is null, throw FHIRException, similarly with expirationDate
        if (! $lotNumber) {
            throw new FHIRException("Lot number is required");
        }

        if (! $expirationDate) {
            throw new FHIRException("Expiration date is required");
        }

        $batch['lotNumber'] = $lotNumber;
        $batch['expirationDate'] = $expirationDate;

        $this->medication['batch'] = $batch;
    }

    public function setMedicationType($code = 'NC')
    {
        $medicationTypeOption = array(
            'NC' => 'Non-compound',
            'SD' => 'Gives of such doses',
            'EP' => 'Divide into equal parts'
        );

        $medicationType['url'] = 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType';
        $medicationType['valueCodeableConcept']['coding'][] = [
            'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
            'code' => $code,
            'display' => $medicationTypeOption[$code],
        ];

        $this->medication['extension'][] = $medicationType;
    }
}

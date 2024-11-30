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

    public array $drug_form = array(
        "APPFUL" => array("display" => "Applicatorful", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DROP" => array("display" => "Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NDROP" => array("display" => "Nasal Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPDROP" => array("display" => "Ophthalmic Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORDROP" => array("display" => "Oral Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OTDROP" => array("display" => "Otic Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PUFF" => array("display" => "Puff", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SCOOP" => array("display" => "Scoops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SPRY" => array("display" => "Sprays", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_DispensableDrugForm" => array("display" => "DispensableDrugForm", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_GasDrugForm" => array("display" => "GasDrugForm", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "GASINHL" => array("display" => "Gas for Inhalation", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_GasLiquidMixture" => array("display" => "GasLiquidMixture", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "AER" => array("display" => "Aerosol", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BAINHL" => array("display" => "Breath Activated Inhaler", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "INHLSOL" => array("display" => "Inhalant Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MDINHL" => array("display" => "Metered Dose Inhaler", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NASSPRY" => array("display" => "Nasal Spray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DERMSPRY" => array("display" => "Dermal Spray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "FOAM" => array("display" => "Foam", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "FOAMAPL" => array("display" => "Foam with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECFORM" => array("display" => "Rectal foam", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGFOAM" => array("display" => "Vaginal foam", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGFOAMAPL" => array("display" => "Vaginal foam with applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECSPRY" => array("display" => "Rectal Spray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGSPRY" => array("display" => "Vaginal Spray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_GasSolidSpray" => array("display" => "GasSolidSpray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "INHL" => array("display" => "Inhalant", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BAINHLPWD" => array("display" => "Breath Activated Powder Inhaler", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "INHLPWD" => array("display" => "Inhalant Powder", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MDINHLPWD" => array("display" => "Metered Dose Powder Inhaler", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NASINHL" => array("display" => "Nasal Inhalant", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORINHL" => array("display" => "Oral Inhalant", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PWDSPRY" => array("display" => "Powder Spray", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SPRYADAPT" => array("display" => "Spray with Adaptor", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_Liquid" => array("display" => "Liquid", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "LIQCLN" => array("display" => "Liquid Cleanser", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "LIQSOAP" => array("display" => "Medicated Liquid Soap", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SHMP" => array("display" => "Shampoo", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OIL" => array("display" => "Oil", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPOIL" => array("display" => "Topical Oil", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SOL" => array("display" => "Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "IPSOL" => array("display" => "Intraperitoneal Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "IRSOL" => array("display" => "Irrigation Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DOUCHE" => array("display" => "Douche", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ENEMA" => array("display" => "Enema", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPIRSOL" => array("display" => "Ophthalmic Irrigation Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "IVSOL" => array("display" => "Intravenous Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORALSOL" => array("display" => "Oral Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ELIXIR" => array("display" => "Elixir", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RINSE" => array("display" => "Mouthwash/Rinse", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SYRUP" => array("display" => "Syrup", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORDROP" => array("display" => "Oral Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECSOL" => array("display" => "Rectal Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPSOL" => array("display" => "Topical Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "LIN" => array("display" => "Liniment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MUCTOPSOL" => array("display" => "Mucous Membrane Topical Solution", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TINCTURE" => array("display" => "Tincture", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DROP" => array("display" => "Drops", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_LiquidLiquidEmulsion" => array("display" => "LiquidLiquidEmulsion", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CRM" => array("display" => "Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NASCRM" => array("display" => "Nasal Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPCRM" => array("display" => "Ophthalmic Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORCRM" => array("display" => "Oral Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OTCRM" => array("display" => "Otic Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECCRM" => array("display" => "Rectal Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPCRM" => array("display" => "Topical Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGCRM" => array("display" => "Vaginal Cream", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGCRMAPL" => array("display" => "Vaginal Cream with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "LTN" => array("display" => "Lotion", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPLTN" => array("display" => "Topical Lotion", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OINT" => array("display" => "Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NASOINT" => array("display" => "Nasal Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OINTAPL" => array("display" => "Ointment with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPOINT" => array("display" => "Ophthalmic Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OTOINT" => array("display" => "Otic Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECOINT" => array("display" => "Rectal Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPOINT" => array("display" => "Topical Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGOINT" => array("display" => "Vaginal Ointment", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGOINAPPL" => array("display" => "Vaginal Ointment with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_LiquidSolidSuspension" => array("display" => "LiquidSolidSuspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "GEL" => array("display" => "Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "GELAPL" => array("display" => "Gel with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "NASGEL" => array("display" => "Nasal Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPGEL" => array("display" => "Ophthalmic Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OTGEL" => array("display" => "Otic Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPGEL" => array("display" => "Topical Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "URETHGEL" => array("display" => "Urethral Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGGEL" => array("display" => "Vaginal Gel", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VGELAPL" => array("display" => "Vaginal Gel with Applicator", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PASTE" => array("display" => "Paste", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PUD" => array("display" => "Pudding", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPASTE" => array("display" => "Toothpaste", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SUSP" => array("display" => "Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ITSUSP" => array("display" => "Intrathecal Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OPSUSP" => array("display" => "Ophthalmic Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORSUSP" => array("display" => "Oral Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERSUSP" => array("display" => "Extended-Release Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERSUSP12" => array("display" => "12 Hour Extended-Release Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERSUSP24" => array("display" => "24 Hour Extended Release Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "OTSUSP" => array("display" => "Otic Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECSUSP" => array("display" => "Rectal Suspension", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "_SolidDrugForm" => array("display" => "Solid Drug Form", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BAR" => array("display" => "Bar", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BARSOAP" => array("display" => "Bar Soap", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MEDBAR" => array("display" => "Medicated Bar Soap", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CHEWBAR" => array("display" => "Chewable Bar", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BEAD" => array("display" => "Beads", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CAKE" => array("display" => "Cake", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CEMENT" => array("display" => "Cement", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CRYS" => array("display" => "Crystals", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DISK" => array("display" => "Disk", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "FLAKE" => array("display" => "Flakes", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "GRAN" => array("display" => "Granules", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "GUM" => array("display" => "Chewing Gum", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PAD" => array("display" => "Pad", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MEDPAD" => array("display" => "Medicated Pad", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PATCH" => array("display" => "Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATCH" => array("display" => "Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATH16" => array("display" => "16 Hour Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATH24" => array("display" => "24 Hour Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATH2WK" => array("display" => "Biweekly Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATH72" => array("display" => "72 Hour Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TPATHWK" => array("display" => "Weekly Transdermal Patch", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PELLET" => array("display" => "Pellet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "PILL" => array("display" => "Pill", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CAP" => array("display" => "Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORCAP" => array("display" => "Oral Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ENTCAP" => array("display" => "Enteric Coated Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERECAP" => array("display" => "Extended Release Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERCAP" => array("display" => "Extended Release Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERCAP12" => array("display" => "12 Hour Extended Release Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERCAP24" => array("display" => "24 Hour Extended Release Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERECCAP" => array("display" => "Extended Release Enteric Coated Capsule", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERTAB" => array("display" => "Extended Release Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TAB" => array("display" => "Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORTAB" => array("display" => "Oral Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "BUCTAB" => array("display" => "Buccal Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SRBUCTAB" => array("display" => "Sustained Release Buccal Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CAPLET" => array("display" => "Caplet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CHEWTAB" => array("display" => "Chewable Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "CPTAB" => array("display" => "Coated Particles Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DISINTAB" => array("display" => "Disintegrating Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "DRTAB" => array("display" => "Delayed Release Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ECTAB" => array("display" => "Enteric Coated Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERECTAB" => array("display" => "Extended Release Enteric Coated Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERTAB" => array("display" => "Extended Release Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERTAB12" => array("display" => "12 Hour Extended Release Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ERTAB24" => array("display" => "24 Hour Extended Release Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "ORTROCHE" => array("display" => "Lozenge/Oral Troche", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SLTAB" => array("display" => "Sublingual Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGTAB" => array("display" => "Vaginal Tablet", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "POWD" => array("display" => "Powder", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "TOPPWD" => array("display" => "Topical Powder", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECPWD" => array("display" => "Rectal Powder", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGPWD" => array("display" => "Vaginal Powder", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SUPP" => array("display" => "Suppository", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "RECSUPP" => array("display" => "Rectal Suppository", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "URETHSUPP" => array("display" => "Urethral suppository", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "VAGSUPP" => array("display" => "Vaginal Suppository", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "SWAB" => array("display" => "Swab", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "MEDSWAB" => array("display" => "Medicated swab", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "WAFER" => array("display" => "Wafer", "system" => "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm"),
        "EMULSION" => array("display" => "Emulsion", "system" => "http://snomed.info/sct"),
        "GAS" => array("display" => "Gas", "system" => "http://snomed.info/sct"),
        "INTRAUTERINE" => array("display" => "Intrauterine dose form", "system" => "http://snomed.info/sct"),
        "IMPLANT" => array("display" => "Implant", "system" => "http://snomed.info/sct"),
        "PESSARY" => array("display" => "Pessary", "system" => "http://snomed.info/sct"),
        "PROLONGEDRELEASE" => array("display" => "Prolonged-release", "system" => "http://snomed.info/sct"),
        "EFFERVESCENT" => array("display" => "Effervescent tablet", "system" => "http://snomed.info/sct"),
        "TABSUSPENSION" => array("display" => "Tablet for oral suspension", "system" => "http://snomed.info/sct"),
        "TABORAL" => array("display" => "Tablet for conventional release oral solution", "system" => "http://snomed.info/sct"),
        "FILMCOATED" => array("display" => "Film-coated tablet", "system" => "http://snomed.info/sct"),
        "IMPREGDRESSING" => array("display" => "Impregnated dressing", "system" => "http://snomed.info/sct"),
        "PROLONGEDVAGINAL" => array("display" => "Prolonged-release vaginal ring", "system" => "http://snomed.info/sct"),
        "VAGINALDOSE" => array("display" => "Vaginal dose form", "system" => "http://snomed.info/sct")
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

    public function setAmount($numerator = null, $numerator_unit = null, $denominator = 1, $denominator_unit = null)
    {
        # If numerator is null, throw FHIRException, similarly with numerator_unit
        if (! $numerator || ! is_numeric($numerator)) {
            throw new FHIRException("Numerator is required in numeric format");
        }

        if (! $numerator_unit) {
            throw new FHIRException("Numerator unit is required (see http://unitsofmeasure.org)");
        }

        if (! $denominator_unit) {
            throw new FHIRException("Denominator unit is required (see http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm)");
        }

        $amount['numerator']['value'] = $numerator;
        $amount['numerator']['system'] = 'http://unitsofmeasure.org';
        $amount['numerator']['code'] = $numerator_unit;

        $amount['denominator']['value'] = $denominator;
        $amount['denominator']['system'] = $this->drug_form[$denominator_unit]['system'];
        $amount['denominator']['code'] = $denominator_unit;
    }

    public function addIngredient($itemCode = null, $itemDisplay = null, $numerator = null, $numerator_unit = null, $denominator = 1, $denominator_unit = null)
    {
        # If itemCode is null, throw FHIRException, similarly with itemDisplay
        if (! $itemCode) {
            throw new FHIRException("Item code is required");
        }

        if (! $itemDisplay) {
            throw new FHIRException("Item display is required");
        }

        $ingredient['item']['coding'][] = [
            'system' => 'http://sys-ids.kemkes.go.id/kfa',
            'code' => $itemCode,
            'display' => $itemDisplay,
        ];

        $ingredient['isActive'] = true;

        $ingredient['strength']['numerator']['value'] = $numerator;
        $ingredient['strength']['numerator']['system'] = 'http://unitsofmeasure.org';
        $ingredient['strength']['numerator']['code'] = $numerator_unit;

        $ingredient['strength']['denominator']['value'] = $denominator;
        $ingredient['strength']['denominator']['system'] = $this->drug_form[$denominator_unit]['system'];
        $ingredient['strength']['denominator']['code'] = $denominator_unit;

        $this->medication['ingredient'][] = $ingredient;
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

    public function json()
    {
        return json_encode($this->medication, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('Medication', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->medication['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('Medication', $id, $payload);

        return [$statusCode, $res];
    }

}

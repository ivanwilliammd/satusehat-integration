<?php

require_once 'vendor/autoload.php';

// This file will generate organization.json

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddress;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddressType;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddressUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBoolean;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCodeableConcept;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPoint;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPointSystem;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPointUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifier;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifierUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIROrganization;

$organization = new FHIROrganization();

// Set active
$active = new FHIRBoolean(true);

// Set identifier
$identifier = new FHIRIdentifier();
$identifier->setValue('DJM_Dir');
$identifier->setSystem(new FHIRUri('http://sys-ids.kemkes.go.id/organization/80216c56-cf06-4c79-8211-ba4e5200d323', true));
$identifier->setUse(new FHIRIdentifierUse('official'));

// Set type
$type = new FHIRCodeableConcept();
$type->addCoding(new FHIRCoding());
$type->getCoding()[0]->setCode(new FHIRCode('dept'));
$type->getCoding()[0]->setDisplay(new FHIRString('Hospital Department'));
$type->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/organization-type', true));

// Set name
$name = new FHIRString('Direktorat Penunjang Medis');

// Set telecom
$telecom = new FHIRContactPoint();
$telecom->setSystem(new FHIRContactPointSystem('phone'));
$telecom->setValue(new FHIRString('021-1234567'));
$telecom->setUse(new FHIRContactPointUse('work'));

// Set telecom email
$telecomEmail = new FHIRContactPoint();
$telecomEmail->setSystem(new FHIRContactPointSystem('email'));
$telecomEmail->setValue(new FHIRString('hello@gmail.com'));
$telecomEmail->setUse(new FHIRContactPointUse('work'));

// Set telecom uri
$telecomUri = new FHIRContactPoint();
$telecomUri->setSystem(new FHIRContactPointSystem('url'));
$telecomUri->setValue(new FHIRString('http://www.kemkes.go.id'));
$telecomUri->setUse(new FHIRContactPointUse('work'));

// Set partOf
$partOf = new FHIRReference();
$partOf->setReference(new FHIRString('Organization/80216c56-cf06-4c79-8211-ba4e5200d323'));

// Set address
$address = new FHIRAddress();
$address->setUse(new FHIRAddressUse('work'));
$address->setType(new FHIRAddressType('both'));
$address->addLine(new FHIRString('Jl. Percetakan Negara No.29'));
$address->setCity(new FHIRString('Jakarta Pusat'));
$address->setDistrict(new FHIRString('Jakarta Pusat'));
$address->setPostalCode(new FHIRString('10560'));
$address->setCountry(new FHIRString('ID'));

// Set Organization
$organization->setActive($active);
$organization->addIdentifier($identifier);
$organization->addType($type);
$organization->setName($name);
$organization->addTelecom($telecom);
$organization->addTelecom($telecomEmail);
$organization->addTelecom($telecomUri);
$organization->setPartOf($partOf);
$organization->addAddress($address);

$organization = json_decode(json_encode($organization), true);
$organization['address'][0]['extension'] = (array) [
    [
        'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
        'extension' => [
            ['url' => 'province', 'valueCode' => '31'],
            ['url' => 'city', 'valueCode' => '3171'],
            ['url' => 'district', 'valueCode' => '317101'],
            ['url' => 'village', 'valueCode' => '31710101'],
        ],
    ],
];

// var_dump($organization);
$fp = fopen('demo/organization.json', 'w+');
fwrite($fp, json_encode($organization, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
fclose($fp);

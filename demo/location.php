<?php

require_once 'vendor/autoload.php';

// This file will generate location.json

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddress;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddressType;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRAddressUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRLocation\FHIRLocationPosition;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCodeableConcept;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPoint;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPointSystem;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRContactPointUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDecimal;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifier;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifierUse;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRLocationMode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRLocationStatus;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRLocation;

$location = new FHIRLocation();

// Set identifier
$identifier = new FHIRIdentifier();
$identifier->setValue('OPD_INT');
$identifier->setSystem(new FHIRUri('http://sys-ids.kemkes.go.id/location/80216c56-cf06-4c79-8211-ba4e5200d323', true));
$identifier->setUse(new FHIRIdentifierUse('official'));

// Set status
$status = new FHIRLocationStatus('active');

// Set Name
$name = new FHIRString('Rawat Jalan Poli Interna');

// Set description
$description = new FHIRString('Rawat Jalan Poli Interna di Gedung ABCD');

// Set mode
$mode = new FHIRLocationMode('instance');

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

// Set address
$address = new FHIRAddress();
$address->setUse(new FHIRAddressUse('work'));
$address->setType(new FHIRAddressType('both'));
$address->addLine(new FHIRString('Jl. Percetakan Negara No.29'));
$address->setCity(new FHIRString('Jakarta Pusat'));
$address->setDistrict(new FHIRString('Jakarta Pusat'));
$address->setPostalCode(new FHIRString('10560'));
$address->setCountry(new FHIRString('ID'));

// Set physicalType
$physicalType = new FHIRCodeableConcept();
$physicalType->addCoding(new FHIRCoding());
$physicalType->getCoding()[0]->setCode(new FHIRCode('ro'));
$physicalType->getCoding()[0]->setDisplay(new FHIRString('Room'));
$physicalType->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/location-physical-type', true));

// Set position
$position = new FHIRLocationPosition();
$position->setLongitude(new FHIRDecimal(106.856));
$position->setLatitude(new FHIRDecimal(-6.214));
$position->setAltitude(new FHIRDecimal(0));

// Set managing organization
$managingOrganization = new FHIRReference();
$managingOrganization->setReference(new FHIRString('Organization/80216c56-cf06-4c79-8211-ba4e5200d323'));

// Set Location
$location->addIdentifier($identifier);
$location->setStatus($status);
$location->setName($name);
$location->setDescription($description);
$location->setMode($mode);
$location->addTelecom($telecom);
$location->addTelecom($telecomEmail);
$location->addTelecom($telecomUri);
$location->setAddress($address);
$location->setPhysicalType($physicalType);
$location->setPosition($position);
$location->setManagingOrganization($managingOrganization);

$location = json_decode(json_encode($location), true);
$location['address']['extension'] = (array) [
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

$fp = fopen('demo/location.json', 'w+');
fwrite($fp, json_encode($location, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
fclose($fp);

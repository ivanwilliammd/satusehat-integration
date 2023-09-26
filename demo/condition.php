<?php

require_once 'vendor/autoload.php';

// This file will generate condition.json

use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRCondition;

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCodeableConcept;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDateTime;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;


$condition = new FHIRCondition();

// Set clinicalStatus
$clinicalStatus = new FHIRCodeableConcept();
$clinicalStatus->addCoding(new FHIRCoding());
$clinicalStatus->getCoding()[0]->setCode(new FHIRCode('active'));
$clinicalStatus->getCoding()[0]->setDisplay(new FHIRString('Active'));
$clinicalStatus->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/condition-clinical', true));

// Set category
$category = new FHIRCodeableConcept();
$category->addCoding(new FHIRCoding());
$category->getCoding()[0]->setCode(new FHIRCode('encounter-diagnosis'));
$category->getCoding()[0]->setDisplay(new FHIRString('Encounter Diagnosis'));
$category->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/condition-category', true));

// Set code
$code = new FHIRCodeableConcept();
$code->addCoding(new FHIRCoding());
$code->getCoding()[0]->setCode(new FHIRCode('J06'));
$code->getCoding()[0]->setDisplay(new FHIRString('Type 2 diabetes mellitus without complications'));
$code->getCoding()[0]->setSystem(new FHIRUri('http://hl7.org/fhir/sid/icd-10', true));

// Set subject
$subject = new FHIRReference();
$subject->setReference(new FHIRString('Patient/100000030009'));
$subject->setDisplay(new FHIRString('John Doe'));

// Set encounter
$encounter = new FHIRReference();
$encounter->setReference(new FHIRString('urn:uuid:0a26ca28-0ea3-486d-8fa9-6f9edd37e567'));
$encounter->setDisplay(new FHIRString('Encounter with patient John Doe'));

// Set onsetDateTime
$onsetDateTime = new FHIRDateTime('2023-09-16');

// Set recordedDate
$recordedDate = new FHIRDateTime('2023-09-16');


$condition->setClinicalStatus($clinicalStatus);
$condition->addCategory($category);
$condition->setCode($code);
$condition->setSubject($subject);
$condition->setEncounter($encounter);
$condition->setOnsetDateTime($onsetDateTime);
$condition->setRecordedDate($recordedDate);

$fp = fopen('demo/condition.json', 'w+');
fwrite($fp, json_encode($condition, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
fclose($fp);
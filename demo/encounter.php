<?php
require_once 'vendor/autoload.php';

// This file will generate encounter.json

use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIREncounter;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIREncounterStatus;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterStatusHistory;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterParticipant;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterDiagnosis;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterLocation;

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIREncounterLocationStatus;

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRPeriod;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCodeableConcept;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifier;

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDateTime;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRPositiveInt;

$encounter = new FHIREncounter();

// Set identifier
$identifier = new FHIRIdentifier();
$identifier->setValue('35664984812312391912312139');
$identifier->setSystem(new FHIRUri('http://sys-ids.kemkes.go.id/encounter/80216c56-cf06-4c79-8211-ba4e5200d323', true));

// Set Class
$class = new FHIRCoding();
$class->setCode(new FHIRCode('AMB'));
$class->setDisplay(new FHIRString('ambulatory'));
$class->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/v3-ActCode', true));

// Set subject
$subject = new FHIRReference();
$subject->setReference(new FHIRString('Patient/100000030009'));
$subject->setDisplay(new FHIRString('Budi Santoso'));

// Set participant
$participant = new FHIREncounterParticipant();
$participant->setIndividual(new FHIRReference());
$participant->getIndividual()->setReference(new FHIRString('Practitioner/N10000002'));
$participant->getIndividual()->setDisplay(new FHIRString('dr. Ivan William Harsono, MTI'));

$participant->addType(new FHIRCodeableConcept());
$participant->getType()[0]->addCoding(new FHIRCoding());
$participant->getType()[0]->getCoding()[0]->setCode(new FHIRCode('ATND'));
$participant->getType()[0]->getCoding()[0]->setDisplay(new FHIRString('attender'));
$participant->getType()[0]->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/v3-ParticipationType', true));

// Set period
$period = new FHIRPeriod();
$period->setStart(new FHIRDateTime());
$period->getStart()->setValue('2023-09-18T20:00:00+07:00');
$period->setEnd(new FHIRDateTime());
$period->getEnd()->setValue('2023-09-18T20:15:00+07:00');

// Set location
$location = new FHIREncounterLocation();
$location->setLocation(new FHIRReference());
$location->getLocation()->setReference(new FHIRString('Location/d485bb9d-0ec2-480d-8e38-5163b5c97181'));
$location->getLocation()->setDisplay(new FHIRString('Ruang Rawat Inap 1'));
// $location->setStatus(new FHIREncounterLocationStatus('active'));
// $location->setPeriod(new FHIRPeriod());
// $location->getPeriod()->setStart(new FHIRDateTime());
// $location->getPeriod()->getStart()->setValue('2023-09-18T20:00:00+07:00');
// $location->getPeriod()->setEnd(new FHIRDateTime());
// $location->getPeriod()->getEnd()->setValue('2023-09-18T20:15:00+07:00');

// Set diagnosis
$diagnosis = new FHIREncounterDiagnosis();
$diagnosis->setCondition(new FHIRReference());
$diagnosis->getCondition()->setReference(new FHIRString('urn:uuid:ba5a7dec-023f-45e1-adb9-1b9d71737a5f'));
$diagnosis->getCondition()->setDisplay(new FHIRString('Hipertensi'));
$diagnosis->setUse(new FHIRCodeableConcept());
$diagnosis->getUse()->addCoding(new FHIRCoding());
$diagnosis->getUse()->getCoding()[0]->setCode(new FHIRCode('DD'));
$diagnosis->getUse()->getCoding()[0]->setDisplay(new FHIRString('Discharge diagnosis'));
$diagnosis->getUse()->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/diagnosis-role', true));
$diagnosis->setRank(new FHIRPositiveInt('1'));

// Set arrived Status History
$arrived = new FHIREncounterStatusHistory();
$arrived->setStatus(new FHIREncounterStatus('arrived'));
$arrived->setPeriod(new FHIRPeriod());
$arrived->getPeriod()->setStart(new FHIRDateTime());
$arrived->getPeriod()->getStart()->setValue('2023-09-18T20:00:00+07:00');
$arrived->getPeriod()->setEnd(new FHIRDateTime());
$arrived->getPeriod()->getEnd()->setValue('2023-09-18T20:15:00+07:00');

// Set in-progress Status History
$inprogress = new FHIREncounterStatusHistory();
$inprogress->setStatus(new FHIREncounterStatus('in-progress'));
$inprogress->setPeriod(new FHIRPeriod());
$inprogress->getPeriod()->setStart(new FHIRDateTime());
$inprogress->getPeriod()->getStart()->setValue('2023-09-18T20:00:00+07:00');
$inprogress->getPeriod()->setEnd(new FHIRDateTime());
$inprogress->getPeriod()->getEnd()->setValue('2023-09-18T20:15:00+07:00');

// Set finished Status History
$finished = new FHIREncounterStatusHistory();
$finished->setStatus(new FHIREncounterStatus('finished'));
$finished->setPeriod(new FHIRPeriod());
$finished->getPeriod()->setStart(new FHIRDateTime());
$finished->getPeriod()->getStart()->setValue('2023-09-18T20:00:00+07:00');
$finished->getPeriod()->setEnd(new FHIRDateTime());
$finished->getPeriod()->getEnd()->setValue('2023-09-18T20:15:00+07:00');

// Set serviceProvider
$serviceProvider = new FHIRReference();
$serviceProvider->setReference(new FHIRString('Organization/80216c56-cf06-4c79-8211-ba4e5200d323'));

// Set Encounter
$encounter->addIdentifier($identifier);
$encounter->setStatus(new FHIREncounterStatus('finished'));
$encounter->setClass($class);
$encounter->setSubject($subject);
$encounter->addParticipant($participant);
$encounter->setPeriod($period);
$encounter->addLocation($location);

$encounter->addDiagnosis($diagnosis);

$encounter->addStatusHistory($arrived);
$encounter->addStatusHistory($inprogress);
$encounter->addStatusHistory($finished);

$encounter->setServiceProvider($serviceProvider);

$fp = fopen('demo/encounter.json', 'w+');
fwrite($fp, json_encode($encounter, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
fclose($fp);
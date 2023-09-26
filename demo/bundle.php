<?php

require_once 'vendor/autoload.php';

// This file will create bundle.json from encounter.json, and condition.json

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRBundle\FHIRBundleEntry;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIRBundle\FHIRBundleRequest;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBundleType;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRHTTPVerb;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRBundle;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIRCondition;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIREncounter;

$encounter_path = 'demo/encounter.json';
$condition_path = 'demo/condition.json';

$encounter_json = file_get_contents($encounter_path);
$condition_json = file_get_contents($condition_path);

$encounter = new FHIREncounter(json_decode($encounter_json, true));
$condition = new FHIRCondition(json_decode($condition_json, true));

$bundle = new FHIRBundle();
$bundle->setType(new FHIRBundleType('transaction'));
$bundle->addEntry(new FHIRBundleEntry());
$bundle->getEntry()[0]->setResource($encounter);
$bundle->getEntry()[0]->setFullUrl(new FHIRUri('urn:uuid:0a26ca28-0ea3-486d-8fa9-6f9edd37e567'));
$bundle->getEntry()[0]->setRequest(new FHIRBundleRequest);
$bundle->getEntry()[0]->getRequest()->setMethod(new FHIRHTTPVerb('POST'));
$bundle->getEntry()[0]->getRequest()->setUrl(new FHIRUri('Encounter'));

$bundle->addEntry(new FHIRBundleEntry());
$bundle->getEntry()[1]->setResource($condition);
$bundle->getEntry()[1]->setFullUrl(new FHIRUri('urn:uuid:ba5a7dec-023f-45e1-adb9-1b9d71737a5f'));
$bundle->getEntry()[1]->setRequest(new FHIRBundleRequest);
$bundle->getEntry()[1]->getRequest()->setMethod(new FHIRHTTPVerb('POST'));
$bundle->getEntry()[1]->getRequest()->setUrl(new FHIRUri('Condition'));

$fp = fopen('demo/bundle.json', 'w+');
fwrite($fp, json_encode($bundle, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
fclose($fp);

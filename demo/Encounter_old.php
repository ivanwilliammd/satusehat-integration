<?php

namespace Satusehat\FHIR;

/**
 * Satusehat\FHIR\Encounter.
 *
 * @property string $registration_id
 * @property string $consultation_method
 * @property string $subject_id
 * @property string $subject_name
 * @property array $doctor
 * @property array $location
 * @property string $discharge
 * @property array $encounter
 * @property \Illuminate\Support\Carbon|null $arrived
 * @property \Illuminate\Support\Carbon|null $inprogress
 * @property \Illuminate\Support\Carbon|null $finished
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterDiagnosis;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterLocation;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterParticipant;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRBackboneElement\FHIREncounter\FHIREncounterStatusHistory;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCode;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCodeableConcept;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRCoding;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRDateTime;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIREncounterLocationStatus;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIREncounterStatus;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRIdentifier;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRPeriod;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRPositiveInt;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRReference;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRString;
use DCarbone\PHPFHIRGenerated\R4\FHIRElement\FHIRUri;
use DCarbone\PHPFHIRGenerated\R4\FHIRResource\FHIRDomainResource\FHIREncounter;
use Satusehat\Integration\OAuth2Client;

class Encounter extends OAuth2Client
{
    public function __construct($data)
    {

        $encounter = new FHIREncounter();

        // Set identifier
        if (array_key_exists('registration_id', $data)) {
            $identifier = new FHIRIdentifier();
            $identifier->setValue($data['registration_id']);
            $identifier->setSystem(new FHIRUri('http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id, true));
            $encounter->addIdentifier($identifier);
        } else {
            return 'registration_id is required';
        }

        // Set Class
        if (array_key_exists('consultation_method', $data)) {
            switch ($data['consultation_method']) {
                case 'RAJAL':
                    $class_code = 'AMB';
                    $class_display = 'ambulatory';
                    break;
                case 'IGD':
                    $class_code = 'EMER';
                    $class_display = 'emergency';
                    break;
                case 'RANAP':
                    $class_code = 'IMP';
                    $class_display = 'inpatient encounter';
                    break;
                case 'HOMECARE':
                    $class_code = 'HH';
                    $class_display = 'home health';
                    break;
                case 'TELECONSULTATION':
                    $class_code = 'TELE';
                    $class_display = 'teleconsultation';
                    break;
                default:
                    return 'consultation_method is invalid (Choose RAJAL / IGD / RANAP/ HOMECARE / TELECONSULTATION)';
            }

            $class = new FHIRCoding();
            $class->setCode(new FHIRCode($class_code));
            $class->setDisplay(new FHIRString($class_display));
            $class->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/v3-ActCode', true));
            $encounter->setClass($class);
        } else {
            return 'consultation_method is required';
        }

        // Set subject
        if (array_key_exists('subject_id', $data) && array_key_exists('subject_name', $data)) {
            $subject = new FHIRReference();
            $subject->setReference(new FHIRString('Patient/'.$data['subject_id']));
            $subject->setDisplay(new FHIRString($data['subject_name']));
            $encounter->setSubject($subject);
        } else {
            return 'subject_id and subject_name is required';
        }

        // Set doctor
        if (array_key_exists('doctor', $data)) {
            foreach ($data['doctor'] as $key => $value) {
                $participant = new FHIREncounterParticipant();
                $participant->setIndividual(new FHIRReference());
                $participant->getIndividual()->setReference(new FHIRString('Practitioner/'.$key));
                $participant->getIndividual()->setDisplay(new FHIRString($value));

                $participant->addType(new FHIRCodeableConcept());
                $participant->getType()[0]->addCoding(new FHIRCoding());
                $participant->getType()[0]->getCoding()[0]->setCode(new FHIRCode('ATND'));
                $participant->getType()[0]->getCoding()[0]->setDisplay(new FHIRString('attender'));
                $participant->getType()[0]->getCoding()[0]->setSystem(new FHIRUri('http://terminology.hl7.org/CodeSystem/v3-ParticipationType', true));

                $encounter->addParticipant($participant);
            }
        } else {
            return "doctor in array format of array key-value ['id' => 'name'] is required";
        }

        // Set location
        if (array_key_exist('location', $data)) {
            foreach ($data['location'] as $key => $value) {
                $location = new FHIREncounterLocation();
                $location->setLocation(new FHIRReference());
                $location->getLocation()->setReference(new FHIRString('Location/'.$key));
                $location->getLocation()->setDisplay(new FHIRString($value));
                $location->setStatus(new FHIREncounterLocationStatus('active'));
                // $location->setPeriod(new FHIRPeriod());
                // $location->getPeriod()->setStart(new FHIRDateTime());
                // $location->getPeriod()->getStart()->setValue('2023-09-18T20:00:00+07:00');
                // $location->getPeriod()->setEnd(new FHIRDateTime());
                // $location->getPeriod()->getEnd()->setValue('2023-09-18T20:15:00+07:00');

                $encounter->addLocation($location);
            }
        } else {
            return "location in array format of array key-value ['id' => 'name'] is required";
        }

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

        // Set period
        $period = new FHIRPeriod();
        $period->setStart(new FHIRDateTime());
        $period->getStart()->setValue('2023-09-18T20:00:00+07:00');
        $period->setEnd(new FHIRDateTime());
        $period->getEnd()->setValue('2023-09-18T20:15:00+07:00');

        // Set serviceProvider
        $serviceProvider = new FHIRReference();
        $serviceProvider->setReference(new FHIRString('Organization/80216c56-cf06-4c79-8211-ba4e5200d323'));

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
    }
}

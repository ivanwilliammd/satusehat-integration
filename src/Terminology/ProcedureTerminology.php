<?php

namespace Satusehat\Integration\Terminology;

class ProcedureTerminology
{
    /**
     * This value set is used in the following places:
     *
     * Resource: ServiceRequest.code (CodeableConcept / Example)
     * Profile: ServiceRequest-Genetics: ServiceRequest.code (CodeableConcept / Example)
     * Resource: ActivityDefinition.code (CodeableConcept / Example)
     * Profile: ShareableActivityDefinition: ActivityDefinition.code (CodeableConcept / Example)
     * Resource: CarePlan.activity.detail.code (CodeableConcept / Example)
     * Resource: Procedure.code (CodeableConcept / Example)
     * Resource: BiologicallyDerivedProduct.processing.procedure (CodeableConcept / Example)
     * 
     * Include codes from http://snomed.info/sct  where concept is-a 71388002 (Procedure)
     */

    public array $procedure_code = array(
        "inpatient_care_management" => array("system" => "http://snomed.info/sct", "code" => "737481003", "display" => "Inpatient care management"),
    );
}

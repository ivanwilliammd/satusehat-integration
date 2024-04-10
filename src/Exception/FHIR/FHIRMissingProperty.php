<?php

namespace Satusehat\Integration\Exception\FHIR;

class FHIRMissingProperty extends FHIRException
{
    public function __construct($message)
    {
        $message = 'FHIR Missing Property: ' . $message;

        parent::__construct($message);
    }
}

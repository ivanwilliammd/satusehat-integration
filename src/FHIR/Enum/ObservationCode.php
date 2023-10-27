<?php

namespace Satusehat\Integration\FHIR\Enum;

enum ObservationCode: string
{
    case Sistole = "8480-6";
    case Diastole = "8462-4";
    case HeartRate = "8867-4";
    case Temperature = "8310-5";
    case RespiratoryRate = "9279-1";
}

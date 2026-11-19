<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/**
 * The two profiles that cover most Indonesian hospital traffic: admission/discharge/transfer and
 * order/result. Anything else starts from one of these and is adjusted for the vendor.
 */
final class Profiles
{
    public static function adtA01(): Profile
    {
        return Profile::fromArray([
            'name' => 'ADT_A01',
            'message_type' => 'ADT',
            'trigger' => 'A01',
            'required_segments' => ['MSH', 'EVN', 'PID', 'PV1'],
            'required_fields' => [
                'MSH.10' => 'Message control ID',
                'PID.3' => 'Patient identifier',
                'PID.5.1' => 'Patient family name',
                'PV1.2' => 'Patient class (I/O/E)',
            ],
            'allowed_values' => [
                'PID.8.1' => ['F', 'M', 'O', 'U', 'A', 'N'],
                'PV1.2' => ['I', 'O', 'E', 'P', 'R', 'B', 'C', 'N'],
            ],
        ]);
    }

    /** A08 is the "update patient information" trigger; PV1 is often absent, so it is not required. */
    public static function adtA08(): Profile
    {
        return Profile::fromArray([
            'name' => 'ADT_A08',
            'message_type' => 'ADT',
            'trigger' => 'A08',
            'required_segments' => ['MSH', 'EVN', 'PID'],
            'required_fields' => [
                'MSH.10' => 'Message control ID',
                'PID.3' => 'Patient identifier',
                'PID.5.1' => 'Patient family name',
            ],
            'allowed_values' => [
                'PID.8.1' => ['F', 'M', 'O', 'U', 'A', 'N'],
            ],
        ]);
    }

    public static function oruR01(): Profile
    {
        return Profile::fromArray([
            'name' => 'ORU_R01',
            'message_type' => 'ORU',
            'trigger' => 'R01',
            'required_segments' => ['MSH', 'PID', 'OBR', 'OBX'],
            'required_fields' => [
                'MSH.10' => 'Message control ID',
                'PID.3' => 'Patient identifier',
                'OBR.4.1' => 'Universal service identifier',
                'OBX.5' => 'Observation value',
            ],
            'allowed_values' => [
                'OBX.11' => ['F', 'P', 'C', 'X'],
            ],
        ]);
    }
}

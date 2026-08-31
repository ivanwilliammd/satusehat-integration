<?php

declare(strict_types=1);

namespace Satusehat\Integration\Terminology;

use Satusehat\Integration\DataType\CodeableConcept;

class Resolver
{
    public static function resolve(mixed $codeOrConcept): array|CodeableConcept
    {
        if ($codeOrConcept instanceof CodeableConcept) {
            return $codeOrConcept;
        }

        if (is_array($codeOrConcept)) {
            return array_map(fn($item) => self::resolve($item), $codeOrConcept);
        }

        if (is_string($codeOrConcept)) {
            // Check if format is "System:Code" or "Prefix:Code" e.g. "ICD10:A00" or "LOINC:2951-2"
            if (str_contains($codeOrConcept, ':')) {
                [$prefix, $code] = explode(':', $codeOrConcept, 2);
                $system = match(strtoupper($prefix)) {
                    'ICD10' => 'http://hl7.org/fhir/sid/icd-10',
                    'ICD9' => 'http://hl7.org/fhir/sid/icd-9-cm',
                    'LOINC' => 'http://loinc.org',
                    'SNOMED' => 'http://snomed.info/sct',
                    'CVX' => 'http://hl7.org/fhir/sid/cvx',
                    'UCUM' => 'http://unitsofmeasure.org',
                    default => $prefix
                };
                return new CodeableConcept([
                    'coding' => [[
                        'system' => $system,
                        'code' => trim($code),
                        'display' => trim($code)
                    ]],
                    'text' => trim($code)
                ]);
            }

            return new CodeableConcept([
                'text' => $codeOrConcept
            ]);
        }

        return $codeOrConcept;
    }
}

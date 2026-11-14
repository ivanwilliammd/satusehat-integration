<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderImmunizationEvaluation;

/**
 * One file per resource, so a failure names the resource instead of hiding in an aggregate suite.
 * Asserts the builder exists and emits the expected resourceType; field-level assertions grow as
 * golden payloads land in satusehat-conformance (golden/builder/ImmunizationEvaluation.json).
 */
class PayloadBuilderImmunizationEvaluationTest extends TestCase
{
    public function test_membangun_payload_immunizationevaluation_dengan_resource_type_yang_benar(): void
    {
        $payload = (new PayloadBuilderImmunizationEvaluation())->build();

        $this->assertIsArray($payload);
        $this->assertSame('ImmunizationEvaluation', $payload['resourceType']);
    }
}

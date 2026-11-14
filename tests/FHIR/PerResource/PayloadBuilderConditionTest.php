<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderCondition;

/**
 * One file per resource, so a failure names the resource instead of hiding in an aggregate suite.
 * Asserts the builder exists and emits the expected resourceType; field-level assertions grow as
 * golden payloads land in satusehat-conformance (golden/builder/Condition.json).
 */
class PayloadBuilderConditionTest extends TestCase
{
    public function test_membangun_payload_condition_dengan_resource_type_yang_benar(): void
    {
        $payload = (new PayloadBuilderCondition())->build();

        $this->assertIsArray($payload);
        $this->assertSame('Condition', $payload['resourceType']);
    }
}

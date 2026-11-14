<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderEventDefinition;

/**
 * One file per resource, so a failure names the resource instead of hiding in an aggregate suite.
 * Asserts the builder exists and emits the expected resourceType; field-level assertions grow as
 * golden payloads land in satusehat-conformance (golden/builder/EventDefinition.json).
 */
class PayloadBuilderEventDefinitionTest extends TestCase
{
    public function test_membangun_payload_eventdefinition_dengan_resource_type_yang_benar(): void
    {
        $payload = (new PayloadBuilderEventDefinition())->build();

        $this->assertIsArray($payload);
        $this->assertSame('EventDefinition', $payload['resourceType']);
    }
}

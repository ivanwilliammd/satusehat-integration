<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderSupplyRequest;

/**
 * One file per resource, so a failure names the resource instead of hiding in an aggregate suite.
 * Asserts the builder exists and emits the expected resourceType; field-level assertions grow as
 * golden payloads land in satusehat-conformance (golden/builder/SupplyRequest.json).
 */
class PayloadBuilderSupplyRequestTest extends TestCase
{
    public function test_membangun_payload_supplyrequest_dengan_resource_type_yang_benar(): void
    {
        $payload = (new PayloadBuilderSupplyRequest())->build();

        $this->assertIsArray($payload);
        $this->assertSame('SupplyRequest', $payload['resourceType']);
    }
}

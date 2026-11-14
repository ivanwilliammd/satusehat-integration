<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderSubstanceNucleicAcid;

/**
 * One file per resource, so a failure names the resource instead of hiding in an aggregate suite.
 * Asserts the builder exists and emits the expected resourceType; field-level assertions grow as
 * golden payloads land in satusehat-conformance (golden/builder/SubstanceNucleicAcid.json).
 */
class PayloadBuilderSubstanceNucleicAcidTest extends TestCase
{
    public function test_membangun_payload_substancenucleicacid_dengan_resource_type_yang_benar(): void
    {
        $payload = (new PayloadBuilderSubstanceNucleicAcid())->build();

        $this->assertIsArray($payload);
        $this->assertSame('SubstanceNucleicAcid', $payload['resourceType']);
    }
}

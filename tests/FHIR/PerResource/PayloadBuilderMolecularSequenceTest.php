<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\PerResource;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Satusehat\Integration\Builder\PayloadBuilderMolecularSequence;

/**
 * One file per resource. MolecularSequence cannot be built empty: coordinateSystem is required by
 * the SATUSEHAT profile, so this test pins that contract instead of forcing an empty payload.
 */
class PayloadBuilderMolecularSequenceTest extends TestCase
{
    public function test_menolak_build_tanpa_coordinate_system(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('coordinateSystem');

        (new PayloadBuilderMolecularSequence())->build();
    }
}

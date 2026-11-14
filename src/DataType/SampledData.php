<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

/**
 * FHIR R4 SampledData — periodic sampled values (e.g. waveforms, continuous monitoring).
 *
 * @property Quantity|null $origin
 * @property float|null $period
 * @property float|null $factor
 * @property float|null $lowerLimit
 * @property float|null $upperLimit
 * @property int|null $dimensions
 * @property string|null $data
 */
class SampledData extends DataType
{
    public ?Quantity $origin = null;
    public ?float $period = null;
    public ?float $factor = null;
    public ?float $lowerLimit = null;
    public ?float $upperLimit = null;
    public ?int $dimensions = null;
    public ?string $data = null;

    public function __construct(
        ?Quantity $origin = null,
        ?float $period = null,
        ?float $factor = null,
        ?float $lowerLimit = null,
        ?float $upperLimit = null,
        ?int $dimensions = null,
        ?string $data = null
    ) {
        $this->origin = $origin;
        $this->period = $period;
        $this->factor = $factor;
        $this->lowerLimit = $lowerLimit;
        $this->upperLimit = $upperLimit;
        $this->dimensions = $dimensions;
        $this->data = $data;
    }
}

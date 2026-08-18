<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class TimingRepeat extends DataType
{
    /** @var Range|Period|Duration|null */
    public $bounds = null;
    public ?int $count = null;
    public ?int $countMax = null;
    public ?float $duration = null;
    public ?float $durationMax = null;
    public ?string $durationUnit = null;
    public ?int $frequency = null;
    public ?int $frequencyMax = null;
    public ?float $period = null;
    public ?float $periodMax = null;
    public ?string $periodUnit = null;
    /** @var string[] */
    public array $dayOfWeek = [];
    /** @var string[] */
    public array $timeOfDay = [];
    /** @var string[] */
    public array $when = [];
    public ?int $offset = null;

    public function setBounds($bounds): self
    {
        $this->bounds = $bounds;
        return $this;
    }

    public function addDayOfWeek(string $day): self
    {
        $this->dayOfWeek[] = $day;
        return $this;
    }

    public function addTimeOfDay(string $time): self
    {
        $this->timeOfDay[] = $time;
        return $this;
    }

    public function addWhen(string $when): self
    {
        $this->when[] = $when;
        return $this;
    }
}

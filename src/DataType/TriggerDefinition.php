<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class TriggerDefinition extends DataType
{
    /**
     * named-event|periodic|data-accessed|data-changed|data-added
     */
    public ?string $type = null;
    public ?string $eventName = null;
    /** @var Timing|Period|string|null */
    public $eventTiming = null;
    public ?DataRequirement $eventData = null;

    public function __construct(?string $type = null)
    {
        $this->type = $type;
    }

    public function setEventTiming($eventTiming): self
    {
        $this->eventTiming = $eventTiming;
        return $this;
    }

    public function setEventData(DataRequirement $eventData): self
    {
        $this->eventData = $eventData;
        return $this;
    }
}

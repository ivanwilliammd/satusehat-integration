<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/** Outcome of one logical send: the application verdict, the transport cost, and how many tries. */
final class SendResult
{
    public function __construct(
        public string $code,
        public string $controlId,
        public string $text,
        public ?Message $ack,
        public int $attempts,
        public float $latencyMs
    ) {
    }

    public function accepted(): bool
    {
        return $this->code === Ack::ACCEPT;
    }

    public function rejected(): bool
    {
        return $this->code === Ack::REJECT;
    }
}

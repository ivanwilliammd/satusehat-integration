<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/** One finding from Profile::validate(); severity is what decides whether a message may be accepted. */
final class Issue
{
    public const ERROR = 'error';
    public const WARNING = 'warning';

    public function __construct(
        public string $severity,
        public string $path,
        public string $message
    ) {
    }

    public function isError(): bool
    {
        return $this->severity === self::ERROR;
    }

    /** @return array{severity:string,path:string,message:string} */
    public function toArray(): array
    {
        return ['severity' => $this->severity, 'path' => $this->path, 'message' => $this->message];
    }

    public function __toString(): string
    {
        return strtoupper($this->severity) . ' ' . $this->path . ': ' . $this->message;
    }
}

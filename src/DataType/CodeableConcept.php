<?php

declare(strict_types=1);

namespace Satusehat\Integration\DataType;

class CodeableConcept extends DataType
{
    /** @var Coding[] */
    public array $coding = [];
    public ?string $text = null;

    public function __construct(?string $text = null, Coding ...$coding)
    {
        $this->text = $this->str($text);
        $this->coding = $coding;
    }

    public function addCoding(Coding $coding): self
    {
        $this->coding[] = $coding;
        return $this;
    }

    public function toArray(): array
    {
        $data = [
            'coding' => array_map(fn(Coding $c) => $c->toArray(), $this->coding),
            'text' => $this->text,
        ];

        if (empty($data['coding'])) {
            unset($data['coding']);
        }

        return array_filter($data, fn($v) => $v !== null && $v !== []);
    }
}

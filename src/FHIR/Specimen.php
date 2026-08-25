<?php

declare(strict_types=1);

namespace Satusehat\Integration\FHIR;

class Specimen extends Element
{
    public array $data = ['resourceType' => 'Specimen'];

    public function setStatus(string $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setType(array $coding): self
    {
        $this->data['type']['coding'] = $coding;
        return $this;
    }

    public function setSubject(array $reference): self
    {
        $this->data['subject'] = $reference;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

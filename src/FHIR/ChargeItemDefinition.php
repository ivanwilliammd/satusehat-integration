<?php

namespace Satusehat\Integration\FHIR;

/**
 * ChargeItemDefinition FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/chargeitemdefinition.html
 */
class ChargeItemDefinition
{
    public array $data = ['resourceType' => 'ChargeItemDefinition'];

    public function setId(string $id): self
    {
        $this->data['id'] = $id;
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->data['identifier'][] = ['system' => $system, 'value' => $value];
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->data['url'] = $url;
        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->data['version'] = $version;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->data['name'] = $name;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->data['date'] = $date;
        return $this;
    }

    public function setPublisher(string $publisher): self
    {
        $this->data['publisher'] = $publisher;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->data['description'] = $description;
        return $this;
    }

    public function setCode(string $system, string $code, string $display = ''): self
    {
        $this->data['code'] = [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ];
        return $this;
    }

    public function addInstance(
        ?string $reference = null,
        ?string $display = null,
        ?string $timingDateTime = null
    ): self {
        $instance = [];
        if ($reference) {
            $instance['reference'] = ['reference' => $reference];
        }
        if ($display) {
            $instance['display'] = $display;
        }
        if ($timingDateTime) {
            $instance['timingDateTime'] = $timingDateTime;
        }
        $this->data['instance'][] = $instance;
        return $this;
    }

    public function addProperty(
        string $codeSystem,
        string $codeValue,
        string $valueString
    ): self {
        $this->data['property'][] = [
            'code' => ['coding' => [['system' => $codeSystem, 'code' => $codeValue]]],
            'valueString' => $valueString,
        ];
        return $this;
    }

    public function addApplies(string $appliesType, string $value): self
    {
        $key = 'applies' . ucfirst($appliesType);
        $this->data[$key] = $value;
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ChargeItemDefinition FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/chargeitemdefinition.html
 */
class PayloadBuilderChargeItemDefinition extends Builder
{
    protected string $resourceType = 'ChargeItemDefinition';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', ['system' => $system, 'value' => $value]);
        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->set('url', $url);
        return $this;
    }

    public function setVersion(string $version): self
    {
        $this->set('version', $version);
        return $this;
    }

    public function setName(string $name): self
    {
        $this->set('name', $name);
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->set('title', $title);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->set('date', $date);
        return $this;
    }

    public function setPublisher(string $publisher): self
    {
        $this->set('publisher', $publisher);
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->set('description', $description);
        return $this;
    }

    public function setCode(string $system, string $code, string $display = ''): self
    {
        $this->set('code', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]],
        ]);
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
        $this->push('instance', $instance);
        return $this;
    }

    public function addProperty(
        string $codeSystem,
        string $codeValue,
        string $valueString
    ): self {
        $this->push('property', [
            'code' => ['coding' => [['system' => $codeSystem, 'code' => $codeValue]]],
            'valueString' => $valueString,
        ]);
        return $this;
    }

    public function addApplies(string $appliesType, string $value): self
    {
        $this->set('applies' . ucfirst($appliesType), $value);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

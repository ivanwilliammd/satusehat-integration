<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ListResource FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/list.html
 */
class PayloadBuilderListResource extends Builder
{
    protected string $resourceType = 'List';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value, ?string $use = null): self
    {
        $ident = ['system' => $system, 'value' => $value];
        if ($use !== null) $ident['use'] = $use;
        $this->add('identifier', $ident);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function setMode(string $mode): self
    {
        $this->set('mode', $mode);
        return $this;
    }

    public function setCode(string $system, string $code, string $display): self
    {
        $this->set('code', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]]
        ]);
        return $this;
    }

    public function setSubject(string $reference, ?string $display = null): self
    {
        $subject = ['reference' => $reference];
        if ($display !== null) $subject['display'] = $display;
        $this->set('subject', $subject);
        return $this;
    }

    public function setDate(string $date): self
    {
        $this->set('date', $date);
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->set('title', $title);
        return $this;
    }
}

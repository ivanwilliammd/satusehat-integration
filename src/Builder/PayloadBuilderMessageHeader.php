<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * MessageHeader FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/messageheader.html
 */
class PayloadBuilderMessageHeader extends Builder
{
    protected string $resourceType = 'MessageHeader';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    /**
     * @deprecated menulis `identifier` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function addIdentifier(string $system, string $value, ?string $use = null, ?string $typeCode = null, ?string $typeDisplay = null): self
    {
        $ident = ['system' => $system, 'value' => $value];
        if ($use !== null) $ident['use'] = $use;
        if ($typeCode !== null) {
            $ident['type'] = [
                'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => $typeCode, 'display' => $typeDisplay ?? $typeCode]]
            ];
        }
        $this->push('identifier', $ident);
        return $this;
    }

    /**
     * @deprecated menulis `status` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    /**
     * @deprecated menulis `code` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function setCode(string $system, string $code, string $display): self
    {
        $this->set('code', [
            'coding' => [['system' => $system, 'code' => $code, 'display' => $display]]
        ]);
        return $this;
    }

    /**
     * @deprecated menulis `subject` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function setSubject(string $reference, ?string $display = null): self
    {
        $subject = ['reference' => $reference];
        if ($display !== null) $subject['display'] = $display;
        $this->set('subject', $subject);
        return $this;
    }

    /**
     * @deprecated menulis `encounter` padahal resource ini tidak
     *             punya elemen tersebut di FHIR R4 — payload-nya akan ditolak server.
     */
    public function setEncounter(string $reference): self
    {
        $this->set('encounter', ['reference' => $reference]);
        return $this;
    }
}
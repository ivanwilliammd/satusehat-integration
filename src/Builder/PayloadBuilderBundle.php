<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * Bundle FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/bundle.html
 */
class PayloadBuilderBundle extends Builder
{
    protected string $resourceType = 'Bundle';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function setType(string $type): self
    {
        $this->set('type', $type);
        return $this;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->set('timestamp', $timestamp);
        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->set('total', $total);
        return $this;
    }

    public function addLink(string $relation, string $url): self
    {
        $this->push('link', [
            'relation' => $relation,
            'url' => $url,
        ]);
        return $this;
    }

    public function addEntry(array $resource, ?string $fullUrl = null): self
    {
        $entry = ['resource' => $resource];

        if ($fullUrl !== null) {
            $entry['fullUrl'] = $fullUrl;
        }

        $this->push('entry', $entry);
        return $this;
    }

    public function addEntryWithRequest(
        array $resource,
        string $fullUrl,
        string $method,
        string $url
    ): self {
        $entry = [
            'fullUrl' => $fullUrl,
            'resource' => $resource,
            'request' => [
                'method' => $method,
                'url' => $url,
            ],
        ];

        $this->push('entry', $entry);
        return $this;
    }

    public function addEntryWithResponse(
        array $resource,
        string $fullUrl,
        string $status,
        ?string $etag = null,
        ?string $lastModified = null
    ): self {
        $entry = [
            'fullUrl' => $fullUrl,
            'resource' => $resource,
            'response' => [
                'status' => $status,
            ],
        ];

        if ($etag !== null) {
            $entry['response']['etag'] = $etag;
        }

        if ($lastModified !== null) {
            $entry['response']['lastModified'] = $lastModified;
        }

        $this->push('entry', $entry);
        return $this;
    }

    public function setSignature(
        string $type,
        string $sigFormat,
        string $sigBlob,
        ?string $sigAlg = null,
        ?string $sigCreated = null,
        ?string $sigWho = null,
        ?string $sigOnBehalfOf = null
    ): self {
        $signature = [
            'type' => [
                ['code' => $type],
            ],
            'format' => $sigFormat,
            'data' => $sigBlob,
        ];

        if ($sigAlg !== null) {
            $signature['sigAlg'] = $sigAlg;
        }

        if ($sigCreated !== null) {
            $signature['created'] = $sigCreated;
        }

        if ($sigWho !== null) {
            $signature['who'] = ['reference' => $sigWho];
        }

        if ($sigOnBehalfOf !== null) {
            $signature['onBehalfOf'] = ['reference' => $sigOnBehalfOf];
        }

        $this->set('signature', $signature);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}

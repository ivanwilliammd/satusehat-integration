<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use DateTime;
use DateTimeInterface;

/**
 * Bundle FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/bundle.html
 *
 * Supported types: document | batch | transaction | history-collection |
 *                  history-document | history-feed | searchset | collection | feed | wrapper
 */
class PayloadBuilderBundle extends Builder
{
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_BATCH = 'batch';
    public const TYPE_TRANSACTION = 'transaction';
    public const TYPE_HISTORY_COLLECTION = 'history-collection';
    public const TYPE_HISTORY_DOCUMENT = 'history-document';
    public const TYPE_HISTORY_FEED = 'history-feed';
    public const TYPE_SEARCHSET = 'searchset';
    public const TYPE_COLLECTION = 'collection';
    public const TYPE_FEED = 'feed';
    public const TYPE_WRAPPER = 'wrapper';

    /** @var array<string, string> */
    public const VALID_TYPES = [
        'document' => self::TYPE_DOCUMENT,
        'batch' => self::TYPE_BATCH,
        'transaction' => self::TYPE_TRANSACTION,
        'history-collection' => self::TYPE_HISTORY_COLLECTION,
        'history-document' => self::TYPE_HISTORY_DOCUMENT,
        'history-feed' => self::TYPE_HISTORY_FEED,
        'searchset' => self::TYPE_SEARCHSET,
        'collection' => self::TYPE_COLLECTION,
        'feed' => self::TYPE_FEED,
        'wrapper' => self::TYPE_WRAPPER,
    ];

    protected string $resourceType = 'Bundle';

    private bool $timestampAutoSet = true;

    public function __construct(?string $type = null)
    {
        $this->data['resourceType'] = $this->resourceType;

        if ($type !== null) {
            $this->setType($type);
        }

        // Auto-set ISO timestamp unless overridden
        if ($this->timestampAutoSet) {
            $this->setTimestamp((new DateTime())->format(DateTimeInterface::ATOM));
        }
    }

    /**
     * Disable auto-timestamp. Call before chaining if you want manual control.
     */
    public function noAutoTimestamp(): self
    {
        $this->timestampAutoSet = false;
        return $this;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    /**
     * @param string $type One of Bundle::TYPE_*
     * @throws \InvalidArgumentException
     */
    public function setType(string $type): self
    {
        if (!isset(self::VALID_TYPES[$type])) {
            throw new \InvalidArgumentException(
                "Invalid Bundle type '{$type}'. Valid: " . implode(', ', array_keys(self::VALID_TYPES))
            );
        }
        $this->set('type', $type);
        return $this;
    }

    public function setTimestamp(string|DateTimeInterface $timestamp): self
    {
        $this->timestampAutoSet = false;
        $this->set('timestamp', $timestamp instanceof DateTimeInterface
            ? $timestamp->format(DateTimeInterface::ATOM)
            : $timestamp);
        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->set('total', $total);
        return $this;
    }

    public function setMeta(array $meta): self
    {
        $this->set('meta', $meta);
        return $this;
    }

    /**
     * Add a link element (e.g. for searchset pagination).
     */
    public function addLink(string $relation, string $url): self
    {
        $this->push('link', [
            'relation' => $relation,
            'url' => $url,
        ]);
        return $this;
    }

    /**
     * Pagination helpers for searchset bundles.
     */
    public function addPaginationLinks(string $baseUrl, int $page, int $count, int $total): self
    {
        $offset = ($page - 1) * $count;
        $params = "offset={$offset}&count={$count}";

        $this->addLink('self', "{$baseUrl}?{$params}")
             ->addLink('first', "{$baseUrl}?offset=0&count={$count}")
             ->addLink('last', "{$baseUrl}?offset=" . (floor($total / $count) * $count) . "&count={$count}");

        if ($page > 1) {
            $prevOffset = ($page - 2) * $count;
            $this->addLink('previous', "{$baseUrl}?offset={$prevOffset}&count={$count}");
        }

        if (($offset + $count) < $total) {
            $nextOffset = $page * $count;
            $this->addLink('next', "{$baseUrl}?offset={$nextOffset}&count={$count}");
        }

        return $this;
    }

    // -------------------------------------------------------------------------
    // Entry builders
    // -------------------------------------------------------------------------

    /**
     * Simple entry: resource + optional fullUrl.
     */
    public function addEntry(array $resource, ?string $fullUrl = null): self
    {
        $entry = ['resource' => $resource];

        if ($fullUrl !== null) {
            $entry['fullUrl'] = $fullUrl;
        }

        $this->push('entry', $entry);
        return $this;
    }

    /**
     * Search result entry with score + relevance.
     */
    public function addSearchEntry(
        array $resource,
        ?string $fullUrl = null,
        ?float $score = null,
        ?string $searchMode = null
    ): self {
        $entry = ['resource' => $resource];

        if ($fullUrl !== null) {
            $entry['fullUrl'] = $fullUrl;
        }

        $search = [];
        if ($searchMode !== null) {
            $search['mode'] = $searchMode; // match | include | outcome
        }
        if ($score !== null) {
            $search['score'] = $score;
        }
        if ($search !== []) {
            $entry['search'] = $search;
        }

        $this->push('entry', $entry);
        return $this;
    }

    /**
     * Batch/transaction entry with request headers.
     */
    public function addBatchEntry(
        array $resource,
        string $fullUrl,
        string $method, // GET | POST | PUT | PATCH | DELETE
        string $url,
        ?string $ifMatch = null,
        ?string $IfNoneMatch = null,
        ?string $IfNoneExist = null
    ): self {
        $request = [
            'method' => $method,
            'url' => $url,
        ];

        if ($IfNoneMatch !== null) {
            $request['ifNoneMatch'] = $IfNoneMatch;
        }
        if ($IfNoneExist !== null) {
            $request['ifNoneExist'] = $IfNoneExist;
        }
        if ($IfNoneMatch !== null) {
            $request['ifNoneMatch'] = $IfNoneMatch;
        }

        $entry = [
            'fullUrl' => $fullUrl,
            'resource' => $resource,
            'request' => $request,
        ];

        $this->push('entry', $entry);
        return $this;
    }

    /**
     * History entry with response metadata.
     */
    public function addHistoryEntry(
        array $resource,
        string $fullUrl,
        string $status,
        ?int $statusCode = null,
        ?string $etag = null,
        ?string $lastModified = null,
        ?string $location = null,
        ?string $outcome = null
    ): self {
        $response = ['status' => $status];

        if ($statusCode !== null) {
            $response['statusCode'] = $statusCode;
        }
        if ($etag !== null) {
            $response['etag'] = $etag;
        }
        if ($lastModified !== null) {
            $response['lastModified'] = $lastModified;
        }
        if ($location !== null) {
            $response['location'] = $location;
        }
        if ($outcome !== null) {
            $response['outcome'] = $outcome;
        }

        $entry = [
            'fullUrl' => $fullUrl,
            'resource' => $resource,
            'response' => $response,
        ];

        $this->push('entry', $entry);
        return $this;
    }

    /**
     * Transaction / batch response entry.
     */
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
            'response' => ['status' => $status],
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

    /**
     * Outcome-only entry (search operation-outcome).
     */
    public function addOutcomeEntry(array $operationOutcome, string $fullUrl): self
    {
        $entry = [
            'fullUrl' => $fullUrl,
            'resource' => $operationOutcome,
            'search' => ['mode' => 'outcome'],
        ];

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
            'type' => [['code' => $type]],
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

    // -------------------------------------------------------------------------
    // Build & validate
    // -------------------------------------------------------------------------

    public function build(): array
    {
        return parent::build();
    }

    /**
     * Validate required Bundle fields.
     * @return string[] List of validation errors (empty = valid)
     */
    public function validate(): array
    {
        $errors = [];

        if (!isset($this->data['type'])) {
            $errors[] = 'Bundle.type is required';
        }

        if (!isset($this->data['timestamp'])) {
            $errors[] = 'Bundle.timestamp is required';
        }

        return $errors;
    }

    /**
     * Validate + throw if invalid.
     * @throws \RuntimeException
     */
    public function json(): array
    {
        $errors = $this->validate();
        if ($errors !== []) {
            throw new \RuntimeException('Bundle validation failed: ' . implode('; ', $errors));
        }

        return $this->build();
    }
}

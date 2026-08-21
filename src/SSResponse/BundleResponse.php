<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSResponse;

/**
 * BundleResponse — parses FHIR Bundle responses (batch-response / transaction-response).
 *
 * Usage with request mapping:
 *   $res = $ssRequest->post('Bundle', $bundlePayload);
 *   $br = new BundleResponse($res->getHttpCode(), $res->getBody(), $bundlePayload['entry']);
 *
 * Method helpers let you extract results by HTTP verb used in the request entry:
 *   $postIds  = $br->byMethod(['POST']);   // newly created resources
 *   $putIds   = $br->byMethod(['PUT']);    // updated resources
 *   $patchIds = $br->byMethod(['PATCH']);  // patched resources
 *   $getAll   = $br->byMethod(['GET']);    // read results
 *   $mixed    = $br->byMethod(['POST','PUT','PATCH']); // all mutating
 */
class BundleResponse
{
    private array $bundle;

    private int $httpCode;

    /** @var array<int, array{index:int, method:string, url:string, fullUrl:string}> */
    private array $requestEntries = [];

    public function __construct(int $httpCode, array $bundle, array $requestEntries = [])
    {
        $this->httpCode = $httpCode;
        $this->bundle = $bundle;
        $this->requestEntries = $requestEntries;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getBundle(): array
    {
        return $this->bundle;
    }

    public function isSuccess(): bool
    {
        return $this->httpCode >= 200 && $this->httpCode < 300;
    }

    public function getTotal(): int
    {
        return (int) ($this->bundle['total'] ?? 0);
    }

    // -------------------------------------------------------------------------
    // Entry helpers (raw — no request context)
    // -------------------------------------------------------------------------

    public function getEntries(): array
    {
        $entries = [];

        if (! isset($this->bundle['entry']) || ! is_array($this->bundle['entry'])) {
            return $entries;
        }

        foreach ($this->bundle['entry'] as $entry) {
            $response = $entry['response'] ?? [];

            $entries[] = [
                'status' => $response['status'] ?? '',
                'location' => $response['location'] ?? '',
                'etag' => $response['etag'] ?? '',
                'lastModified' => $response['lastModified'] ?? '',
                'outcome' => $response['outcome'] ?? null,
                'fullUrl' => $entry['fullUrl'] ?? '',
            ];
        }

        return $entries;
    }

    public function getEntryStatuses(): array
    {
        $statuses = [];

        foreach ($this->getEntries() as $entry) {
            $statuses[] = $entry['status'];
        }

        return $statuses;
    }

    public function getSuccessEntries(): array
    {
        $success = [];

        foreach ($this->getEntries() as $index => $entry) {
            $code = (int) explode(' ', $entry['status'])[0];
            if ($code >= 200 && $code < 300) {
                $success[] = array_merge(['index' => $index], $entry);
            }
        }

        return $success;
    }

    public function getFailedEntries(): array
    {
        $failed = [];

        foreach ($this->getEntries() as $index => $entry) {
            $code = (int) explode(' ', $entry['status'])[0];
            if ($code >= 400 || $code === 0) {
                $failed[] = array_merge(['index' => $index], $entry);
            }
        }

        return $failed;
    }

    public function getEntryResourceId(int $index): ?string
    {
        $entries = $this->bundle['entry'] ?? [];

        if (! isset($entries[$index])) {
            return null;
        }

        $location = $entries[$index]['response']['location'] ?? '';

        if ($location === '') {
            return null;
        }

        // Parse resource ID from location URL
        $parts = explode('/', (string) $location);
        $idPart = end($parts);
        $idPart = explode('?', $idPart)[0];

        return $idPart !== '' ? $idPart : null;
    }

    public function getEntryErrors(int $index): array
    {
        $entries = $this->bundle['entry'] ?? [];

        if (! isset($entries[$index])) {
            return [];
        }

        $outcome = $entries[$index]['response']['outcome'] ?? null;

        if (! $outcome || ($outcome['resourceType'] ?? '') !== 'OperationOutcome') {
            return [];
        }

        $messages = [];

        if (! isset($outcome['issue']) || ! is_array($outcome['issue'])) {
            return $messages;
        }

        foreach ($outcome['issue'] as $issue) {
            $text = $issue['details']['text']
                ?? $issue['diagnostics']
                ?? $issue['details']['coding'][0]['display'] ?? null;

            if ($text) {
                $messages[] = (string) $text;
            }
        }

        return $messages;
    }

    public function getResourceIds(): array
    {
        $ids = [];

        foreach ($this->getEntries() as $index => $entry) {
            $id = $this->getEntryResourceId($index);

            if ($id) {
                $ids[] = [
                    'fullUrl' => $entry['fullUrl'],
                    'id' => $id,
                    'location' => $entry['location'],
                ];
            }
        }

        return $ids;
    }

    public function isAllSuccess(): bool
    {
        $entries = $this->getEntries();

        if (empty($entries)) {
            return false;
        }

        foreach ($entries as $entry) {
            $code = (int) explode(' ', (string) $entry['status'])[0];
            if ($code < 200 || $code >= 300) {
                return false;
            }
        }

        return true;
    }

    // -------------------------------------------------------------------------
    // Request-context helpers — pass $requestEntries in constructor
    // -------------------------------------------------------------------------

    /**
     * Filter response entries by their corresponding request HTTP method(s).
     * Response entries are in the same order as request entries (index alignment).
     *
     * @param array<string> $methods  e.g. ['POST'], ['POST','PUT'], ['GET']
     * @return array<int, array{
     *   index: int,
     *   method: string,
     *   url: string,
     *   fullUrl: string,
     *   status: string,
     *   statusCode: int,
     *   location: string,
     *   resourceId: string|null,
     *   etag: string,
     *   lastModified: string,
     *   outcome: array|null,
     *   errors: array<string>,
     *   isSuccess: bool
     * }>
     */
    public function byMethod(array $methods): array
    {
        if (empty($this->requestEntries)) {
            return [];
        }

        $results = [];
        $responseEntries = $this->bundle['entry'] ?? [];

        foreach ($this->requestEntries as $reqIndex => $reqEntry) {
            $method = strtoupper($reqEntry['method'] ?? '');

            if (! in_array($method, $methods, true)) {
                continue;
            }

            $respEntry = $responseEntries[$reqIndex] ?? [];
            $response = $respEntry['response'] ?? [];
            $status = $response['status'] ?? '';
            $statusCode = (int) explode(' ', (string) $status)[0];
            $location = $response['location'] ?? '';

            // Parse resource ID from location
            $resourceId = null;
            if ($location !== '') {
                $parts = explode('/', (string) $location);
                $idPart = end($parts);
                $idPart = explode('?', $idPart)[0];
                if ($idPart !== '') {
                    $resourceId = $idPart;
                }
            }

            // Parse outcome errors
            $errors = [];
            $outcome = $response['outcome'] ?? null;
            if ($outcome && ($outcome['resourceType'] ?? '') === 'OperationOutcome') {
                foreach ($outcome['issue'] ?? [] as $issue) {
                    $text = $issue['details']['text']
                        ?? $issue['diagnostics']
                        ?? $issue['details']['coding'][0]['display'] ?? null;
                    if ($text) {
                        $errors[] = (string) $text;
                    }
                }
            }

            // For GET: full resource is in response entry
            $resource = null;
            if ($method === 'GET' && isset($respEntry['resource'])) {
                $resource = $respEntry['resource'];
            }

            $results[] = [
                'index' => $reqIndex,
                'method' => $method,
                'url' => $reqEntry['url'] ?? '',
                'fullUrl' => $reqEntry['fullUrl'] ?? '',
                'status' => $status,
                'statusCode' => $statusCode,
                'location' => $location,
                'resourceId' => $resourceId,
                'etag' => $response['etag'] ?? '',
                'lastModified' => $response['lastModified'] ?? '',
                'outcome' => $outcome,
                'errors' => $errors,
                'isSuccess' => $statusCode >= 200 && $statusCode < 300,
                'resource' => $resource, // only populated for GET
            ];
        }

        return $results;
    }

    /**
     * Shorthand: entries that were POST (created resources).
     *
     * @return array<int, array>
     */
    public function created(): array
    {
        return $this->byMethod(['POST']);
    }

    /**
     * Shorthand: entries that were PUT (updated resources).
     *
     * @return array<int, array>
     */
    public function updated(): array
    {
        return $this->byMethod(['PUT']);
    }

    /**
     * Shorthand: entries that were PATCH (partially updated resources).
     *
     * @return array<int, array>
     */
    public function patched(): array
    {
        return $this->byMethod(['PATCH']);
    }

    /**
     * Shorthun: entries that were GET (read results — includes full resource).
     *
     * @return array<int, array>
     */
    public function read(): array
    {
        return $this->byMethod(['GET']);
    }

    /**
     * Shorthand: entries that were DELETE (deleted resources).
     *
     * @return array<int, array>
     */
    public function deleted(): array
    {
        return $this->byMethod(['DELETE']);
    }

    /**
     * Build a simple map: request fullUrl → server-assigned resource ID.
     * Useful for replacing urn:uuid:... references after a transaction.
     *
     * @return array<string, string>  [fullUrl => resourceId]
     */
    public function idMap(): array
    {
        $map = [];

        foreach ($this->created() as $entry) {
            if ($entry['fullUrl'] && $entry['resourceId']) {
                $map[$entry['fullUrl']] = $entry['resourceId'];
            }
        }

        return $map;
    }

    /**
     * Look up response entry by resource type + ID (from request URL).
     *
     * @return array|null
     */
    public function find(string $resourceType, string $resourceId): ?array
    {
        foreach ($this->requestEntries as $reqIndex => $reqEntry) {
            $url = $reqEntry['url'] ?? '';
            if (stripos($url, $resourceType) !== false && stripos($url, $resourceId) !== false) {
                $respEntries = $this->bundle['entry'] ?? [];
                $respEntry = $respEntries[$reqIndex]['response'] ?? [];

                return [
                    'index' => $reqIndex,
                    'method' => $reqEntry['method'] ?? '',
                    'status' => $respEntry['status'] ?? '',
                    'statusCode' => (int) explode(' ', (string) ($respEntry['status'] ?? ''))[0],
                    'location' => $respEntry['location'] ?? '',
                    'resourceId' => $this->getEntryResourceId($reqIndex),
                    'isSuccess' => (int) explode(' ', (string) ($respEntry['status'] ?? ''))[0] >= 200,
                ];
            }
        }

        return null;
    }
}

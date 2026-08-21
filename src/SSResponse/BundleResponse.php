<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSResponse;

class BundleResponse
{
    private array $bundle;

    private int $httpCode;

    public function __construct(int $httpCode, array $bundle)
    {
        $this->httpCode = $httpCode;
        $this->bundle = $bundle;
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
}

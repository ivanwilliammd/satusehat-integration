<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSResponse;

class SSResponse
{
    private int $httpCode;

    private array $body;

    private string $content;

    public function __construct(int $httpCode, array $body, string $content)
    {
        $this->httpCode = $httpCode;
        $this->body = $body;
        $this->content = $content;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isSuccess(): bool
    {
        return $this->httpCode >= 200 && $this->httpCode < 300;
    }

    public function isError(): bool
    {
        return ! $this->isSuccess();
    }

    /**
     * Extract FHIR OperationOutcome issue messages from response body.
     *
     * @return array<string>
     */
    public function getErrorMessages(): array
    {
        $messages = [];

        if (! isset($this->body['resourceType']) || $this->body['resourceType'] !== 'OperationOutcome') {
            return $messages;
        }

        if (! isset($this->body['issue']) || ! is_array($this->body['issue'])) {
            return $messages;
        }

        foreach ($this->body['issue'] as $issue) {
            $text = $issue['details']['text']
                ?? $issue['diagnostics']
                ?? $issue['details']['coding'][0]['display'] ?? null;

            if ($text) {
                $messages[] = (string) $text;
            }
        }

        return $messages;
    }

    /**
     * Extract resource id from response body.
     */
    public function getResourceId(): ?string
    {
        $id = $this->body['id'] ?? null;

        if ($id) {
            return (string) $id;
        }

        // Bundle entry link (self link contains full URL with id)
        if (($this->body['resourceType'] ?? '') === 'Bundle') {
            $selfLink = $this->body['link']['self']['url'] ?? null;
            if ($selfLink) {
                $parts = explode('/', (string) $selfLink);
                $idPart = end($parts);
                // strip query string
                $idPart = explode('?', $idPart)[0];
                if ($idPart !== '') {
                    return $idPart;
                }
            }

            // first entry resource id
            if (! empty($this->body['entry'][0]['resource']['id'])) {
                return (string) $this->body['entry'][0]['resource']['id'];
            }
        }

        // Patient create response
        if (isset($this->body['success']) && $this->body['success'] === true) {
            return $this->body['data']['patient_id'] ?? null;
        }

        if (isset($this->body['create_patient']['success']) && $this->body['create_patient']['success'] === true) {
            return $this->body['create_patient']['data']['patient_id'] ?? null;
        }

        return null;
    }

    /**
     * Shorthand for getResourceId — returns the id of the given FHIR resource type.
     *
     * @param string $resourceType Not currently used; reserved for future per-type extraction
     */
    public function get(string $resourceType): ?string
    {
        return $this->getResourceId();
    }
}

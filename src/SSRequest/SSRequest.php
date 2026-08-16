<?php

declare(strict_types=1);

namespace Satusehat\Integration\SSRequest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use Satusehat\Integration\OAuth2Client;
use Satusehat\Integration\SSResponse\SSResponse;

class SSRequest
{
    private ?OAuth2Client $oauth2;

    private ?string $token;

    private string $baseUrl;

    /** @var array<string, mixed> */
    private array $config;

    private Client $http;

    /** @var array<string, mixed> */
    private array $defaultHeaders;

    private int $timeout;

    private int $maxRetries;

    private int $retryCount = 0;

    /**
     * @param OAuth2Client|null $oauth2     OAuth2Client instance (token + baseUrl auto-resolved)
     * @param string|null       $token      Pre-provided bearer token (bypasses OAuth2Client)
     * @param string|null       $baseUrl    FHIR base URL override
     * @param array<string,mixed> $config    Optional: timeout, maxRetries, headers
     */
    public function __construct(
        ?OAuth2Client $oauth2 = null,
        ?string $token = null,
        ?string $baseUrl = null,
        array $config = []
    ) {
        $this->oauth2 = $oauth2;
        $this->token = $token;
        $this->config = $config;

        $this->timeout = (int) ($config['timeout'] ?? 30);
        $this->maxRetries = (int) ($config['maxRetries'] ?? 5);

        $this->defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/fhir+json',
        ];

        if (isset($config['headers']) && is_array($config['headers'])) {
            $this->defaultHeaders = array_merge($this->defaultHeaders, $config['headers']);
        }

        // Resolve base URL
        if ($baseUrl !== null) {
            $this->baseUrl = rtrim($baseUrl, '/');
        } elseif ($oauth2 !== null) {
            $this->baseUrl = rtrim($oauth2->fhir_url, '/');
        } else {
            $this->baseUrl = '';
        }

        $this->http = new Client([
            'timeout' => $this->timeout,
            'verify' => $config['verify'] ?? true,
        ]);
    }

    // -------------------------------------------------------------------------
    // Public HTTP methods
    // -------------------------------------------------------------------------

    public function get(string $url, array $params = []): SSResponse
    {
        $fullUrl = $this->buildUrl($url, $params);

        return $this->request('GET', $fullUrl);
    }

    public function post(string $url, array $body): SSResponse
    {
        $fullUrl = $this->buildUrl($url);

        return $this->request('POST', $fullUrl, $body);
    }

    public function put(string $url, array $body): SSResponse
    {
        $fullUrl = $this->buildUrl($url);

        return $this->request('PUT', $fullUrl, $body);
    }

    public function delete(string $url, array $body = []): SSResponse
    {
        $fullUrl = $this->buildUrl($url);

        return $this->request('DELETE', $fullUrl, $body);
    }

    public function patch(string $url, array $patchPayload): SSResponse
    {
        $fullUrl = $this->buildUrl($url);

        return $this->request('PATCH', $fullUrl, $patchPayload);
    }

    // -------------------------------------------------------------------------
    // Batch / Transaction (Bundle POST)
    // -------------------------------------------------------------------------

    public function postBundle(string $url, array $bundlePayload): SSResponse
    {
        return $this->post($url, $bundlePayload);
    }

    // -------------------------------------------------------------------------
    // Token resolution
    // -------------------------------------------------------------------------

    private function getToken(): ?string
    {
        if ($this->token !== null) {
            return $this->token;
        }

        if ($this->oauth2 !== null) {
            return $this->oauth2->token();
        }

        return null;
    }

    private function shouldAutoRefresh(): bool
    {
        if ($this->oauth2 !== null && method_exists($this->oauth2, 'isTokenAutoRefresh')) {
            return $this->oauth2->isTokenAutoRefresh();
        }

        return false;
    }

    private function isTokenAutoRefresh(): bool
    {
        return $this->shouldAutoRefresh();
    }

    // -------------------------------------------------------------------------
    // Core request with retry logic
    // -------------------------------------------------------------------------

    private function request(string $method, string $url, ?array $body = null): SSResponse
    {
        $this->retryCount = 0;

        return $this->doRequest($method, $url, $body);
    }

    private function doRequest(string $method, string $url, ?array $body = null): SSResponse
    {
        $token = $this->getToken();

        $headers = $this->defaultHeaders;
        if ($token !== null) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $options = ['headers' => $headers];

        if ($body !== null && in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $options['body'] = json_encode($body, JSON_THROW_ON_ERROR);
        }

        try {
            $response = $this->http->request($method, $url, $options);

            return $this->buildResponse($response);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            return $this->handleFailure($method, $url, $response, $body);
        } catch (\Throwable $e) {
            // Non-HTTP errors (connection, DNS, etc.) — return as error response
            return new SSResponse(
                0,
                ['resourceType' => 'OperationOutcome', 'issue' => [['details' => ['text' => $e->getMessage()]]]],
                $e->getMessage()
            );
        }
    }

    private function handleFailure(string $method, string $url, ResponseInterface $response, ?array $body = null): SSResponse
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $bodyArray = json_decode($content, true) ?? [];

        // 429 — rate limited: read Retry-After header
        if ($statusCode === 429) {
            $this->retryCount++;

            if ($this->retryCount > $this->maxRetries) {
                return new SSResponse($statusCode, $bodyArray, $content);
            }

            $retryAfter = $response->getHeaderLine('Retry-After');
            $sleep = $retryAfter !== '' ? (int) $retryAfter : (2 ** $this->retryCount);

            $this->sleep($sleep);

            return $this->doRequest($method, $url, $body);
        }

        // 401 — unauthorized: attempt token refresh
        if ($statusCode === 401) {
            if ($this->isTokenAutoRefresh() && $this->oauth2 !== null) {
                $this->retryCount++;

                if ($this->retryCount > $this->maxRetries) {
                    return new SSResponse($statusCode, $bodyArray, $content);
                }

                // Force token refresh
                if (method_exists($this->oauth2, 'refreshToken')) {
                    $this->oauth2->refreshToken();
                }
                $this->token = null;

                $this->sleep(2 ** $this->retryCount);

                return $this->doRequest($method, $url, $body);
            }

            return new SSResponse($statusCode, $bodyArray, $content);
        }

        // 5xx — server error: exponential backoff
        if ($statusCode >= 500) {
            $this->retryCount++;

            if ($this->retryCount > $this->maxRetries) {
                return new SSResponse($statusCode, $bodyArray, $content);
            }

            $this->sleep(2 ** $this->retryCount);

            return $this->doRequest($method, $url, $body);
        }

        return new SSResponse($statusCode, $bodyArray, $content);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function buildUrl(string $url, array $params = []): string
    {
        // Absolute URL already provided
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $full = $url;
        } else {
            $full = $this->baseUrl . '/' . ltrim($url, '/');
        }

        if (empty($params)) {
            return $full;
        }

        return $full . (str_contains($full, '?') ? '&' : '?') . http_build_query($params);
    }

    private function buildResponse(ResponseInterface $response): SSResponse
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        $bodyArray = json_decode($content, true) ?? [];

        return new SSResponse($statusCode, $bodyArray, $content);
    }

    private function sleep(int $seconds): void
    {
        if ($seconds <= 0) {
            return;
        }

        // Honor system-wide max sleep cap of 60s
        $seconds = min($seconds, 60);

        if (function_exists('sleep')) {
            sleep($seconds);
        }
    }
}

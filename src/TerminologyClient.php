<?php

declare(strict_types=1);

namespace Satusehat\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * TerminologyClient — HTTP client for SmarTerm (terminology.ivanmd.id).
 *
 * SmarTerm is the unified Indonesian health terminology dictionary service
 * (ICD-10, ICD-9-CM, SNOMED CT, LOINC, KPTL, KFA obat/alkes/BZA) powering
 * SATUSEHAT-ready code mapping. This client talks to its public REST API so
 * consuming apps can resolve/search/validate codes WITHOUT shipping the
 * national datasets locally.
 *
 * Environment (or constructor args):
 *   TERMINOLOGY_BASE_URL  default https://terminology.ivanmd.id
 *   TERMINOLOGY_API_TOKEN bearer token (JWT OIDC dari auth.ivanmd.id ATAU
 *                          Sanctum Personal Access Token)
 *
 * All responses are returned as decoded arrays of the server envelope
 * { success, data, count?, ...meta }.
 */
class TerminologyClient
{
    private Client $http;

    private string $baseUrl;

    private ?string $token;

    private int $timeout;

    /**
     * @param string|null $baseUrl API base, default env TERMINOLOGY_BASE_URL or
     *                             https://terminology.ivanmd.id
     * @param string|null $token   Bearer token (JWT OIDC / Sanctum PAT). Defaults
     *                             to env TERMINOLOGY_API_TOKEN.
     */
    public function __construct(?string $baseUrl = null, ?string $token = null, array $config = [])
    {
        $this->baseUrl = rtrim($baseUrl ?: (getenv('TERMINOLOGY_BASE_URL') ?: 'https://terminology.ivanmd.id'), '/');
        $this->token = $token ?: (getenv('TERMINOLOGY_API_TOKEN') ?: null);
        $this->timeout = (int) ($config['timeout'] ?? 30);

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'satusehat-integration/terminology-client',
        ];
        if ($this->token !== null && $this->token !== '') {
            $headers['Authorization'] = 'Bearer '.$this->token;
        }

        $this->http = new Client([
            'base_uri' => $this->baseUrl.'/',
            'timeout' => $this->timeout,
            'http_errors' => false,
            'headers' => $headers,
        ]);
    }

    /** Raw HTTP helper — returns [status, decodedBody]. */
    public function request(string $method, string $path, array $query = [], ?array $body = null): array
    {
        $options = [];
        if ($query !== []) {
            $options['query'] = $query;
        }
        if ($body !== null) {
            $options['json'] = $body;
        }

        try {
            $res = $this->http->request($method, $path, $options);
            $decoded = json_decode((string) $res->getBody(), true);

            return [$res->getStatusCode(), $decoded ?? ['raw' => (string) $res->getBody()]];
        } catch (GuzzleException $e) {
            return [0, ['error' => $e->getMessage()]];
        }
    }

    // ─── Unified & per-system search ──────────────────────────────────────────

    /** GET /api/v1/terminology/search?q=&category=&limit= */
    public function search(string $query, string $category = 'all', int $limit = 20): array
    {
        [$status, $body] = $this->request('GET', 'api/v1/terminology/search', [
            'q' => $query,
            'category' => $category,
            'limit' => max(1, min($limit, 100)),
        ]);

        return $this->unwrap($status, $body);
    }

    /** GET /api/v1/terminology/{system}/search?q= — system: icd10|icd9cm|snomed|loinc|kfa|kfa_alkes|kfa_bza|kptl */
    public function searchSystem(string $system, string $query, int $limit = 20): array
    {
        [$status, $body] = $this->request('GET', "api/v1/terminology/{$system}/search", [
            'q' => $query,
            'limit' => max(1, min($limit, 100)),
        ]);

        return $this->unwrap($status, $body);
    }

    /** GET /api/v1/terminology/kptl/combine?base_code=&modifiers= — multi-layer KPTL */
    public function kptlCombine(string $baseCode, array $modifiers = []): array
    {
        [$status, $body] = $this->request('GET', 'api/v1/terminology/kptl/combine', [
            'base_code' => $baseCode,
            'modifiers' => implode(',', $modifiers),
        ]);

        return $this->unwrap($status, $body);
    }

    /** GET /api/v1/terminology/mapping?source=&target=&code= — cross-terminology mapping */
    public function map(string $source, string $target, string $code): array
    {
        [$status, $body] = $this->request('GET', 'api/v1/terminology/mapping', [
            'source' => $source,
            'target' => $target,
            'code' => $code,
        ]);

        return $this->unwrap($status, $body);
    }

    /** POST /api/v1/terminology/validate — { system, code } */
    public function validate(string $system, string $code): array
    {
        [$status, $body] = $this->request('POST', 'api/v1/terminology/validate', [], [
            'system' => $system,
            'code' => $code,
        ]);

        return $this->unwrap($status, $body);
    }

    /** POST /api/v1/terminology/auto-map — { source_system, source_code } → SNOMED */
    public function autoMap(string $sourceSystem, string $sourceCode): array
    {
        [$status, $body] = $this->request('POST', 'api/v1/terminology/auto-map', [], [
            'source_system' => $sourceSystem,
            'source_code' => $sourceCode,
        ]);

        return $this->unwrap($status, $body);
    }

    // ─── Context-aware FHIR resolution ────────────────────────────────────────

    /** POST /api/v1/terminology/resolve/condition */
    public function resolveCondition(string $code, ?string $systemHint = null, string $category = 'encounter-diagnosis'): array
    {
        return $this->resolve('condition', [
            'code' => $code,
            'system_hint' => $systemHint,
            'category' => $category,
        ]);
    }

    /** POST /api/v1/terminology/resolve/chief-complaint */
    public function resolveChiefComplaint(string $code): array
    {
        return $this->resolve('chief-complaint', ['code' => $code]);
    }

    /** POST /api/v1/terminology/resolve/observation */
    public function resolveObservation(string $code, ?string $systemHint = null): array
    {
        return $this->resolve('observation', [
            'code' => $code,
            'system_hint' => $systemHint,
        ]);
    }

    /** POST /api/v1/terminology/resolve/medication */
    public function resolveMedication(string $code): array
    {
        return $this->resolve('medication', ['code' => $code]);
    }

    /** POST /api/v1/terminology/resolve/service-request */
    public function resolveServiceRequest(string $code, string $category = 'lab'): array
    {
        return $this->resolve('service-request', [
            'code' => $code,
            'category' => $category,
        ]);
    }

    // ─── Value sets ───────────────────────────────────────────────────────────

    /** GET /api/value-sets */
    public function valueSets(): array
    {
        [$status, $body] = $this->request('GET', 'api/value-sets');

        return $this->unwrap($status, $body);
    }

    /** GET /api/value-sets/{bundleId} */
    public function valueSet(string $bundleId): array
    {
        [$status, $body] = $this->request('GET', "api/value-sets/{$bundleId}");

        return $this->unwrap($status, $body);
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    private function resolve(string $kind, array $payload): array
    {
        [$status, $body] = $this->request('POST', "api/v1/terminology/resolve/{$kind}", [], array_filter($payload, fn ($v) => $v !== null));

        return $this->unwrap($status, $body);
    }

    /** Normalize envelope: throws on HTTP error / success:false; else returns data array. */
    private function unwrap(int $status, array $body): array
    {
        if ($status === 0) {
            throw new \RuntimeException('TerminologyClient network error: '.($body['error'] ?? 'unknown'));
        }
        if ($status >= 400) {
            throw new \RuntimeException("TerminologyClient HTTP {$status}: ".($body['message'] ?? $body['error'] ?? json_encode($body)));
        }

        return $body;
    }
}

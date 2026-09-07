<?php

declare(strict_types=1);

namespace Satusehat\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * TerminologyClient — standalone HTTP client for SmarTerm (terminology.ivanmd.id).
 *
 * ⚠️ TERPISAH dari ekosistem SATUSEHAT: jangan reuse OAuth2Client (itu OAuth2
 * SATUSEHAT Kemkes). SmarTerm punya auth sendiri, dual-mode:
 *
 *   1. Sanctum Personal Access Token (PAT) — token statis, header `Bearer`.
 *      Env: TERMINOLOGY_API_TOKEN (atau argumen $token).
 *   2. OIDC client_credentials ke auth.ivanmd.id (Passport) — client fetch JWT
 *      sendiri, cache sampai expire, auto-refresh saat 401.
 *      Env: TERMINOLOGY_CLIENT_ID / TERMINOLOGY_CLIENT_SECRET /
 *           TERMINOLOGY_TOKEN_URL (default https://auth.ivanmd.id/oauth/token)
 *
 * Jika keduanya diberikan, PAT menang (lebih sederhana). OIDC dipakai saat
 * $token kosong dan kredensial client tersedia.
 *
 * Fluent chaining:
 *   $term->query('pemasangan kateter')->on('kptl')->limit(10)->get();
 *   $term->query('paracetamol')->all()->first();
 */
class TerminologyClient
{
    private Client $http;

    private string $baseUrl;

    private ?string $token;

    private ?string $clientId;

    private ?string $clientSecret;

    private string $tokenUrl;

    private int $timeout;

    /** OIDC cached token. */
    private ?string $oidcToken = null;

    private int $oidcExpiresAt = 0;

    public function __construct(?string $baseUrl = null, ?string $token = null, array $config = [])
    {
        $this->baseUrl = rtrim($baseUrl ?: (getenv('TERMINOLOGY_BASE_URL') ?: 'https://terminology.ivanmd.id'), '/');
        $this->token = $token ?: (getenv('TERMINOLOGY_API_TOKEN') ?: null);
        $this->clientId = (string) ($config['client_id'] ?? getenv('TERMINOLOGY_CLIENT_ID') ?: '');
        $this->clientSecret = (string) ($config['client_secret'] ?? getenv('TERMINOLOGY_CLIENT_SECRET') ?: '');
        $this->tokenUrl = (string) ($config['token_url'] ?? getenv('TERMINOLOGY_TOKEN_URL') ?: 'https://auth.ivanmd.id/oauth/token');
        $this->timeout = (int) ($config['timeout'] ?? 30);

        $this->http = new Client([
            'base_uri' => $this->baseUrl.'/',
            'timeout' => $this->timeout,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'satusehat-integration/terminology-client',
            ],
        ]);
    }

    /** Fluent query builder — entry: query('istilah')->on('kfa')->limit(5)->get() */
    public function query(string $text): TerminologyQueryBuilder
    {
        return new TerminologyQueryBuilder($this, $text);
    }

    /** Raw HTTP helper — auto auth header; OIDC refresh-once on 401. Returns [status, body]. */
    public function request(string $method, string $path, array $query = [], ?array $body = null): array
    {
        $options = [];
        if ($query !== []) {
            $options['query'] = $query;
        }
        if ($body !== null) {
            $options['json'] = $body;
        }

        $token = $this->resolveToken();
        if ($token !== null && $token !== '') {
            $options['headers'] = ['Authorization' => 'Bearer '.$token];
        }

        try {
            $res = $this->http->request($method, $path, $options);
        } catch (GuzzleException $e) {
            return [0, ['error' => $e->getMessage()]];
        }

        // OIDC mode: token expire → refresh sekali lalu retry.
        if ($res->getStatusCode() === 401 && $this->token === null && $this->clientId !== '') {
            $this->oidcToken = null;
            $this->oidcExpiresAt = 0;
            $token = $this->fetchOidcToken();
            if ($token !== null) {
                $options['headers'] = ['Authorization' => 'Bearer '.$token];
                try {
                    $res = $this->http->request($method, $path, $options);
                } catch (GuzzleException $e) {
                    return [0, ['error' => $e->getMessage()]];
                }
            }
        }

        $decoded = json_decode((string) $res->getBody(), true);

        return [$res->getStatusCode(), $decoded ?? ['raw' => (string) $res->getBody()]];
    }

    // ─── Unified & per-system search ──────────────────────────────────────────

    /** GET /api/v1/terminology/search?q=&category=&limit= */
    public function search(string $query, string $category = 'all', int $limit = 20): array
    {
        [$status, $body] = $this->request('GET', 'api/v1/terminology/search', [
            'q' => $query,
            'category' => $category,
            'limit' => $this->clampLimit($limit),
        ]);

        return $this->unwrap($status, $body);
    }

    /** GET /api/v1/terminology/{system}/search?q= — system: icd10|icd9cm|snomed|loinc|kfa|kfa_alkes|kfa_bza|kptl */
    public function searchSystem(string $system, string $query, int $limit = 20): array
    {
        [$status, $body] = $this->request('GET', "api/v1/terminology/{$system}/search", [
            'q' => $query,
            'limit' => $this->clampLimit($limit),
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

    public function resolveCondition(string $code, ?string $systemHint = null, string $category = 'encounter-diagnosis'): array
    {
        return $this->resolve('condition', ['code' => $code, 'system_hint' => $systemHint, 'category' => $category]);
    }

    public function resolveChiefComplaint(string $code): array
    {
        return $this->resolve('chief-complaint', ['code' => $code]);
    }

    public function resolveObservation(string $code, ?string $systemHint = null): array
    {
        return $this->resolve('observation', ['code' => $code, 'system_hint' => $systemHint]);
    }

    public function resolveMedication(string $code): array
    {
        return $this->resolve('medication', ['code' => $code]);
    }

    public function resolveServiceRequest(string $code, string $category = 'lab'): array
    {
        return $this->resolve('service-request', ['code' => $code, 'category' => $category]);
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
        [$status, $body] = $this->request('GET', 'api/value-sets/'.rawurlencode($bundleId));

        return $this->unwrap($status, $body);
    }

    // ─── Internals ────────────────────────────────────────────────────────────

    /** Static PAT menang; kalau kosong dan ada OIDC creds → lazy fetch + cache. */
    private function resolveToken(): ?string
    {
        if ($this->token !== null && $this->token !== '') {
            return $this->token;
        }
        if ($this->clientId === '') {
            return null;
        }
        if ($this->oidcToken !== null && time() < $this->oidcExpiresAt - 30) {
            return $this->oidcToken;
        }
        if ($this->oidcToken === null) {
            $this->oidcToken = $this->fetchOidcToken();
        }

        return $this->oidcToken;
    }

    /** POST {tokenUrl} grant_type=client_credentials → access_token. */
    private function fetchOidcToken(): ?string
    {
        try {
            $res = $this->http->request('POST', $this->tokenUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]);
        } catch (GuzzleException $e) {
            return null;
        }

        if ($res->getStatusCode() >= 400) {
            return null;
        }
        $data = json_decode((string) $res->getBody(), true);
        $accessToken = $data['access_token'] ?? null;
        if (! is_string($accessToken) || $accessToken === '') {
            return null;
        }
        $this->oidcToken = $accessToken;
        $this->oidcExpiresAt = time() + (int) ($data['expires_in'] ?? 3600);

        return $accessToken;
    }

    private function resolve(string $kind, array $payload): array
    {
        [$status, $body] = $this->request('POST', "api/v1/terminology/resolve/{$kind}", [], array_filter($payload, fn ($v) => $v !== null));

        return $this->unwrap($status, $body);
    }

    private function clampLimit(int $limit): int
    {
        return max(1, min($limit, 100));
    }

    /** Throws on network error / HTTP >= 400; else returns full envelope array. */
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

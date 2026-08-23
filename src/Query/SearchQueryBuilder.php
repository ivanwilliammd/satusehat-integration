<?php

declare(strict_types=1);

namespace Satusehat\Integration\Query;

use InvalidArgumentException;

/**
 * Validated FHIR search query builder.
 *
 * Allowlist source: resources/search-parameters.json generated from HL7 FHIR R4
 * `search-parameters.json`. SATUSEHAT-specific narrowing can override via
 * config/search-parameters.satusehat.json later.
 *
 * Usage:
 *   $query = SearchQueryBuilder::for('Patient')
 *       ->where('identifier', 'https://fhir.kemkes.go.id/id/nik|317...')
 *       ->where('_count', 10)
 *       ->toArray();
 *
 *   $ss->get('Patient', $query);
 */
class SearchQueryBuilder
{
    private string $resourceType;

    /** @var array<string,mixed> */
    private array $params = [];

    /** @var array<string,array<string,array<string,mixed>>>|null */
    private static ?array $definitions = null;

    /** @var string[] */
    private static array $commonParams = [
        '_id', '_lastUpdated', '_tag', '_profile', '_security', '_text', '_content',
        '_list', '_has', '_type', '_sort', '_count', '_include', '_revinclude', '_summary',
        '_total', '_elements', '_contained', '_containedType', '_format', '_pretty',
    ];

    private function __construct(string $resourceType)
    {
        $this->resourceType = $resourceType;
        $this->assertResourceAllowed($resourceType);
    }

    public static function for(string $resourceType): self
    {
        return new self($resourceType);
    }

    /**
     * Add a validated search parameter.
     *
     * @param string $name FHIR search parameter name, e.g. identifier, patient, code
     * @param string|int|float|bool|array<int,string|int|float|bool> $value
     */
    public function where(string $name, $value): self
    {
        $this->assertParamAllowed($name);
        $this->params[$name] = $value;
        return $this;
    }

    /** Token search helper: system|code. */
    public function token(string $name, string $system, string $code): self
    {
        return $this->where($name, $system . '|' . $code);
    }

    /** Reference search helper: Resource/id. */
    public function reference(string $name, string $targetResource, string $id): self
    {
        return $this->where($name, $targetResource . '/' . $id);
    }

    /** Date search helper: ge/le/gt/lt/eq prefix + date. */
    public function date(string $name, string $prefix, string $date): self
    {
        if (!in_array($prefix, ['eq', 'ne', 'gt', 'lt', 'ge', 'le', 'sa', 'eb', 'ap'], true)) {
            throw new InvalidArgumentException("Invalid FHIR date prefix: {$prefix}");
        }
        return $this->where($name, $prefix . $date);
    }

    public function count(int $count): self
    {
        if ($count < 1) {
            throw new InvalidArgumentException('_count must be >= 1');
        }
        return $this->where('_count', $count);
    }

    public function sort(string $field): self
    {
        return $this->where('_sort', $field);
    }

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return $this->params;
    }

    public function toQueryString(): string
    {
        return http_build_query($this->params);
    }

    /** @return array<string,array<string,mixed>> */
    public static function allowedFor(string $resourceType): array
    {
        self::loadDefinitions();
        return self::$definitions[$resourceType] ?? [];
    }

    /** @return string[] */
    public static function resources(): array
    {
        self::loadDefinitions();
        $resources = array_keys(self::$definitions);
        sort($resources);
        return $resources;
    }

    private function assertResourceAllowed(string $resourceType): void
    {
        self::loadDefinitions();
        if (!isset(self::$definitions[$resourceType])) {
            throw new InvalidArgumentException("Unknown/unsupported FHIR resource: {$resourceType}");
        }
    }

    private function assertParamAllowed(string $name): void
    {
        if (in_array($name, self::$commonParams, true)) {
            return;
        }

        $allowed = self::allowedFor($this->resourceType);
        if (!isset($allowed[$name])) {
            $known = implode(', ', array_slice(array_keys($allowed), 0, 30));
            throw new InvalidArgumentException(
                "Search parameter '{$name}' is not allowed for {$this->resourceType}. Known: {$known}"
            );
        }
    }

    private static function loadDefinitions(): void
    {
        if (self::$definitions !== null) {
            return;
        }

        $path = dirname(__DIR__, 2) . '/resources/search-parameters.json';
        if (!is_file($path)) {
            throw new InvalidArgumentException("Search parameter definition file missing: {$path}");
        }

        $json = file_get_contents($path);
        $defs = json_decode($json ?: '{}', true);
        if (!is_array($defs)) {
            throw new InvalidArgumentException("Invalid search parameter definition JSON: {$path}");
        }

        self::$definitions = $defs;
    }
}

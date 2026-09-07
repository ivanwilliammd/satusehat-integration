<?php

declare(strict_types=1);

namespace Satusehat\Integration;

/**
 * Fluent query builder for SmarTerm — chained search against terminology.ivanmd.id.
 *
 *   $term->query('pemasangan kateter')->on('kptl')->limit(10)->get();
 *   $term->query('demam')->category('diagnosis')->first();
 *   $term->query('paracetamol')->on('kfa')->all();
 *   $term->query('93004944')->asCode()->validate();
 */
class TerminologyQueryBuilder
{
    private TerminologyClient $client;

    private string $text;

    /** null = unified search (/search); string = per-system. */
    private ?string $system = null;

    private string $category = 'all';

    private int $limit = 20;

    private bool $asCode = false;

    public function __construct(TerminologyClient $client, string $text)
    {
        $this->client = $client;
        $this->text = $text;
    }

    /** Scope ke satu sistem: icd10|icd9cm|snomed|loinc|kfa|kfa_alkes|kfa_bza|kptl */
    public function on(string $system): self
    {
        $this->system = $system;

        return $this;
    }

    /** Unified search category: all|diagnosis|procedure|medication|lab|imaging|... */
    public function category(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = max(1, min($limit, 100));

        return $this;
    }

    /** Treat text as exact code → validate (POST /validate). */
    public function asCode(): self
    {
        $this->asCode = true;

        return $this;
    }

    /** Alias of limit(1). */
    public function first(): self
    {
        return $this->limit(1);
    }

    /** Max 100 hasil. */
    public function all(): self
    {
        return $this->limit(100);
    }

    /** Execute — search (GET) or validate (POST). Returns envelope array. */
    public function get(): array
    {
        if ($this->asCode) {
            // text = code; butuh system tujuan utk validate. Kalau belum di-scope,
            // coba tebak: kode numerik 93xxxxxx→kfa, 83xxxxxx→kfa_alkes, 91xxxxxx→kfa_bza.
            $system = $this->system ?? $this->guessSystemFromCode($this->text);

            return $this->client->validate($system, $this->text);
        }

        if ($this->system !== null) {
            return $this->client->searchSystem($this->system, $this->text, $this->limit);
        }

        return $this->client->search($this->text, $this->category, $this->limit);
    }

    private function guessSystemFromCode(string $code): string
    {
        if (preg_match('/^93\d+$/', $code)) {
            return 'kfa';
        }
        if (preg_match('/^83\d+$/', $code)) {
            return 'kfa_alkes';
        }
        if (preg_match('/^91\d+$/', $code)) {
            return 'kfa_bza';
        }
        if (preg_match('/^\d{5,7}$/', $code)) {
            return 'snomed';
        }
        if (preg_match('/^\d+(\.\d+)?$/', $code)) {
            return 'icd9cm';
        }
        if (preg_match('/^[A-Z]\d{2}(\.\d+)?$/', $code)) {
            return 'icd10';
        }
        if (str_contains($code, '-')) {
            return 'loinc';
        }

        return 'snomed';
    }
}

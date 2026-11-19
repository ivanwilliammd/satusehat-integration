<?php

declare(strict_types=1);

namespace Satusehat\Integration\CDA;

/**
 * One CDA section: a coded heading with narrative text and, optionally, nested sections.
 *
 * The narrative `<text>` is kept as XML, not as a string of HTML, because CDA narrative is permitted
 * to carry `<content>`, `<paragraph>` and referenced elements; flattening it to text would lose the
 * only human-readable part of the document.
 */
final class Section
{
    /**
     * @param array<string, string> $codes  codeSystem => code, from the <code> element
     * @param list<Section>         $children
     */
    public function __construct(
        public string $title = '',
        public string $text = '',
        public array $codes = [],
        public array $children = [],
    ) {
    }

    /** LOINC code of this section, e.g. "10164-2" for History of present illness. */
    public function code(): ?string
    {
        if (isset($this->codes['2.16.840.1.113883.6.1'])) {
            return $this->codes['2.16.840.1.113883.6.1'];
        }

        if ($this->codes === []) {
            return null;
        }
        $first = reset($this->codes);

        return $first === false ? null : $first;
    }

    /** First section in the tree with this LOINC code, depth first. */
    public function find(string $loinc): ?Section
    {
        if (($this->codes['2.16.840.1.113883.6.1'] ?? null) === $loinc) {
            return $this;
        }
        foreach ($this->children as $child) {
            if (($hit = $child->find($loinc)) !== null) {
                return $hit;
            }
        }

        return null;
    }

    /** @return list<Section> */
    public function flatten(): array
    {
        $out = [$this];
        foreach ($this->children as $child) {
            foreach ($child->flatten() as $s) {
                $out[] = $s;
            }
        }

        return $out;
    }
}

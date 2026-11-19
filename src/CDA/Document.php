<?php

declare(strict_types=1);

namespace Satusehat\Integration\CDA;

use DOMDocument;
use DOMElement;
use DOMXPath;
use InvalidArgumentException;

/**
 * HL7 CDA R2 ClinicalDocument: parse, inspect, and build the minimum document that a hospital
 * record exchange actually needs.
 *
 * Scope is deliberate. CDA R2 is hundreds of pages of optional structure; the part that gets used is
 * the header (who, what, when, where, signed by whom) and the sections with narrative text. Those are
 * modelled. Everything else is preserved in the DOM and written back untouched on `toXml()`, so a
 * document that contains structures this class does not understand survives a round trip instead of
 * being truncated by the SDK.
 *
 * The HL7 CDA R2 schemas are licensed; this class implements the structural rules from the
 * specification and does not redistribute them.
 */
final class Document
{
    public const NS = 'urn:hl7-org:v3';
    public const LOINC = '2.16.840.1.113883.6.1';

    public DOMDocument $dom;

    private function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    /** Parses a ClinicalDocument. Refuses anything whose root element is not ClinicalDocument. */
    public static function parse(string $xml): self
    {
        $dom = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $ok = $dom->loadXML($xml, LIBXML_NONET | LIBXML_NOBLANKS);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$ok || $dom->documentElement === null) {
            throw new InvalidArgumentException('XML CDA tidak sah.');
        }
        if ($dom->documentElement->localName !== 'ClinicalDocument') {
            throw new InvalidArgumentException(
                'Akar dokumen adalah ' . $dom->documentElement->localName . ', bukan ClinicalDocument.'
            );
        }

        return new self($dom);
    }

    public function xpath(): DOMXPath
    {
        $xp = new DOMXPath($this->dom);
        $xp->registerNamespace('hl7', self::NS);

        return $xp;
    }

    /** @return list<DOMElement> */
    public function nodes(string $query, ?DOMElement $context = null): array
    {
        $result = $this->xpath()->query($query, $context);
        if ($result === false) {
            return [];
        }
        $out = [];
        foreach ($result as $node) {
            if ($node instanceof DOMElement) {
                $out[] = $node;
            }
        }

        return $out;
    }

    public function first(string $query, ?DOMElement $context = null): ?DOMElement
    {
        return $this->nodes($query, $context)[0] ?? null;
    }

    public function scalar(string $query, ?DOMElement $context = null): string
    {
        $node = $this->first($query, $context);
        if ($node === null) {
            return '';
        }
        $value = $node->getAttribute('value');

        return trim($value !== '' ? $value : $node->textContent);
    }

    public function title(): string
    {
        return $this->scalar('//hl7:ClinicalDocument/hl7:title');
    }

    public function documentId(): string
    {
        $node = $this->first('//hl7:ClinicalDocument/hl7:id');
        if ($node === null) {
            return '';
        }

        return $node->getAttribute('root') . ($node->getAttribute('extension') !== ''
            ? '|' . $node->getAttribute('extension') : '');
    }

    public function typeCode(): string
    {
        return $this->first('//hl7:ClinicalDocument/hl7:code')?->getAttribute('code') ?? '';
    }

    public function effectiveTime(): string
    {
        return $this->first('//hl7:ClinicalDocument/hl7:effectiveTime')?->getAttribute('value') ?? '';
    }

    public function custodian(): string
    {
        return $this->scalar('//hl7:ClinicalDocument/hl7:custodian//hl7:name');
    }

    /** Author names in document order; empty when the document is unsigned by a person. */
    public function authors(): array
    {
        $authors = [];
        foreach ($this->nodes('//hl7:ClinicalDocument/hl7:author') as $author) {
            $family = $this->scalar('.//hl7:assignedPerson/hl7:name/hl7:family', $author);
            $given = $this->scalar('.//hl7:assignedPerson/hl7:name/hl7:given', $author);
            $name = trim($given . ' ' . $family);
            $authors[] = $name !== '' ? $name : $this->scalar('.//hl7:assignedAuthoringDevice/hl7:softwareName', $author);
        }

        return array_values(array_filter($authors, static fn (string $a): bool => $a !== ''));
    }

    public function patient(): PatientRole
    {
        $role = $this->first('//hl7:ClinicalDocument/hl7:recordTarget/hl7:patientRole');
        if ($role === null) {
            return new PatientRole();
        }
        $ids = [];
        foreach ($this->nodes('.//hl7:id', $role) as $id) {
            $ids[] = ['root' => $id->getAttribute('root'), 'extension' => $id->getAttribute('extension')];
        }
        $names = [];
        foreach ($this->nodes('.//hl7:name', $role) as $name) {
            $names[] = trim($this->scalar('hl7:given', $name) . ' ' . $this->scalar('hl7:family', $name));
        }
        $gender = $this->first('.//hl7:administrativeGenderCode', $role)?->getAttribute('code') ?? '';
        $birth = $this->first('.//hl7:birthTime', $role)?->getAttribute('value') ?? '';

        return new PatientRole(array_values(array_filter($ids)), array_values(array_filter($names)), $gender, $birth);
    }

    /** @return list<Section> */
    public function sections(): array
    {
        $body = $this->first('//hl7:ClinicalDocument/hl7:component/hl7:structuredBody');
        if ($body === null) {
            return [];
        }
        $out = [];
        foreach ($this->nodes('hl7:component/hl7:section', $body) as $section) {
            $out[] = $this->readSection($section);
        }

        return $out;
    }

    /** Section by LOINC code, searched through the whole tree. */
    public function section(string $loinc): ?Section
    {
        foreach ($this->sections() as $section) {
            if (($hit = $section->find($loinc)) !== null) {
                return $hit;
            }
        }

        return null;
    }

    /** Narrative text of a section, with CDA markup flattened to text. */
    public function sectionText(string $loinc): string
    {
        $section = $this->section($loinc);

        return $section === null ? '' : self::plainText($section->text);
    }

    private function readSection(DOMElement $section): Section
    {
        $codes = [];
        foreach ($this->nodes('hl7:code', $section) as $code) {
            $system = $code->getAttribute('codeSystem');
            $value = $code->getAttribute('code');
            if ($system !== '' && $value !== '') {
                $codes[$system] = $value;
            }
        }
        $children = [];
        foreach ($this->nodes('hl7:component/hl7:section', $section) as $child) {
            $children[] = $this->readSection($child);
        }
        $textNode = $this->first('hl7:text', $section);
        $text = '';
        if ($textNode !== null) {
            $text = $this->dom->saveXML($textNode) ?: '';
        }

        return new Section(
            $this->scalar('hl7:title', $section),
            $text,
            $codes,
            $children,
        );
    }

    /** Flattens CDA narrative markup to plain text, keeping paragraph breaks. */
    public static function plainText(string $xml): string
    {
        if (trim($xml) === '') {
            return '';
        }
        $fragment = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $fragment->loadXML('<root xmlns="' . self::NS . '">' . $xml . '</root>', LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $text = '';
        foreach ($fragment->getElementsByTagName('*') as $element) {
            if (in_array($element->localName, ['paragraph', 'item', 'tr', 'caption'], true)) {
                $text .= "\n";
            }
            foreach ($element->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $text .= $child->nodeValue;
                }
            }
        }
        if ($text === '') {
            $text = $fragment->textContent;
        }

        return trim(preg_replace("/\n{3,}/", "\n\n", $text) ?? $text);
    }

    /**
     * Builds a minimal but structurally valid ClinicalDocument.
     *
     * @param array{id?: string, title?: string, code?: string, codeDisplay?: string, effectiveTime?: string,
     *              custodian?: string, author?: string, patientId?: string, patientIdRoot?: string,
     *              patientName?: string, gender?: string, birthTime?: string,
     *              sections?: array<string, array{title?: string, text?: string, loinc?: string}>} $data
     */
    public static function build(array $data): self
    {
        $esc = static fn (string $v): string => htmlspecialchars($v, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $id = $data['id'] ?? ('DOC' . date('YmdHis'));
        $now = $data['effectiveTime'] ?? date('YmdHis');
        $patientId = $data['patientId'] ?? '';
        $patientRoot = $data['patientIdRoot'] ?? '2.16.840.1.113883.3.3157.1003.1';

        $sections = '';
        foreach ($data['sections'] ?? [] as $key => $section) {
            $loinc = $section['loinc'] ?? (is_string($key) ? $key : '');
            $code = $loinc !== ''
                ? '<code code="' . $esc($loinc) . '" codeSystem="' . self::LOINC . '"/>'
                : '';
            $sections .= '<component><section>'
                . $code
                . '<title>' . $esc($section['title'] ?? '') . '</title>'
                . '<text>' . ($section['text'] ?? '') . '</text>'
                . '</section></component>';
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<ClinicalDocument xmlns="' . self::NS . '">'
            . '<realmCode code="ID"/>'
            . '<typeId root="2.16.840.1.113883.1.3" extension="POCD_MT000040"/>'
            . '<templateId root="2.16.840.1.113883.10.20.22.1.1"/>'
            . '<id root="' . $esc($id) . '"/>'
            . '<code code="' . $esc($data['code'] ?? '34133-9') . '" codeSystem="' . self::LOINC . '" '
            . 'displayName="' . $esc($data['codeDisplay'] ?? 'Summarization of episode note') . '"/>'
            . '<title>' . $esc($data['title'] ?? 'Catatan Medis') . '</title>'
            . '<effectiveTime value="' . $esc($now) . '"/>'
            . '<confidentialityCode code="N" codeSystem="2.16.840.1.113883.5.25"/>'
            . '<recordTarget><patientRole>'
            . ($patientId !== ''
                ? '<id root="' . $esc($patientRoot) . '" extension="' . $esc($patientId) . '"/>'
                : '')
            . '<patient>'
            . (($data['patientName'] ?? '') !== ''
                ? '<name><family>' . $esc($data['patientName'] ?? '') . '</family></name>'
                : '')
            . (($data['gender'] ?? '') !== ''
                ? '<administrativeGenderCode code="' . $esc($data['gender']) . '" '
                  . 'codeSystem="2.16.840.1.113883.5.1"/>'
                : '')
            . (($data['birthTime'] ?? '') !== ''
                ? '<birthTime value="' . $esc($data['birthTime']) . '"/>'
                : '')
            . '</patient></patientRole></recordTarget>'
            . '<author><time value="' . $esc($now) . '"/><assignedAuthor><id root="' . $esc($id) . '.AUTH"/>'
            . '<assignedPerson><name><given>' . $esc($data['author'] ?? '') . '</given></name></assignedPerson>'
            . '</assignedAuthor></author>'
            . '<custodian><assignedCustodian><representedCustodianOrganization>'
            . '<name>' . $esc($data['custodian'] ?? '') . '</name>'
            . '</representedCustodianOrganization></assignedCustodian></custodian>'
            . '<component><structuredBody>' . $sections . '</structuredBody></component>'
            . '</ClinicalDocument>';

        return self::parse($xml);
    }

    /**
     * Structural check against the CDA R2 header rules that matter for exchange. Returns problems as
     * strings; an empty list means the document is exchangeable, not that it is clinically correct.
     *
     * @return list<string>
     */
    public function validate(): array
    {
        $problems = [];
        if ($this->documentId() === '') {
            $problems[] = 'ClinicalDocument/id wajib ada.';
        }
        if ($this->title() === '') {
            $problems[] = 'ClinicalDocument/title wajib ada.';
        }
        if ($this->typeCode() === '') {
            $problems[] = 'ClinicalDocument/code wajib ada.';
        }
        if ($this->effectiveTime() === '') {
            $problems[] = 'ClinicalDocument/effectiveTime wajib ada.';
        }
        if ($this->first('//hl7:ClinicalDocument/hl7:recordTarget') === null) {
            $problems[] = 'recordTarget wajib ada.';
        }
        if ($this->patient()->identifiers === []) {
            $problems[] = 'recordTarget/patientRole/id wajib ada.';
        }
        if ($this->custodian() === '') {
            $problems[] = 'custodian wajib ada.';
        }
        if ($this->authors() === []) {
            $problems[] = 'author wajib ada.';
        }
        if ($this->sections() === []) {
            $problems[] = 'structuredBody wajib berisi minimal satu section.';
        }

        return $problems;
    }

    public function toXml(): string
    {
        $this->dom->formatOutput = true;

        return $this->dom->saveXML() ?: '';
    }
}

<?php

declare(strict_types=1);

namespace Satusehat\Integration\CDA;

/**
 * The patient identity block of a CDA document: identifiers, name, sex and birth date.
 *
 * Identifiers keep their full (root, extension) pair: a NIK without the root cannot be matched
 * against anything, and dropping the root is how a local MRN silently becomes a national NIK.
 */
final class PatientRole
{
    /**
     * @param list<array{root: string, extension: string}> $identifiers
     * @param list<string>                                 $names
     */
    public function __construct(
        public array $identifiers = [],
        public array $names = [],
        public string $administrativeGender = '',
        public string $birthTime = '',
    ) {
    }

    public function name(): string
    {
        return $this->names === [] ? '' : $this->names[0];
    }

    /** First identifier with this root, or '' — roots identify the assigning authority. */
    public function identifier(string $root): string
    {
        foreach ($this->identifiers as $id) {
            if ($id['root'] === $root) {
                return $id['extension'];
            }
        }

        return '';
    }
}

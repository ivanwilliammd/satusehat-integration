<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

/**
 * A declarative validation profile: which segments must exist, which fields must be filled, and
 * which values are allowed.
 *
 * It is data, not a class per message type, because every LIS and every hospital disagrees about the
 * details. A new vendor is a new array, not a new file that has to be ported to five languages.
 *
 *   $profile = Profile::fromArray([
 *       'name' => 'ADT_A01',
 *       'message_type' => 'ADT',
 *       'trigger' => 'A01',
 *       'required_segments' => ['MSH', 'EVN', 'PID', 'PV1'],
 *       'required_fields' => ['PID.3' => 'Patient identifier', 'PID.5.1' => 'Family name'],
 *       'allowed_values' => ['PID.8.1' => ['F', 'M', 'O', 'U']],
 *   ]);
 *   $issues = $profile->validate($msg);
 */
final class Profile
{
    /** @param array<string,string> $requiredFields @param array<string,list<string>> $allowedValues */
    private function __construct(
        public string $name,
        private string $messageType,
        private string $trigger,
        private array $requiredSegments,
        private array $requiredFields,
        private array $allowedValues
    ) {
    }

    /** @param array<string,mixed> $def */
    public static function fromArray(array $def): self
    {
        return new self(
            (string) ($def['name'] ?? 'unnamed'),
            (string) ($def['message_type'] ?? ''),
            (string) ($def['trigger'] ?? ''),
            array_values(array_map('strval', $def['required_segments'] ?? [])),
            array_map('strval', $def['required_fields'] ?? []),
            array_map(static fn ($v): array => array_map('strval', (array) $v), $def['allowed_values'] ?? [])
        );
    }

    /**
     * @return list<Issue> empty when the message satisfies the profile
     */
    public function validate(Message $message): array
    {
        $issues = [];

        if ($this->messageType !== '' && $message->messageType() !== $this->messageType) {
            $issues[] = new Issue(Issue::ERROR, 'MSH.9.1', "message type {$message->messageType()} bukan {$this->messageType}");
        }
        if ($this->trigger !== '' && $message->triggerEvent() !== $this->trigger) {
            $issues[] = new Issue(Issue::ERROR, 'MSH.9.2', "trigger {$message->triggerEvent()} bukan {$this->trigger}");
        }

        foreach ($this->requiredSegments as $segment) {
            if ($message->segment($segment) === null) {
                $issues[] = new Issue(Issue::ERROR, $segment, 'segmen wajib tidak ada');
            }
        }

        foreach ($this->requiredFields as $path => $label) {
            if (trim($message->get($path)) === '') {
                $issues[] = new Issue(Issue::ERROR, $path, "wajib diisi ({$label})");
            }
        }

        foreach ($this->allowedValues as $path => $allowed) {
            $value = $message->get($path);
            if ($value !== '' && !in_array($value, $allowed, true)) {
                $issues[] = new Issue(
                    Issue::WARNING,
                    $path,
                    "nilai '{$value}' di luar daftar yang lazim (" . implode(', ', $allowed) . ')'
                );
            }
        }

        return $issues;
    }

    /** True when the message may be accepted (warnings do not block). */
    public function passes(Message $message): bool
    {
        foreach ($this->validate($message) as $issue) {
            if ($issue->isError()) {
                return false;
            }
        }

        return true;
    }
}

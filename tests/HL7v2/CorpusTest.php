<?php

namespace Satusehat\Integration\Tests\HL7v2;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\HL7v2\Message;
use Satusehat\Integration\HL7v2\Profile;
use Satusehat\Integration\HL7v2\Profiles;

/**
 * Corpus test: ten messages a hospital would actually send, and ten that must be refused.
 *
 * The invalid half matters more than the valid one. A parser that happily accepts a message with no
 * PID is worse than one that fails, because the failure then happens downstream, in the database,
 * at 03:00, without the raw message to look at.
 */
final class CorpusTest extends TestCase
{
    private const DIR = __DIR__ . '/fixtures';

    /** @return array<string,array{0:string,1:string}> */
    public static function validProvider(): array
    {
        return self::cases('valid');
    }

    /** @return array<string,array{0:string,1:string}> */
    public static function invalidProvider(): array
    {
        return self::cases('invalid');
    }

    /** @return array<string,array{0:string,1:string}> */
    private static function cases(string $group): array
    {
        $manifest = json_decode((string) file_get_contents(self::DIR . '/manifest.json'), true);
        $out = [];
        foreach (array_keys($manifest[$group]) as $name) {
            $out[$name] = [$name, $manifest[$group][$name]['profile']];
        }

        return $out;
    }

    /** @dataProvider validProvider */
    public function testValidMessagesPassTheirProfile(string $name, string $profile): void
    {
        $msg = Message::parse($this->read('valid', $name));

        $issues = $this->profile($profile)->validate($msg);
        $errors = array_filter($issues, static fn ($i): bool => $i->isError());

        self::assertSame([], array_values(array_map('strval', $errors)), "fixture {$name} seharusnya lolos");
    }

    /** @dataProvider invalidProvider */
    public function testInvalidMessagesAreRefused(string $name, string $profile): void
    {
        $manifest = json_decode((string) file_get_contents(self::DIR . '/manifest.json'), true);
        $expect = $manifest['invalid'][$name];

        if (isset($expect['exception'])) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessageMatches('/' . preg_quote($expect['exception'], '/') . '/');
            Message::parse($this->read('invalid', $name));

            return;
        }

        $msg = Message::parse($this->read('invalid', $name));
        $paths = array_map(static fn ($i): string => $i->path, $this->profile($profile)->validate($msg));

        foreach ($expect['issues'] as $path) {
            self::assertContains($path, $paths, "fixture {$name} harus melaporkan {$path}");
        }
    }

    public function testCorpusIsNotEmpty(): void
    {
        self::assertGreaterThanOrEqual(20, count(glob(self::DIR . '/*/*.hl7')));
    }

    private function read(string $group, string $name): string
    {
        return (string) file_get_contents(self::DIR . "/{$group}/{$name}.hl7");
    }

    private function profile(string $name): Profile
    {
        return match ($name) {
            'oruR01' => Profiles::oruR01(),
            'adtA08' => Profiles::adtA08(),
            default => Profiles::adtA01(),
        };
    }
}

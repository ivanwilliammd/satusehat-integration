<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\GoalParser;

/**
 * GoalParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class GoalParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/Goal.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('Goal', $data['resourceType']);

        $parser = new GoalParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['id'], $parser->getId(), 'elemen id');
        $this->assertSame($data['lifecycleStatus'], $parser->getLifecycleStatus(), 'elemen lifecycleStatus');
        $this->assertSame($data['achievementStatus'], $parser->getAchievementStatus(), 'elemen achievementStatus');
        $this->assertSame($data['category'], $parser->getCategory(), 'elemen category');
        $this->assertSame($data['description'], $parser->getDescription(), 'elemen description');
        $this->assertSame($data['subject'], $parser->getSubject(), 'elemen subject');
        $this->assertSame($data['target'], $parser->getTarget(), 'elemen target');
        $this->assertSame($data['statusDate'], $parser->getStatusDate(), 'elemen statusDate');
        $this->assertSame($data['expressedBy'], $parser->getExpressedBy(), 'elemen expressedBy');
        $this->assertSame($data['addresses'], $parser->getAddresses(), 'elemen addresses');
        $this->assertSame($data['outcomeCode'], $parser->getOutcomeCode(), 'elemen outcomeCode');
    }
}

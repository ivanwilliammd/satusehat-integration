<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\SSRequest\BatchSender;
use Satusehat\Integration\SSRequest\Journal;
use Satusehat\Integration\SSRequest\SSRequest;

/**
 * The two promises this feature makes: a resumed run never re-sends what the server accepted, and a 429 stops
 * everybody for a while instead of only the request that saw it.
 */
final class BatchSenderTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/satusehat-batch-' . bin2hex(random_bytes(4));
        mkdir($this->dir, 0775, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $file) {
            unlink($file);
        }
        @rmdir($this->dir);
    }

    /** @param array<int,Response> $responses */
    private function request(array $responses): SSRequest
    {
        $mock = new MockHandler($responses);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        return new SSRequest(token: 'uji', baseUrl: 'https://fhir.example.test', config: ['http_client' => $client]);
    }

    public function testResultsComeBackInInputOrder(): void
    {
        $request = $this->request([
            new Response(201, [], '{"resourceType":"Bundle"}'),
            new Response(400, [], '{"resourceType":"OperationOutcome"}'),
            new Response(200, [], '{}'),
        ]);

        $out = (new BatchSender($request, concurrency: 3))->send([
            ['resourceType' => 'Bundle', 'id' => 'a'],
            ['resourceType' => 'Bundle', 'id' => 'b'],
            ['resourceType' => 'Bundle', 'id' => 'c'],
        ]);

        $this->assertSame([201, 400, 200], array_column($out, 'status'));
        $this->assertSame([true, false, true], array_column($out, 'ok'));
        $this->assertSame([0, 1, 2], array_keys($out));
    }

    public function testJournalSkipsWhatTheServerAlreadyAccepted(): void
    {
        $journal = new Journal($this->dir . '/journal.jsonl');
        $bundle = ['resourceType' => 'Bundle', 'id' => 'sudah-terkirim'];
        $journal->record(Journal::fingerprint($bundle), 201);

        // No responses queued: if the sender tried to send, the mock handler would throw.
        $request = $this->request([]);

        $out = (new BatchSender($request, journal: $journal))->send([$bundle]);

        $this->assertTrue($out[0]['skipped']);
        $this->assertTrue($out[0]['ok']);
        $this->assertSame(0, $out[0]['status']);
    }

    public function testFailuresAreRecordedSoTheNextRunRetriesThem(): void
    {
        $journal = new Journal($this->dir . '/journal.jsonl');
        $bundle = ['resourceType' => 'Bundle', 'id' => 'gagal'];
        $request = $this->request([new Response(500, [], '{}'), new Response(500, [], '{}')]);

        $out = (new BatchSender($request, maxRetries: 0))->send([$bundle]);

        $this->assertFalse($out[0]['ok']);
        $this->assertFalse($journal->alreadySent(Journal::fingerprint($bundle)));

        // Now the server answers 201 on the resumed run.
        $retry = $this->request([new Response(201, [], '{}')]);
        $again = (new BatchSender($retry, journal: $journal))->send([$bundle]);

        $this->assertFalse($again[0]['skipped'], 'a failed payload must be sent again');
        $this->assertSame(201, $again[0]['status']);
        $this->assertTrue($journal->alreadySent(Journal::fingerprint($bundle)));
    }

    public function testA429IsRetriedAfterTheSharedGate(): void
    {
        $request = $this->request([
            new Response(429, ['Retry-After' => '0'], '{}'),
            new Response(201, [], '{}'),
        ]);

        $start = microtime(true);
        $out = (new BatchSender($request, maxRetries: 1))->send([['resourceType' => 'Bundle', 'id' => 'a']]);

        $this->assertSame(201, $out[0]['status']);
        $this->assertTrue($out[0]['ok']);
        // Retry-After: 0 must not turn into a multi-second wait when the header is honest.
        $this->assertLessThan(3.0, microtime(true) - $start);
    }

    public function testConcurrencyMustBePositive(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new BatchSender($this->request([]), concurrency: 0);
    }
}

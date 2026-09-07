<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\TerminologyClient;

/**
 * @covers \Satusehat\Integration\TerminologyClient
 */
class TerminologyClientTest extends TestCase
{
    private function clientWith(array $responses): array
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $http = new Client(['handler' => $handler]);

        // Inject mock Guzzle via reflection — TerminologyClient builds its own client,
        // so instead we exercise the request() path through a tiny subclass hook.
        $client = new class($http) extends TerminologyClient {
            public Client $httpPublic;

            public function __construct(Client $http)
            {
                $this->httpPublic = $http;
                parent::__construct('https://terminology.ivanmd.id', 'test-token');
                $this->httpPublic = $http;
            }
        };

        return [$client, $mock];
    }

    public function testSearchBuildsEnvelopeAndReturnsData(): void
    {
        $client = new class('https://terminology.ivanmd.id', 'tok') extends TerminologyClient {
            public array $last = [];

            public function request(string $method, string $path, array $query = [], ?array $body = null): array
            {
                $this->last = [$method, $path, $query, $body];

                return [200, ['success' => true, 'data' => [['terminology' => 'icd10', 'code' => 'A09']], 'count' => 1]];
            }
        };

        $result = $client->search('diare', 'diagnosis', 10);

        $this->assertSame('GET', $client->last[0]);
        $this->assertSame('api/v1/terminology/search', $client->last[1]);
        $this->assertSame(['q' => 'diare', 'category' => 'diagnosis', 'limit' => 10], $client->last[2]);
        $this->assertSame([['terminology' => 'icd10', 'code' => 'A09']], $result['data']);
    }

    public function testSearchSystemKfaAlkes(): void
    {
        $client = new class('https://terminology.ivanmd.id', 'tok') extends TerminologyClient {
            public array $last = [];

            public function request(string $method, string $path, array $query = [], ?array $body = null): array
            {
                $this->last = [$method, $path, $query, $body];

                return [200, ['success' => true, 'data' => [['kfa_code' => '83005721']], 'count' => 1]];
            }
        };

        $result = $client->searchSystem('kfa_alkes', 'termometer', 5);

        $this->assertSame('api/v1/terminology/kfa_alkes/search', $client->last[1]);
        $this->assertSame('termometer', $client->last[2]['q']);
        $this->assertSame('83005721', $result['data'][0]['kfa_code']);
    }

    public function testValidatePostsBody(): void
    {
        $client = new class('https://terminology.ivanmd.id', 'tok') extends TerminologyClient {
            public array $last = [];

            public function request(string $method, string $path, array $query = [], ?array $body = null): array
            {
                $this->last = [$method, $path, $query, $body];

                return [200, ['success' => true, 'data' => ['valid' => true, 'system' => 'snomed', 'code' => '44054006']]];
            }
        };

        $result = $client->validate('snomed', '44054006');

        $this->assertSame('POST', $client->last[0]);
        $this->assertSame(['system' => 'snomed', 'code' => '44054006'], $client->last[3]);
        $this->assertTrue($result['data']['valid']);
    }

    public function testResolveMedication(): void
    {
        $client = new class('https://terminology.ivanmd.id', 'tok') extends TerminologyClient {
            public array $last = [];

            public function request(string $method, string $path, array $query = [], ?array $body = null): array
            {
                $this->last = [$method, $path, $query, $body];

                return [200, ['success' => true, 'data' => ['code' => '93004944']]];
            }
        };

        $result = $client->resolveMedication('93004944');

        $this->assertSame('api/v1/terminology/resolve/medication', $client->last[1]);
        $this->assertSame(['code' => '93004944'], $client->last[3]);
        $this->assertSame('93004944', $result['data']['code']);
    }

    public function testHttpErrorThrows(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('HTTP 401');

        $client = new class('https://terminology.ivanmd.id', 'bad') extends TerminologyClient {
            public function request(string $method, string $path, array $query = [], ?array $body = null): array
            {
                return [401, ['success' => false, 'message' => 'Unauthorized']];
            }
        };

        $client->search('demam');
    }
}

<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderBundle;

class BundleTest extends TestCase
{
    public function test_constructor_sets_resource_type_and_timestamp()
    {
        $builder = new PayloadBuilderBundle;

        $payload = $builder->build();

        $this->assertSame('Bundle', $payload['resourceType']);
        $this->assertArrayHasKey('timestamp', $payload);
    }

    public function test_constructor_with_type_sets_bundle_type()
    {
        $builder = new PayloadBuilderBundle('document');

        $payload = $builder->build();

        $this->assertSame('document', $payload['type']);
    }

    public function test_set_type_valid_type()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setType('batch');

        $payload = $builder->build();

        $this->assertSame('batch', $payload['type']);
    }

    public function test_set_type_invalid_type_throws()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new PayloadBuilderBundle;
        $builder->setType('invalid-type');
    }

    public function test_set_id()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setId('bundle-123');

        $payload = $builder->build();

        $this->assertSame('bundle-123', $payload['id']);
    }

    public function test_set_timestamp()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setTimestamp('2024-01-15T10:00:00+00:00');

        $payload = $builder->build();

        $this->assertSame('2024-01-15T10:00:00+00:00', $payload['timestamp']);
    }

    public function test_set_total()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setTotal(42);

        $payload = $builder->build();

        $this->assertSame(42, $payload['total']);
    }

    public function test_set_meta()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setMeta(['versionId' => '1', 'lastUpdated' => '2024-01-15T10:00:00Z']);

        $payload = $builder->build();

        $this->assertSame('1', $payload['meta']['versionId']);
    }

    public function test_add_link()
    {
        $builder = new PayloadBuilderBundle;
        $builder->addLink('self', 'https://example.com/bundle');

        $payload = $builder->build();

        $this->assertArrayHasKey('link', $payload);
        $this->assertSame('self', $payload['link'][0]['relation']);
        $this->assertSame('https://example.com/bundle', $payload['link'][0]['url']);
    }

    public function test_add_multiple_links()
    {
        $builder = new PayloadBuilderBundle;
        $builder->addLink('self', 'https://example.com/bundle')
                ->addLink('next', 'https://example.com/bundle?page=2');

        $payload = $builder->build();

        $this->assertCount(2, $payload['link']);
    }

    public function test_add_pagination_links()
    {
        $builder = new PayloadBuilderBundle;
        $builder->addPaginationLinks('https://example.com/bundle', 2, 10, 55);

        $payload = $builder->build();

        $linkRelations = array_column($payload['link'], 'relation');
        $this->assertContains('self', $linkRelations);
        $this->assertContains('first', $linkRelations);
        $this->assertContains('last', $linkRelations);
        $this->assertContains('previous', $linkRelations);
        $this->assertContains('next', $linkRelations);
    }

    public function test_add_pagination_links_no_next_on_last_page()
    {
        $builder = new PayloadBuilderBundle;
        $builder->addPaginationLinks('https://example.com/bundle', 6, 10, 55);

        $payload = $builder->build();

        $linkRelations = array_column($payload['link'], 'relation');
        $this->assertContains('previous', $linkRelations);
        $this->assertNotContains('next', $linkRelations);
    }

    public function test_add_pagination_links_no_previous_on_first_page()
    {
        $builder = new PayloadBuilderBundle;
        $builder->addPaginationLinks('https://example.com/bundle', 1, 10, 55);

        $payload = $builder->build();

        $linkRelations = array_column($payload['link'], 'relation');
        $this->assertNotContains('previous', $linkRelations);
    }

    public function test_add_entry()
    {
        $resource = ['resourceType' => 'Patient', 'id' => 'p-1'];
        $builder = new PayloadBuilderBundle;
        $builder->addEntry($resource, 'http://example.com/Patient/p-1');

        $payload = $builder->build();

        $this->assertArrayHasKey('entry', $payload);
        $this->assertSame('http://example.com/Patient/p-1', $payload['entry'][0]['fullUrl']);
        $this->assertSame('Patient', $payload['entry'][0]['resource']['resourceType']);
    }

    public function test_add_entry_without_full_url()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addEntry($resource);

        $payload = $builder->build();

        $this->assertArrayNotHasKey('fullUrl', $payload['entry'][0]);
    }

    public function test_add_search_entry()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addSearchEntry($resource, 'http://example.com/Patient/p-1', 0.95, 'match');

        $payload = $builder->build();

        $this->assertArrayHasKey('search', $payload['entry'][0]);
        $this->assertSame('match', $payload['entry'][0]['search']['mode']);
        $this->assertSame(0.95, $payload['entry'][0]['search']['score']);
    }

    public function test_add_batch_entry()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addBatchEntry($resource, 'http://example.com/Patient/p-1', 'POST', 'Patient', null, null, null);

        $payload = $builder->build();

        $this->assertArrayHasKey('request', $payload['entry'][0]);
        $this->assertSame('POST', $payload['entry'][0]['request']['method']);
        $this->assertSame('Patient', $payload['entry'][0]['request']['url']);
    }

    public function test_add_batch_entry_with_if_none_match()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addBatchEntry($resource, 'http://example.com/Patient/p-1', 'PUT', 'Patient', null, '"1"', null);

        $payload = $builder->build();

        $this->assertSame('"1"', $payload['entry'][0]['request']['ifNoneMatch']);
    }

    public function test_add_history_entry()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addHistoryEntry($resource, 'http://example.com/Patient/p-1', '200', 200, 'W/"1"', '2024-01-15T10:00:00Z', null, null);

        $payload = $builder->build();

        $this->assertArrayHasKey('response', $payload['entry'][0]);
        $this->assertSame('200', $payload['entry'][0]['response']['status']);
        $this->assertSame(200, $payload['entry'][0]['response']['statusCode']);
    }

    public function test_add_entry_with_response()
    {
        $resource = ['resourceType' => 'Patient'];
        $builder = new PayloadBuilderBundle;
        $builder->addEntryWithResponse($resource, 'http://example.com/Patient/p-1', '201 Created', null, null);

        $payload = $builder->build();

        $this->assertArrayHasKey('response', $payload['entry'][0]);
        $this->assertSame('201 Created', $payload['entry'][0]['response']['status']);
    }

    public function test_add_outcome_entry()
    {
        $operationOutcome = ['resourceType' => 'OperationOutcome', 'issue' => []];
        $builder = new PayloadBuilderBundle;
        $builder->addOutcomeEntry($operationOutcome, 'http://example.com/Patient/p-1');

        $payload = $builder->build();

        $this->assertSame('outcome', $payload['entry'][0]['search']['mode']);
    }

    public function test_set_signature()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setSignature(
            'author',
            'application/fhir+json',
            base64_encode('signature-data'),
            'RS256',
            '2024-01-15T10:00:00Z',
            'Practitioner/p-1',
            null
        );

        $payload = $builder->build();

        $this->assertArrayHasKey('signature', $payload);
        $this->assertSame('author', $payload['signature']['type'][0]['code']);
        $this->assertSame('Practitioner/p-1', $payload['signature']['who']['reference']);
    }

    public function test_validate_missing_type_returns_error()
    {
        $builder = new PayloadBuilderBundle;
        $builder->noAutoTimestamp();

        $errors = $builder->validate();

        $this->assertContains('Bundle.type is required', $errors);
    }

    public function test_validate_missing_timestamp_returns_error()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setType('batch');

        // Manually unset timestamp (auto-set in constructor, no public API to disable before run)
        $reflection = new \ReflectionClass($builder);
        $prop = $reflection->getProperty('data');
        $prop->setAccessible(true);
        $data = $prop->getValue($builder);
        unset($data['timestamp']);
        $prop->setValue($builder, $data);

        $errors = $builder->validate();

        $this->assertContains('Bundle.timestamp is required', $errors);
    }

    public function test_validate_returns_empty_on_valid()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setType('document');

        $errors = $builder->validate();

        $this->assertEmpty($errors);
    }

    public function test_json_throws_on_validation_failure()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/validation failed/i');

        $builder = new PayloadBuilderBundle;
        // type not set; timestamp auto-set in constructor
        $builder->json();
    }

    public function test_json_returns_payload_on_valid()
    {
        $builder = new PayloadBuilderBundle;
        $builder->setType('document');

        $payload = $builder->json();

        $this->assertSame('Bundle', $payload['resourceType']);
        $this->assertSame('document', $payload['type']);
    }

    public function test_no_auto_timestamp_sets_flag()
    {
        $reflection = new \ReflectionClass(PayloadBuilderBundle::class);
        $prop = $reflection->getProperty('timestampAutoSet');
        $prop->setAccessible(true);

        // Default (before construction) is true
        $this->assertTrue($prop->getValue($reflection->newInstanceWithoutConstructor()));

        // After construction, flag is false (constructor calls setTimestamp which sets it)
        $builder = new PayloadBuilderBundle;
        $this->assertFalse($prop->getValue($builder));

        // noAutoTimestamp keeps it false
        $builder->noAutoTimestamp();
        $this->assertFalse($prop->getValue($builder));
    }

    public function test_set_timestamp_resets_auto_flag()
    {
        $builder = new PayloadBuilderBundle;
        $reflection = new \ReflectionClass($builder);
        $prop = $reflection->getProperty('timestampAutoSet');
        $prop->setAccessible(true);
        $this->assertFalse($prop->getValue($builder)); // constructor already set it false

        $builder->setTimestamp('2024-01-15T10:00:00+00:00');
        $this->assertFalse($prop->getValue($builder)); // remains false
    }

    public function test_chainable()
    {
        $builder = new PayloadBuilderBundle;
        $result = $builder->setId('b-1')
                          ->setType('batch')
                          ->setTimestamp('2024-01-15T10:00:00+00:00')
                          ->setTotal(5)
                          ->addLink('self', 'http://example.com')
                          ->addEntry(['resourceType' => 'Patient']);

        $this->assertInstanceOf(PayloadBuilderBundle::class, $result);
        $this->assertCount(1, $builder->build()['entry']);
    }
}

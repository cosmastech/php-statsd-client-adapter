<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\TagNormalizerSpy;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(InMemoryClientAdapter::class)]
class InMemoryTest extends BaseTestCase
{
    #[Test]
    public function getStats_returnsInMemoryStatsRecord(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // When
        $record = $inMemoryClient->getStats();

        // Then
        self::assertInstanceOf(InMemoryStatsRecord::class, $record);
        self::assertEachRecordWithinStatsRecordIsEmpty($record);
    }

    #[Test]
    public function reset_clearsStats(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // And set some data
        $inMemoryClient->increment("bogus", 1, ["tag1" => true], 99);
        $inMemoryClient->decrement("bogus", 1, ["tag1" => false], 99);
        $inMemoryClient->gauge("a gauge stat", 11444.4);
        $inMemoryClient->histogram("histogram", 259444);

        // When
        $inMemoryClient->reset();

        // Then
        self::assertEachRecordWithinStatsRecordIsEmpty($inMemoryClient->getStats());
    }

    #[Test]
    public function setTagNormalizer(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->increment("something", tags: ["my-tags" => "are-here"]);

        // Then
        $this->assertEqualsCanonicalizing([["my-tags" => "are-here"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function getClient_returnsNull(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // When
        $client = $inMemoryClient->getClient();

        // Then
        self::assertNull($client);
    }

    private static function assertEachRecordWithinStatsRecordIsEmpty(InMemoryStatsRecord $record): void
    {
        self::assertEmpty($record->distribution);
        self::assertEmpty($record->getCounts());
        self::assertEmpty($record->histogram);
        self::assertEmpty($record->set);
        self::assertEmpty($record->getTimings());
        self::assertEmpty($record->getGauges());
    }
}

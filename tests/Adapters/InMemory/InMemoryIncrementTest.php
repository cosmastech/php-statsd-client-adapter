<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryIncrementTest extends BaseTestCase
{
    #[Test]
    public function recordsCountRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2024-01-19 00:00:00");
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->increment("hello");

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->getCounts());

        $countRecord = $statsRecord->getCounts()[0];
        self::assertInstanceOf(InMemoryCountRecord::class, $countRecord);
        self::assertEquals("hello", $countRecord->stat);
        self::assertEquals(1, $countRecord->count);
        self::assertEquals($stubDateTime, $countRecord->recordedAt);
        self::assertEquals(1.0, $countRecord->sampleRate);
        self::assertEmpty($countRecord->tags);
    }

    #[Test]
    public function recordsTags(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub(new DateTimeImmutable())
        );

        // When
        $inMemoryClient->increment("hello", tags: ["abc" => 199, "xyz" => "end"]);

        // Then
        $countRecord = $inMemoryClient->getStats()->getCounts()[0];
        self::assertEqualsCanonicalizing(["abc" => 199, "xyz" => "end"], $countRecord->tags);
    }

    #[Test]
    public function normalizesTags(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub(new DateTimeImmutable())
        );

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->increment("irrelevant", tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function withDefaultTags_mergesTags(): void
    {
        // Given
        $defaultTags = ["abc" => 123];
        $inMemoryClient = new InMemoryClientAdapter(
            $defaultTags,
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub(new DateTimeImmutable())
        );

        // When
        $inMemoryClient->increment("some-stat", tags: ["hello" => "world"]);

        // Then
        $countStat = $inMemoryClient->getStats()->getCounts()[0];
        self::assertEqualsCanonicalizing(["hello" => "world", "abc" => 123], $countStat->tags);
    }
}

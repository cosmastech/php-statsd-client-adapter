<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryTimingTest extends BaseTestCase
{
    #[Test]
    public function storesTimingRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2019-02-13 07:56:00");
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->timing("timing-stat", 199, 0.2, ["timing" => "some-value"]);

        // Then
        /** @var InMemoryStatsRecord $statsRecord */
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->getTimings());

        $timingRecord = $statsRecord->getTimings()[0];
        self::assertEquals("timing-stat", $timingRecord->stat);
        self::assertEquals(199, $timingRecord->durationMilliseconds);
        self::assertEquals(0.2, $timingRecord->sampleRate);
        self::assertEqualsCanonicalizing(["timing" => "some-value"], $timingRecord->tags);
        self::assertEquals($stubDateTime, $timingRecord->recordedAt);
    }

    #[Test]
    public function normalizesTags(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            clock: new ClockStub(new DateTimeImmutable())
        );

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->timing(stat: "irrelevant", durationMs: 1000, tags: ["hello" => "world"]);

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
            clock: new ClockStub(new DateTimeImmutable())
        );

        // When
        $inMemoryClient->timing(stat: "some-stat", durationMs: 1, tags: ["hello" => "world"]);

        // Then
        $timingStat = $inMemoryClient->getStats()->getTimings()[0];
        self::assertEqualsCanonicalizing(["hello" => "world", "abc" => 123], $timingStat->tags);
    }

    #[Test]
    public function time_storesDurationOfClosure(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            clock: new ClockStub([
                new DateTimeImmutable("2024-02-13 01:01:00"),
                new DateTimeImmutable("2024-02-13 01:01:01"),
            ])
        );

        // And
        $closure = fn () => "abc";

        // When
        $actualReturn = $inMemoryClient->time($closure, "my-stat");

        // Then
        self::assertEquals("abc", $actualReturn);

        $timingRecord = $inMemoryClient->getStats()->getTimings()[0];
        self::assertEquals(1000, $timingRecord->durationMilliseconds);
    }
}

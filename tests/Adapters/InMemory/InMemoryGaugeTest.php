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

class InMemoryGaugeTest extends BaseTestCase
{
    #[Test]
    public function storesGaugeRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->gauge("gauge-stat", 23488);

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->gauge);

        $gaugeRecord = $statsRecord->gauge[0];
        self::assertEquals("gauge-stat", $gaugeRecord->stat);
        self::assertEquals(23488, $gaugeRecord->value);
        self::assertEquals(1, $gaugeRecord->sampleRate);
        self::assertEqualsCanonicalizing([], $gaugeRecord->tags);
        self::assertEquals($stubDateTime, $gaugeRecord->recordedAt);
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
        $inMemoryClient->gauge(stat: "irrelevant", value: 1.0, tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function withDefaultTags_mergesTags(): void
    {
        // Given
        $defaultTags = ["abc" => 123];
        $inMemoryClient = new InMemoryClientAdapter($defaultTags);

        // When
        $inMemoryClient->gauge("some-stat", value: 1.1, tags: ["hello" => "world"]);

        // Then
        $gaugeStat = $inMemoryClient->getStats()->gauge[0];
        self::assertEqualsCanonicalizing(["hello" => "world", "abc" => 123], $gaugeStat->tags);
    }
}

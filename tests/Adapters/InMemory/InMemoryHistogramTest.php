<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\Normalizers\NoopNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\NormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryHistogramTest extends BaseTestCase
{
    #[Test]
    public function storesHistogramRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClientAdapter(clock: new ClockStub($stubDateTime));

        // When
        $inMemoryClient->histogram(
            "histogram-stat",
            23488,
            0.55,
            ["histogram" => "yep", "has-tags" => "also yes"]
        );

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->getHistograms());

        $histogramRecord = $statsRecord->getHistograms()[0];
        self::assertEquals("histogram-stat", $histogramRecord->stat);
        self::assertEquals(23488, $histogramRecord->value);
        self::assertEquals(0.55, $histogramRecord->sampleRate);
        self::assertEqualsCanonicalizing(["histogram" => "yep", "has-tags" => "also yes"], $histogramRecord->tags);
        self::assertEquals($stubDateTime, $histogramRecord->recordedAt);
    }

    #[Test]
    public function normalizesTags(): void
    {
        // Given
        $tagNormalizerSpy = new NormalizerSpy();

        // And
        $inMemoryClient = new InMemoryClientAdapter(tagNormalizer: $tagNormalizerSpy);

        // When
        $inMemoryClient->histogram(stat: "irrelevant", value: 19.2, tags: ["hello" => "world"]);

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
            new NoopNormalizer(),
            new ClockStub(new DateTimeImmutable())
        );

        // When
        $inMemoryClient->histogram(stat: "some-stat", value: 1.2, tags: ["hello" => "world"]);

        // Then
        $histogramStat = $inMemoryClient->getStats()->getHistograms()[0];
        self::assertEqualsCanonicalizing(["hello" => "world", "abc" => 123], $histogramStat->tags);
    }
}

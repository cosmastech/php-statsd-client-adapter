<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryHistogramTest extends BaseTestCase
{
    #[Test]
    public function storesHistogramRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub($stubDateTime));

        // When
        $inMemoryClient->histogram(
            "histogram-stat",
            23488,
            0.55,
            ["histogram" => "yep", "has-tags" => "also yes"]
        );

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->histogram);

        $histogramRecord = $statsRecord->histogram[0];
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
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub(new DateTimeImmutable()));

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->histogram(stat: "irrelevant", value: 19.2, tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function withDefaultTags_mergesTags(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub(new DateTimeImmutable()));

        // And
        $inMemoryClient->withDefaultTags(["abc" => 123]);

        // When
        $inMemoryClient->histogram(stat: "some-stat", value: 1.2, tags: ["hello" => "world"]);

        // Then
        $histogramStat = $inMemoryClient->getStats()->histogram[0];
        self::assertEqualsCanonicalizing(["hello" => "world", "abc" => 123], $histogramStat->tags);
    }
}

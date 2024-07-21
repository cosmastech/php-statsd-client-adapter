<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\DataProviders\EnumProvider;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\TagNormalizerSpy;
use Cosmastech\StatsDClientAdapter\Tests\Enums\IntBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\PlainUnitEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\StringBackedEnum;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use UnitEnum;

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

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function enumAsStat_recordsStatAsString(UnitEnum $case, string $converted): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // When
        $inMemoryClient->increment($case);

        // Then
        $countStat = $inMemoryClient->getStats()->getCounts()[0];
        self::assertEqualsCanonicalizing($converted, $countStat->stat);
    }

    #[Test]
    public function arrayOfEnumsAsStat_recordsStatsAsStrings(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // When
        $inMemoryClient->increment([IntBackedEnum::TWO, StringBackedEnum::A, PlainUnitEnum::FIRST, "hello"], value: 1994);

        // Then
        self::assertCount(
            4,
            /** @var array<int, InMemoryCountRecord> $countStats */
            $countStats = $inMemoryClient->getStats()->getCounts()
        );
        self::assertEquals("2", $countStats[0]->stat);
        self::assertEquals(1994, $countStats[0]->count);
        self::assertEquals("a", $countStats[1]->stat);
        self::assertEquals(1994, $countStats[1]->count);
        self::assertEquals("FIRST", $countStats[2]->stat);
        self::assertEquals(1994, $countStats[2]->count);
        self::assertEquals("hello", $countStats[3]->stat);
        self::assertEquals(1994, $countStats[3]->count);
    }
}

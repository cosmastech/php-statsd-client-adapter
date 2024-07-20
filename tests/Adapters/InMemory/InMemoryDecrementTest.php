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

class InMemoryDecrementTest extends BaseTestCase
{
    #[Test]
    public function recordsCountRecord(): void
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2024-02-18 14:22:19");

        $inMemoryClient = new InMemoryClientAdapter(
            [],
            new InMemoryStatsRecord(),
            new NoopTagNormalizer(),
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->decrement("goodbye", sampleRate: 0.1, tags: ["ringo" => "drummer"], value: -129);

        // Then
        $countStat = $inMemoryClient->getStats()->getCounts()[0];
        self::assertEquals(-129, $countStat->count);
        self::assertEquals("goodbye", $countStat->stat);
        self::assertEqualsCanonicalizing(["ringo" => "drummer"], $countStat->tags);
        self::assertEquals($stubDateTime, $countStat->recordedAt);
        self::assertEquals(0.1, $countStat->sampleRate);
    }

    #[Test]
    public function normalizesTags(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->decrement("irrelevant", tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function positiveValue_convertsToANegativeNumber(): void
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter();

        // When
        $inMemoryClient->decrement("some-stat", value: 1845);

        // Then
        $countStat = $inMemoryClient->getStats()->getCounts()[0];
        self::assertEquals(-1845, $countStat->count);
    }

    #[Test]
    public function withDefaultTags_mergesTags(): void
    {
        // Given
        $defaultTags = ["abc" => 123];
        $inMemoryClient = new InMemoryClientAdapter($defaultTags);

        // When
        $inMemoryClient->decrement("some-stat", tags: ["hello" => "world"]);

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
        $inMemoryClient->decrement($case, value: -144);

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
        $inMemoryClient->decrement([IntBackedEnum::TWO, StringBackedEnum::A, PlainUnitEnum::FIRST], value: 1994);

        // Then
        self::assertCount(
            3,
            /** @var array<int, InMemoryCountRecord> $countStats */
            $countStats = $inMemoryClient->getStats()->getCounts()
        );
        self::assertEquals("2", $countStats[0]->stat);
        self::assertEquals(-1994, $countStats[0]->count);
        self::assertEquals("a", $countStats[1]->stat);
        self::assertEquals(-1994, $countStats[1]->count);
        self::assertEquals("FIRST", $countStats[2]->stat);
        self::assertEquals(-1994, $countStats[2]->count);
    }
}

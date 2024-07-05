<?php

namespace Cosmastech\StatsDClient\Tests\Adapters\InMemory;

use Cosmastech\StatsDClient\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClient\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryDecrementTest extends BaseTestCase
{
    #[Test]
    public function recordsCountRecord()
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2024-02-18 14:22:19");

        $inMemoryClient = new InMemoryClientAdapter(
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->decrement("goodbye", sampleRate: 0.1, tags: ["ringo" => "drummer"], value: -129);

        // Then
        $countStat = $inMemoryClient->getStats()->count[0];
        self::assertEquals(-129, $countStat->count);
        self::assertEquals("goodbye", $countStat->stat);
        self::assertEqualsCanonicalizing(["ringo" => "drummer"], $countStat->tags);
        self::assertEquals($stubDateTime, $countStat->recordedAt);
        self::assertEquals(0.1, $countStat->sampleRate);
    }

    #[Test]
    public function normalizesTags()
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub(new DateTimeImmutable()));

        // And
        $tagNormalizerSpy = new TagNormalizerSpy();
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->decrement("irrelevant", tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }

    #[Test]
    public function positiveValue_convertsToANegativeNumber()
    {
        // Given
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub(new DateTimeImmutable()));

        // When
        $inMemoryClient->decrement("some-stat", value: 1845);

        // Then
        $countStat = $inMemoryClient->getStats()->count[0];
        self::assertEquals(-1845, $countStat->count);
    }
}

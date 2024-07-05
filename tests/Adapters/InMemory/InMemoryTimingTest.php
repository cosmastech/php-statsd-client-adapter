<?php

namespace Cosmastech\StatsDClient\Tests\Adapters\InMemory;

use Cosmastech\StatsDClient\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\Doubles\ClockStub;
use Cosmastech\StatsDClient\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryTimingTest extends BaseTestCase
{
    #[Test]
    public function storesTimingRecord()
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2019-02-13 07:56:00");
        $inMemoryClient = new InMemoryClientAdapter(new ClockStub($stubDateTime));

        // When
        $inMemoryClient->timing("timing-stat", 199, 0.2, ["timing" => "some-value"]);

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->timing);

        $timingRecord = $statsRecord->timing[0];
        self::assertEquals("timing-stat", $timingRecord->stat);
        self::assertEquals(199, $timingRecord->durationMilliseconds);
        self::assertEquals(0.2, $timingRecord->sampleRate);
        self::assertEqualsCanonicalizing(["timing" => "some-value"], $timingRecord->tags);
        self::assertEquals($stubDateTime, $timingRecord->recordedAt);
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
        $inMemoryClient->timing(stat: "irrelevant", durationMs: 1000, tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }
}

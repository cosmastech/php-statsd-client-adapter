<?php

namespace Cosmastech\StatsDClient\Tests\Clients\InMemory;

use Cosmastech\StatsDClient\Clients\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\Clients\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryIncrementTest extends BaseTestCase
{
    #[Test]
    public function recordsCountRecord()
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2024-01-19 00:00:00");
        $inMemoryClient = new InMemoryClient(
            new ClockStub($stubDateTime)
        );

        // When
        $inMemoryClient->increment("hello");

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->count);

        $countRecord = $statsRecord->count[0];
        self::assertInstanceOf(InMemoryCountRecord::class, $countRecord);
        self::assertEquals("hello", $countRecord->stat);
        self::assertEquals(1, $countRecord->count);
        self::assertEquals($stubDateTime, $countRecord->recordedAt);
        self::assertEquals(1.0, $countRecord->sampleRate);
        self::assertEmpty($countRecord->tags);
    }

    #[Test]
    public function recordsTags()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub(new DateTimeImmutable));

        // When
        $inMemoryClient->increment("hello", tags: ["abc" => 199, "xyz" => "end"]);

        // Then
        $countRecord = $inMemoryClient->getStats()->count[0];
        self::assertEqualsCanonicalizing(["abc" => 199, "xyz" => "end"], $countRecord->tags);
    }
}
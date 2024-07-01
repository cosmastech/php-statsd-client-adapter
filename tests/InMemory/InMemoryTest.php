<?php

namespace Cosmastech\StatsDClient\Tests\InMemory;

use Cosmastech\StatsDClient\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClient\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryTest extends BaseTestCase
{
    private readonly DateTimeImmutable $stubDatetime;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->stubDatetime = new DateTimeImmutable("2024-01-19 00:00:00");
    }

    #[Test]
    public function getStats_returnsInMemoryStatsRecord()
    {
        // Given
        $clock = new ClockStub($this->stubDatetime);

        $inMemoryClient = new InMemoryClient($clock);

        // When
        $record = $inMemoryClient->getStats();

        // Then
        self::assertInstanceOf(InMemoryStatsRecord::class, $record);
        self::assertEmpty($record->distribution);
        self::assertEmpty($record->count);
        self::assertEmpty($record->histogram);
        self::assertEmpty($record->set);
        self::assertEmpty($record->timing);
        self::assertEmpty($record->gauge);
    }

    #[Test]
    public function increment_recordsCountRecord()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub($this->stubDatetime));

        // When
        $inMemoryClient->increment("hello");

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->count);

        $countRecord = $statsRecord->count[0];
        self::assertInstanceOf(InMemoryCountRecord::class, $countRecord);
        self::assertEquals("hello", $countRecord->stat);
        self::assertEquals(1, $countRecord->count);
        self::assertEquals($this->stubDatetime, $countRecord->recordedAt);
        self::assertEquals(1.0, $countRecord->sampleRate);
        self::assertEmpty($countRecord->tags);
    }

    #[Test]
    public function increment_recordsTags()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub($this->stubDatetime));

        // When
        $inMemoryClient->increment("hello", tags: ["abc" => 199, "xyz" => "end"]);

        // Then
        $countRecord = $inMemoryClient->getStats()->count[0];
        self::assertEqualsCanonicalizing(["abc" => 199, "xyz" => "end"], $countRecord->tags);
    }



}
<?php

namespace Cosmastech\StatsDClient\Tests\Clients\InMemory;

use Cosmastech\StatsDClient\Clients\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\Clients\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryTest extends BaseTestCase
{
    #[Test]
    public function getStats_returnsInMemoryStatsRecord()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub(new DateTimeImmutable));

        // When
        $record = $inMemoryClient->getStats();

        // Then
        self::assertInstanceOf(InMemoryStatsRecord::class, $record);
        self::assertEachRecordWithinStatsRecordIsEmpty($record);
    }

    #[Test]
    public function reset_clearsStats()
    {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub(new DateTimeImmutable));

        // And set some data
        $inMemoryClient->increment("bogus", 1, ["tag1" => true], 99);
        $inMemoryClient->decrement("bogus", 1, ["tag1" => false], 99);
        $inMemoryClient->gauge("a gauge stat", 11444.4);
        $inMemoryClient->histogram("histogram", 259444);

        // When
        $inMemoryClient->reset();

        // Then
        self::assertEachRecordWithinStatsRecordIsEmpty($inMemoryClient->getStats());
    }

    private static function assertEachRecordWithinStatsRecordIsEmpty(InMemoryStatsRecord $record): void
    {
        self::assertEmpty($record->distribution);
        self::assertEmpty($record->count);
        self::assertEmpty($record->histogram);
        self::assertEmpty($record->set);
        self::assertEmpty($record->timing);
        self::assertEmpty($record->gauge);
    }
}
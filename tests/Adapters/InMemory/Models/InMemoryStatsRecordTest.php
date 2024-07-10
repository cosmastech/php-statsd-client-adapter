<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\InMemory\Models;

use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryCountRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryDistributionRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryGaugeRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryHistogramRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemorySetRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(InMemoryStatsRecord::class)]
class InMemoryStatsRecordTest extends BaseTestCase
{
    #[Test]
    public function flush_emptiesAllInMemoryRecords(): void
    {
        // Given
        $record = new InMemoryStatsRecord();

        // And
        $this->fillStatsRecord($record);

        // When
        $record->flush();

        // Then
        self::assertEquals([], $record->getTimings());
        self::assertEquals([], $record->getCounts());
        self::assertEquals([], $record->getGauges());
        self::assertEquals([], $record->set);
        self::assertEquals([], $record->histogram);
        self::assertEquals([], $record->distribution);
    }

    private function fillStatsRecord(InMemoryStatsRecord $record): void
    {
        $record->recordTiming(
            new InMemoryTimingRecord(
                "irrelevant",
                99.2,
                1.0,
                ["key" => "value"],
                new DateTimeImmutable()
            )
        );

        $record->recordCount(
            new InMemoryCountRecord(
                "irrelevant-count",
                23,
                0.2,
                [],
                new DateTimeImmutable()
            )
        );

        $record->recordGauge(
            new InMemoryGaugeRecord(
                "irrelevant",
                1.3,
                0.01,
                ["k" => "v"],
                new DateTimeImmutable()
            )
        );

        $record->set[] = new InMemorySetRecord(
            "irrelevant",
            "abc",
            1.0,
            [],
            new DateTimeImmutable()
        );

        $record->histogram[] = new InMemoryHistogramRecord(
            "irrelevant",
            0.4,
            0.45,
            [],
            new DateTimeImmutable()
        );
        $record->distribution[] = new InMemoryDistributionRecord(
            "irrelevant",
            1.0,
            1.0,
            [],
            new DateTimeImmutable()
        );
    }
}

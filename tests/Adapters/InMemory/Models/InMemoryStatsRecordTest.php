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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(InMemoryStatsRecord::class)]
class InMemoryStatsRecordTest extends BaseTestCase
{
    #[Test]
    #[DataProvider("recordMethodDataProvider")]
    public function record_storesRecord(object $metricRecord, string $recordMethodName, string $getMethodName): void
    {
        // Given
        $record = new InMemoryStatsRecord();

        // When
        $record->{$recordMethodName}($metricRecord);

        // Then
        $metricRecordings = $record->{$getMethodName}();
        self::assertCount(1, $metricRecordings);
        self::assertSame($metricRecord, $metricRecordings[0]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function recordMethodDataProvider(): array
    {
        $datetime = new DateTimeImmutable();

        return [
            "timing" => [
                new InMemoryTimingRecord("irrelevant", 12.1, 1.0, [], $datetime),
                "recordTiming",
                "getTimings",
            ],
            "count" => [
                new InMemoryCountRecord("irrelevant", 483, 0.99, [], $datetime),
                "recordCount",
                "getCounts",
            ],
            "gauge" => [
                new InMemoryGaugeRecord("irrelevant", 33.2, 1.0, [], $datetime),
                "recordGauge",
                "getGauges",
            ],
            "set" => [
                new InMemorySetRecord("irrelevant-set", "unique value", 1.33, [], $datetime),
                "recordSet",
                "getSets",
            ],
            "histogram" => [
                new InMemoryHistogramRecord("histy", 111, 1.0, ["key" => "value"], $datetime),
                "recordHistogram",
                "getHistograms",
            ],
            "distribution" => [
                new InMemoryDistributionRecord("irrelevant", 0.3, 0.3, [], $datetime),
                "recordDistribution",
                "getDistributions"
            ],
        ];
    }
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
        self::assertEquals([], $record->getSets());
        self::assertEquals([], $record->getHistograms());
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

        $record->recordSet(
            new InMemorySetRecord(
                "irrelevant",
                "abc",
                1.0,
                [],
                new DateTimeImmutable()
            )
        );

        $record->recordHistogram(new InMemoryHistogramRecord(
            "irrelevant",
            0.4,
            0.45,
            [],
            new DateTimeImmutable()
        ));

        $record->distribution[] = new InMemoryDistributionRecord(
            "irrelevant",
            1.0,
            1.0,
            [],
            new DateTimeImmutable()
        );
    }
}

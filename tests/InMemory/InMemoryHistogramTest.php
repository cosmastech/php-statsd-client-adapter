<?php

namespace Cosmastech\StatsDClient\Tests\InMemory;

use Cosmastech\StatsDClient\Clients\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemoryHistogramTest extends BaseTestCase
{
    #[Test]
    public function storesHistogramRecord()
    {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClient(new ClockStub($stubDateTime));

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
}
<?php

namespace Cosmastech\StatsDClient\Tests\Clients\InMemory;

use Cosmastech\StatsDClient\Clients\InMemory\InMemoryClient;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use Cosmastech\StatsDClient\Tests\ClockStub;
use Cosmastech\StatsDClient\Tests\Doubles\TagNormalizerSpy;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;

class InMemorySetTest extends BaseTestCase
{
    #[Test]
    public function storesSetRecord() {
        // Given
        $stubDateTime = new DateTimeImmutable("2018-02-13 18:50:00");
        $inMemoryClient = new InMemoryClient(new ClockStub($stubDateTime));

        // When
        $inMemoryClient->set("set-test", 14);

        // Then
        $statsRecord = $inMemoryClient->getStats();
        self::assertCount(1, $statsRecord->set);

        $setRecord = $statsRecord->set[0];
        self::assertEquals("set-test", $setRecord->stat);
        self::assertEquals(14, $setRecord->value);
        self::assertEquals(1, $setRecord->sampleRate);
        self::assertEqualsCanonicalizing([], $setRecord->tags);
        self::assertEquals($stubDateTime, $setRecord->recordedAt);
    }

    #[Test]
    public function normalizesTags() {
        // Given
        $inMemoryClient = new InMemoryClient(new ClockStub(new DateTimeImmutable));

        // And
        $tagNormalizerSpy = new TagNormalizerSpy;
        $inMemoryClient->setTagNormalizer($tagNormalizerSpy);

        // When
        $inMemoryClient->set(stat: "irrelevant", value: 11, tags: ["hello" => "world"]);

        // Then
        $this->assertEqualsCanonicalizing([["hello" => "world"]], $tagNormalizerSpy->getNormalizeCalls());
    }
}
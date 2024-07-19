<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\StatsClientAware;

use Cosmastech\StatsDClientAdapter\Adapters\Concerns\StatsClientAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\StatsClientAwareInterface;
use Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\Test;

class StatsClientAwareTraitTest extends BaseTestCase
{
    #[Test]
    public function classHasStatsClientAware_canSetStatsClient(): void
    {
        // Given
        $wantsStatsClient = self::makeWantsStatsClientClass();

        // And
        $statsDClientAdapter = new InMemoryClientAdapter();

        // When
        $wantsStatsClient->setStatsClient($statsDClientAdapter);

        // Then
        self::assertSame(
            $statsDClientAdapter,
            $wantsStatsClient->getStatsClient() /** @phpstan-ignore method.notFound (this is a method on an anonymous class) */
        );
    }

    private static function makeWantsStatsClientClass(): StatsClientAwareInterface
    {
        return new class () implements StatsClientAwareInterface {
            use StatsClientAwareTrait;
            public function getStatsClient(): StatsDClientAdapter
            {
                return $this->statsClient;
            }
        };
    }
}

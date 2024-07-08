<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\League;

use Cosmastech\StatsDClientAdapter\Adapters\League\LeagueStatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use League\StatsD\StatsDClient;
use PHPUnit\Framework\Attributes\Test;

class LeagueStatsDClientTest extends BaseTestCase
{
    #[Test]
    public function getClient_returnsLeagueStatsDClient(): void
    {
        // Given
        $leagueStatsDClient = LeagueStatsDClientAdapter::fromConfig([]);

        // When
        $client = $leagueStatsDClient->getClient();

        // Then
        self::assertInstanceOf(StatsDClient::class, $client);
    }
}

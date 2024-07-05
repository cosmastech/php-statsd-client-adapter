<?php

namespace Cosmastech\StatsDClient\Tests\Adapters\League;

use Cosmastech\StatsDClient\Adapters\League\LeagueStatsDClientAdapter;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use League\StatsD\StatsDClient;
use PHPUnit\Framework\Attributes\Test;

class LeagueStatsDClientTest extends BaseTestCase
{
    #[Test]
    public function getClient_returnsLeagueStatsDClient()
    {
        // Given
        $leagueStatsDClient = LeagueStatsDClientAdapter::fromConfig([]);

        // When
        $client = $leagueStatsDClient->getClient();

        // Then
        self::assertInstanceOf(StatsDClient::class, $client);
    }
}

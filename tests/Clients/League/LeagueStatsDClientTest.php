<?php

namespace Cosmastech\StatsDClient\Tests\Clients\League;

use Cosmastech\StatsDClient\Clients\League\LeagueStatsDClient;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use League\StatsD\StatsDClient;
use PHPUnit\Framework\Attributes\Test;

class LeagueStatsDClientTest extends BaseTestCase
{
    #[Test]
    public function getClient_returnsLeagueStatsDClient()
    {
        // Given
        $leagueStatsDClient = LeagueStatsDClient::fromConfig([]);

        // When
        $client = $leagueStatsDClient->getClient();

        // Then
        self::assertInstanceOf(StatsDClient::class, $client);
    }
}

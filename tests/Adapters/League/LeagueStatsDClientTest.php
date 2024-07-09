<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\League;

use Cosmastech\StatsDClientAdapter\Adapters\League\LeagueStatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use League\StatsD\StatsDClient;
use PHPUnit\Framework\Attributes\Test;

class LeagueStatsDClientTest extends BaseTestCase
{
    protected array $args;
    protected function setUp(): void
    {
        parent::setUp();

        $this->args = [];
    }

    #[Test]
    public function getClient_returnsLeagueStatsDClient(): void
    {
        // Given
        $leagueStatsDClientAdapter = LeagueStatsDClientAdapter::fromConfig([]);

        // When
        $client = $leagueStatsDClientAdapter->getClient();

        // Then
        self::assertInstanceOf(StatsDClient::class, $client);
    }

    #[Test]
    public function setUnavailableStatHandler_histogram_callsClosure(): void
    {
        // Given
        $leagueStatsDClientAdapter = LeagueStatsDClientAdapter::fromConfig([]);

        // And
        $leagueStatsDClientAdapter->setUnavailableStatHandler($this->saveArgs(...));

        // When
        $leagueStatsDClientAdapter->histogram("some-stat", 12, 0.1, ["my_tag" => true]);

        // Then
        self::assertEquals("some-stat", $this->args[0]);
        self::assertEquals(12, $this->args[1]);
        self::assertEquals(0.1, $this->args[2]);
        self::assertEquals(["my_tag" => true], $this->args[3]);
    }

    private function saveArgs(): void
    {
        $this->args = func_get_args();
    }
}

<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;

trait StatsClientAwareTrait
{
    protected StatsDClientAdapter $statsClient;

    public function setStatsClient(StatsDClientAdapter $statsDClientAdapter): void
    {
        $this->statsClient = $statsDClientAdapter;
    }
}

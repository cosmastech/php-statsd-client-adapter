<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Contracts;

use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;

interface StatsClientAwareInterface
{
    public function setStatsClient(StatsDClientAdapter $statsDClientAdapter): void;
}

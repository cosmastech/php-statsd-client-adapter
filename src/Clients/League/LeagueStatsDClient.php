<?php

namespace Cosmastech\StatsDClient\Clients\League;

use Cosmastech\StatsDClient\Clients\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClient\Clients\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClient\Clients\StatsDClient;
use League\StatsD\Client;
use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Exception\ConnectionException;
use League\StatsD\StatsDClient as LeagueStatsDClientInterface;

class LeagueStatsDClient implements StatsDClient, TagNormalizerAware
{
    use TagNormalizerAwareTrait;

    public function __construct(private readonly LeagueStatsDClientInterface $leagueStatsDClient)
    {
    }

    /**
     * @throws ConfigurationException
     */
    public static function fromConfig(array $config, string $instanceName = 'default'): static
    {
        $instance = Client::instance($instanceName);
        $instance->configure($config);

        return new static($instance);
    }

    /**
     * @throws ConnectionException
     */
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->shouldRecord($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->timing($stat, $durationMs, $this->normalizeTags($tags));
    }

    /**
     * @throws ConnectionException
     */
    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->shouldRecord($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->gauge($stat, $value, $tags);
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        trigger_error("histogram is not implemented for this client");
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        trigger_error("distribution is not implemented for this client");
    }

    /**
     * @throws ConnectionException
     */
    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->shouldRecord($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->set($stat, $value, $tags);
    }

    /**
     * @throws ConnectionException
     */
    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->leagueStatsDClient->increment($stats, $value, $sampleRate, $tags);
    }

    /**
     * @throws ConnectionException
     */
    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->leagueStatsDClient->decrement($stats, $value, $sampleRate, $tags);
    }

    /**
     * @throws ConnectionException
     */
    public function updateStats(array|string $stats, int $delta = 1, $sampleRate = 1.0, $tags = null): void
    {
        $this->increment($stats, $sampleRate, $tags, $delta);
    }

    protected function shouldRecord(float $sampleRate): bool
    {
        if ($sampleRate >= 1) {
            return true;
        }

        if ((mt_rand() / mt_getrandmax()) <= $sampleRate) {
            return true;
        }

        return false;
    }
}

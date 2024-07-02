<?php

namespace Cosmastech\StatsDClient\Clients\League;

use Cosmastech\StatsDClient\Clients\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClient\Clients\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClient\Clients\StatsDClient;
use Cosmastech\StatsDClient\Utility\SampleRateDecider\Contracts\SampleRateSendDecider as SampleRateSendDeciderInterface;
use Cosmastech\StatsDClient\Utility\SampleRateDecider\SampleRateSendDecider;
use League\StatsD\Client;
use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Exception\ConnectionException;
use League\StatsD\StatsDClient as LeagueStatsDClientInterface;

class LeagueStatsDClient implements StatsDClient, TagNormalizerAware
{
    use TagNormalizerAwareTrait;

    public function __construct(
        protected readonly LeagueStatsDClientInterface $leagueStatsDClient,
        protected readonly SampleRateSendDeciderInterface $sampleRateSendDecider
    ) {
    }

    /**
     * @throws ConfigurationException
     */
    public static function fromConfig(
        array $config,
        string $instanceName = 'default',
        ?SampleRateSendDeciderInterface $sampleRateSendDecider = null
    ): static {
        $instance = Client::instance($instanceName);
        $instance->configure($config);

        return new static($instance, $sampleRateSendDecider ?? new SampleRateSendDecider());
    }

    /**
     * @throws ConnectionException
     */
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->timing($stat, $durationMs, $this->normalizeTags($tags));
    }

    /**
     * @throws ConnectionException
     */
    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
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
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
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

    public function getClient(): LeagueStatsDClientInterface
    {
        return $this->leagueStatsDClient;
    }
}

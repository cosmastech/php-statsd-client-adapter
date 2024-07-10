<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\League;

use Closure;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TimeClosureTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;
use Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\Contracts\SampleRateSendDecider as SampleRateSendDeciderInterface;
use Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\SampleRateSendDecider;
use League\StatsD\Client;
use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Exception\ConnectionException;
use League\StatsD\StatsDClient as LeagueStatsDClientInterface;

class LeagueStatsDClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;
    use TimeClosureTrait;

    /**
     * @var Closure(string, float, float, array<mixed, mixed>):void
     */
    protected Closure $unavailableStatHandler;

    /**
     * @param  LeagueStatsDClientInterface  $leagueStatsDClient
     * @param  SampleRateSendDeciderInterface  $sampleRateSendDecider
     * @param  array<mixed, mixed>  $defaultTags
     * @param  TagNormalizer  $tagNormalizer
     */
    public function __construct(
        protected readonly LeagueStatsDClientInterface $leagueStatsDClient,
        protected readonly SampleRateSendDeciderInterface $sampleRateSendDecider = new SampleRateSendDecider(),
        array $defaultTags = [],
        TagNormalizer $tagNormalizer = new NoopTagNormalizer(),
    ) {
        $this->setDefaultTags($defaultTags);
        $this->setTagNormalizer($tagNormalizer);
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  string  $instanceName
     * @param  SampleRateSendDeciderInterface|null  $sampleRateSendDecider
     * @param  array<mixed, mixed>  $defaultTags
     * @return self
     *
     * @throws ConfigurationException
     */
    public static function fromConfig(
        array $config,
        string $instanceName = 'default',
        ?SampleRateSendDeciderInterface $sampleRateSendDecider = null,
        array $defaultTags = []
    ): self {
        /** @var Client $instance */
        $instance = Client::instance($instanceName);
        $instance->configure($config);

        return new self(
            $instance,
            $sampleRateSendDecider ?? new SampleRateSendDecider(),
            $defaultTags
        );
    }

    /**
     * @param  Closure(string, float, float, array<mixed, mixed>):void  $closure
     * @return self
     */
    public function setUnavailableStatHandler(Closure $closure): self
    {
        $this->unavailableStatHandler = $closure;

        return $this;
    }

    /**
     * @param  string  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    protected function handleUnavailableStat(
        string $stat,
        float $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        $this->getUnavailableStatHandler()($stat, $value, $sampleRate, $tags);
    }

    /**
     * @return Closure(string, float, float, array<mixed, mixed>):void
     */
    protected function getUnavailableStatHandler(): Closure
    {
        return $this->unavailableStatHandler ?? function (): void {};
    }


    /**
     * @param  string  $stat
     * @param  float  $durationMs
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     *
     * @throws ConnectionException
     */
    public function timing(string $stat, float $durationMs, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->timing(
            $stat,
            $durationMs,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function gauge(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->gauge(
            $stat,
            $value,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    public function histogram(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->handleUnavailableStat($stat, $value, $sampleRate, $tags);
    }

    public function distribution(string $stat, float $value, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->handleUnavailableStat($stat, $value, $sampleRate, $tags);
    }

    /**
     * @throws ConnectionException
     */
    public function set(string $stat, float|string $value, float $sampleRate = 1.0, array $tags = []): void
    {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->set(
            $stat,
            $value,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function increment(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->leagueStatsDClient->increment(
            $stats,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function decrement(array|string $stats, float $sampleRate = 1.0, array $tags = [], int $value = 1): void
    {
        $this->leagueStatsDClient->decrement(
            $stats,
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function updateStats(array|string $stats, int $delta = 1, float $sampleRate = 1.0, array $tags = []): void
    {
        $this->increment(
            $stats,
            $sampleRate,
            $this->normalizeTags($this->mergeTags($tags)),
            $delta
        );
    }

    public function getClient(): LeagueStatsDClientInterface
    {
        return $this->leagueStatsDClient;
    }
}

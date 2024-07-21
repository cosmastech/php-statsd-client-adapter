<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\League;

use Closure;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\ConvertsStatTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\HasDefaultTagsTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TagNormalizerAwareTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Concerns\TimeClosureTrait;
use Cosmastech\StatsDClientAdapter\Adapters\Contracts\TagNormalizerAware;
use Cosmastech\StatsDClientAdapter\Adapters\StatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\TagNormalizers\NoopTagNormalizer;
use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;
use Cosmastech\StatsDClientAdapter\Utility\Clock;
use Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\Contracts\SampleRateSendDecider as SampleRateSendDeciderInterface;
use Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\SampleRateSendDecider;
use League\StatsD\Client;
use League\StatsD\Exception\ConfigurationException;
use League\StatsD\Exception\ConnectionException;
use League\StatsD\StatsDClient as LeagueStatsDClientInterface;
use Psr\Clock\ClockInterface;
use UnitEnum;

class LeagueStatsDClientAdapter implements StatsDClientAdapter, TagNormalizerAware
{
    use ConvertsStatTrait;
    use HasDefaultTagsTrait;
    use TagNormalizerAwareTrait;
    use TimeClosureTrait;

    protected readonly LeagueStatsDClientInterface $leagueStatsDClient;

    protected readonly SampleRateSendDeciderInterface $sampleRateSendDecider;

    protected readonly ClockInterface $clock;

    /**
     * @var Closure(string, float, float, array<mixed, mixed>):void
     */
    protected Closure $unavailableStatHandler;

    /**
     * @param  LeagueStatsDClientInterface  $leagueStatsDClient
     * @param  array<mixed, mixed>  $defaultTags
     * @param  SampleRateSendDeciderInterface  $sampleRateSendDecider
     * @param  TagNormalizer  $tagNormalizer
     * @param  ClockInterface  $clock
     */
    public function __construct(
        LeagueStatsDClientInterface $leagueStatsDClient,
        array $defaultTags = [],
        SampleRateSendDeciderInterface $sampleRateSendDecider = new SampleRateSendDecider(),
        TagNormalizer $tagNormalizer = new NoopTagNormalizer(),
        ClockInterface $clock = new Clock(),
    ) {
        $this->leagueStatsDClient = $leagueStatsDClient;
        $this->setDefaultTags($defaultTags);
        $this->sampleRateSendDecider = $sampleRateSendDecider;
        $this->setTagNormalizer($tagNormalizer);
        $this->clock = $clock;
    }

    /**
     * @param  array<string, mixed>  $config
     * @param  array<mixed, mixed>  $defaultTags
     * @param  SampleRateSendDeciderInterface  $sampleRateSendDecider
     * @param  string  $instanceName
     * @param  TagNormalizer  $tagNormalizer
     * @param  ClockInterface  $clock
     * @return self
     *
     * @throws ConfigurationException
     */
    public static function fromConfig(
        array $config,
        array $defaultTags = [],
        SampleRateSendDeciderInterface $sampleRateSendDecider = new SampleRateSendDecider(),
        string $instanceName = 'default',
        TagNormalizer $tagNormalizer = new NoopTagNormalizer(),
        ClockInterface $clock = new Clock(),
    ): self {
        /** @var Client $instance */
        $instance = Client::instance($instanceName);
        $instance->configure($config);

        return new self(
            $instance,
            $defaultTags,
            $sampleRateSendDecider,
            $tagNormalizer,
            $clock
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
     * @param  string|UnitEnum  $stat
     * @param  float  $value
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     */
    protected function handleUnavailableStat(
        string|UnitEnum $stat,
        float $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        $this->getUnavailableStatHandler()(
            $this->convertStat($stat),
            $value,
            $sampleRate,
            $tags
        );
    }

    /**
     * @return Closure(string, float, float, array<mixed, mixed>):void
     */
    protected function getUnavailableStatHandler(): Closure
    {
        return $this->unavailableStatHandler ?? function (): void {};
    }

    /**
     * @param  string|UnitEnum  $stat
     * @param  float  $durationMs
     * @param  float  $sampleRate
     * @param  array<mixed, mixed>  $tags
     * @return void
     *
     * @throws ConnectionException
     */
    public function timing(
        string|UnitEnum $stat,
        float $durationMs,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->timing(
            $this->convertStat($stat),
            $durationMs,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function gauge(
        string|UnitEnum $stat,
        float $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->gauge(
            $this->convertStat($stat),
            $value,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    public function histogram(
        string|UnitEnum $stat,
        float $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        $this->handleUnavailableStat($stat, $value, $sampleRate, $tags);
    }

    public function distribution(
        string|UnitEnum $stat,
        float $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        $this->handleUnavailableStat($stat, $value, $sampleRate, $tags);
    }

    /**
     * @throws ConnectionException
     */
    public function set(
        string|UnitEnum $stat,
        float|string $value,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        if (! $this->sampleRateSendDecider->decide($sampleRate)) {
            return;
        }

        $this->leagueStatsDClient->set(
            $this->convertStat($stat),
            $value,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function increment(
        array|string|UnitEnum $stats,
        float $sampleRate = 1.0,
        array $tags = [],
        int $value = 1
    ): void {
        $this->leagueStatsDClient->increment(
            $this->convertStat($stats),
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function decrement(
        array|string|UnitEnum $stats,
        float $sampleRate = 1.0,
        array $tags = [],
        int $value = 1
    ): void {
        $this->leagueStatsDClient->decrement(
            $this->convertStat($stats),
            $value,
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags))
        );
    }

    /**
     * @throws ConnectionException
     */
    public function updateStats(
        array|string|UnitEnum $stats,
        int $delta = 1,
        float $sampleRate = 1.0,
        array $tags = []
    ): void {
        $this->increment(
            $this->convertStat($stats),
            $sampleRate,
            $this->normalizeTags($this->mergeWithDefaultTags($tags)),
            $delta
        );
    }

    public function getClient(): LeagueStatsDClientInterface
    {
        return $this->leagueStatsDClient;
    }
}

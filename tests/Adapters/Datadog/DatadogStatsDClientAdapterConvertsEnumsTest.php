<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Adapters\Datadog;

use Cosmastech\StatsDClientAdapter\Adapters\Datadog\DatadogStatsDClientAdapter;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use Cosmastech\StatsDClientAdapter\Tests\DataProviders\EnumProvider;
use Cosmastech\StatsDClientAdapter\Tests\Doubles\DogStatsDSpy;
use Cosmastech\StatsDClientAdapter\Tests\Enums\IntBackedEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\PlainUnitEnum;
use Cosmastech\StatsDClientAdapter\Tests\Enums\StringBackedEnum;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Test;
use UnitEnum;

class DatadogStatsDClientAdapterConvertsEnumsTest extends BaseTestCase
{
    private DogStatsDSpy $dogStatsSpy;
    private DatadogStatsDClientAdapter $adapter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dogStatsSpy = new DogStatsDSpy();
        $this->adapter = new DatadogStatsDClientAdapter($this->dogStatsSpy);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function timing_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->timing($case, 100);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->timings[0]['stat']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function gauge_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->gauge($case, 100);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->gauges[0]['stat']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function histogram_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->histogram($case, 100);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->histograms[0]['stat']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function distribution_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->distribution($case, 100);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->distributions[0]['stat']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function set_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->set($case, 1);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->sets[0]['stat']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function increment_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->increment($case);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->increments[0]['stats']);
    }

    #[Test]
    public function increment_convertsArrayToStrings(): void
    {
        // When
        $this->adapter->increment([IntBackedEnum::TWO, PlainUnitEnum::SECOND, StringBackedEnum::B, "hello"]);

        // Then
        self::assertEqualsCanonicalizing(["2", "SECOND", "b", "hello"], $this->dogStatsSpy->increments[0]['stats']);
    }

    #[Test]
    #[DataProviderExternal(EnumProvider::class, 'differentEnumTypesAndExpectedStringDataProvider')]
    public function decrement_convertsEnumToString(UnitEnum $case, string $converted): void
    {
        // When
        $this->adapter->decrement($case, 100);

        // Then
        self::assertSame($converted, $this->dogStatsSpy->decrements[0]['stats']);
    }

    #[Test]
    public function decrement_convertsArrayWithEnumsToArrayOfStrings(): void
    {
        // When
        $this->adapter->decrement([StringBackedEnum::B, PlainUnitEnum::SECOND, IntBackedEnum::TWO, "hello"]);

        // Then
        self::assertEqualsCanonicalizing(["b", "SECOND", "2", "hello"], $this->dogStatsSpy->decrements[0]['stats']);
    }
}

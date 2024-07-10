<?php

namespace Cosmastech\StatsDClientAdapter\Tests\Clients\Datadog;

use Cosmastech\PsrLoggerSpy\LogFactory;
use Cosmastech\PsrLoggerSpy\LoggerSpy;
use Cosmastech\StatsDClientAdapter\Clients\Datadog\DatadogLoggingClient;
use Cosmastech\StatsDClientAdapter\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LogLevel;

#[CoversClass(DatadogLoggingClient::class)]
class DatadogLoggingClientTest extends BaseTestCase
{
    private LoggerSpy $loggerSpy;

    public function setUp(): void
    {
        parent::setUp();

        $this->loggerSpy = new LoggerSpy(new LogFactory());
    }

    #[Test]
    #[DataProvider("logLevelsDataProvider")]
    public function respectsLogLevel(string $logLevel): void
    {
        // Given
        $datadogLoggingClient = new DatadogLoggingClient($this->loggerSpy, logLevel: $logLevel);

        // When
        $datadogLoggingClient->increment("some-stat");

        // Then
        $logs = $this->loggerSpy->getLogs();

        self::assertCount(1, $logs);
        self::assertEquals($logLevel, $logs[0]->getLevel()->value);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function logLevelsDataProvider(): array
    {
        return [
            "debug" => [LogLevel::DEBUG],
            "info" => [LogLevel::INFO],
            "notice" => [LogLevel::NOTICE],
            "warning" => [LogLevel::WARNING],
            "error" => [LogLevel::ERROR],
            "critical" => [LogLevel::CRITICAL],
            "alert" => [LogLevel::ALERT],
            "emergency" => [LogLevel::EMERGENCY],
        ];
    }
}

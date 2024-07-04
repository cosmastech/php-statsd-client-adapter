<?php

namespace Cosmastech\StatsDClient\Tests\Datadog;

use Cosmastech\PsrLoggerSpy\LogFactory;
use Cosmastech\PsrLoggerSpy\LoggerSpy;
use Cosmastech\StatsDClient\Datadog\DatadogLoggingClient;
use Cosmastech\StatsDClient\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\LogLevel;

class DatadogLoggingClientTest extends BaseTestCase
{
    private LoggerSpy $loggerSpy;

    public function setUp(): void
    {
        $this->loggerSpy = new LoggerSpy(new LogFactory());
    }

    #[Test]
    #[DataProvider("logLevelsDataProvider")]
    public function respectsLogLevel(string $logLevel)
    {
        // Given
        $datadogLoggingClient = new DatadogLoggingClient($this->loggerSpy, $logLevel);

        // When
        $datadogLoggingClient->increment("some-stat");

        // Then
        $logs = $this->loggerSpy->getLogs();

        self::assertCount(1, $logs);
        self::assertEquals($logLevel, $logs[0]->getLevel()->value);
    }

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

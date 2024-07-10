<?php

namespace Cosmastech\StatsDClientAdapter\Clients\Datadog;

use DataDog\DogStatsd;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class DatadogLoggingClient extends DogStatsd
{
    protected readonly LoggerInterface $logger;

    protected readonly string $logLevel;

    /**
     * @param  LoggerInterface  $logger
     * @param  array<string, mixed>  $datadogConfig
     * @param  string  $logLevel
     */
    public function __construct(
        LoggerInterface $logger,
        array $datadogConfig = [],
        string $logLevel = LogLevel::DEBUG,
    ) {
        parent::__construct($datadogConfig);

        $this->logger = $logger;
        $this->logLevel = $logLevel;
    }

    /**
     * Write message to log.
     *
     * @param mixed $message
     * @return void
     */
    public function flush($message)
    {
        $this->logger->log($this->logLevel, $message);
    }
}

<?php

require_once __DIR__ . "/../vendor/autoload.php";

// See instantiation parameters: https://docs.datadoghq.com/developers/dogstatsd/?code-lang=php&tab=hostagent#client-instantiation-parameters

// You will need Monolog installed to run this example.

$logger = new \Monolog\Logger('safe_datadog');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/log_datadog.txt', \Monolog\Level::Debug));

$datadog = new \Cosmastech\StatsDClientAdapter\Clients\Datadog\ExceptionCatchingDatadogClient(
    [],
    static fn (\Throwable $t) => $logger->error('Exception: ' . $t->getMessage())
);

$adapter = new \Cosmastech\StatsDClientAdapter\Adapters\Datadog\DatadogStatsDClientAdapter($datadog);

$adapter->increment("logins", 1, ["type" => "successful"], 1); // assume that this fails

// You should see a file named log_datadog.txt in this directory which will have stats
// ex: [2024-07-08T23:59:18.880180+00:00] safe_datadog.ERROR: Exception: ErrorException: socket_sendto(): Unable to write to socket [111]: Connection refused in /var/www/vendor/datadog/php-datadogstatsd/src/DogStatsd.php:502

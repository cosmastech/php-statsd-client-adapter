<?php

require_once __DIR__ . "/../vendor/autoload.php";

// See instantiation parameters: https://docs.datadoghq.com/developers/dogstatsd/?code-lang=php&tab=hostagent#client-instantiation-parameters

// You will need Monolog installed to run this example.

$logger = new \Monolog\Logger('log_datadog');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/log_datadog.txt', \Monolog\Level::Debug));

$datadog = new \Cosmastech\StatsDClientAdapter\Clients\Datadog\DatadogLoggingClient($logger);

$adapter = new \Cosmastech\StatsDClientAdapter\Adapters\Datadog\DatadogStatsDClientAdapter($datadog);

$adapter->increment("logins", 1, ["type" => "successful"], 1);

// You should see a file named log_datadog.txt in this directory which will have stats
// ex: [2024-07-08T23:59:18.880180+00:00] log_datadog.DEBUG: logins:1|c|#type:successful [] []

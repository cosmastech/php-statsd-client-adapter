<?php

require_once __DIR__ . "/../vendor/autoload.php";

// This requires that the league/statsd package is installed for your project.

$adapter = \Cosmastech\StatsDClientAdapter\Adapters\League\LeagueStatsDClientAdapter::fromConfig([
    // See configuration options at https://github.com/thephpleague/statsd?tab=readme-ov-file#configuring
    'host' => '127.0.0.1',
    'port' => 8125,
    'namespace' => 'example',
]);

$adapter->gauge("my-stat", 1.1);

// Confirm in your statsd daemon that the gauge was logged

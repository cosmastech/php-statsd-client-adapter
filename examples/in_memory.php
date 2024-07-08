<?php

require_once __DIR__ . "/../vendor/autoload.php";

function timeInMilliseconds()
{
    return time() * 1000;
}

function makeApiRequest()
{
    sleep(1);
}

$inMemoryAdapter = new \Cosmastech\StatsDClientAdapter\Adapters\InMemory\InMemoryClientAdapter();

$inMemoryAdapter->setDefaultTags(["app_version" => "2.83.0"]); // Set this for tags you want included in all stats.

$startTimeInMs = timeInMilliseconds();
makeApiRequest();

$inMemoryAdapter->timing("api-response", timeInMilliseconds() - $startTimeInMs, 1.0, ["source" => "github"]);

/** @var \Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryStatsRecord $stats */
$stats = $inMemoryAdapter->getStats();

var_dump($stats->timing[0]);
/*
object(Cosmastech\StatsDClientAdapter\Adapters\InMemory\Models\InMemoryTimingRecord)#7 (5) {
  ["stat"]=>
  string(12) "api-response"
  ["durationMilliseconds"]=>
  float(2000)
  ["sampleRate"]=>
  float(1)
  ["tags"]=>
  array(2) {
    ["app_version"]=>
    string(6) "2.83.0"
    ["source"]=>
    string(6) "github"
  }
  ["recordedAt"]=>
  object(DateTimeImmutable)#8 (3) {
    ["date"]=>
    string(26) "2024-07-08 22:25:53.080522"
    ["timezone_type"]=>
    int(3)
    ["timezone"]=>
    string(3) "UTC"
  }
}
*/

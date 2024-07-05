<?php

namespace Cosmastech\StatsDClientAdapter\Utility\SampleRateDecider\Contracts;

interface SampleRateSendDecider
{
    public function decide(float $sampleRate): bool;
}

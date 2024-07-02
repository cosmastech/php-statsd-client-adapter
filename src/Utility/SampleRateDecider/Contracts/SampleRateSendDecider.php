<?php

namespace Cosmastech\StatsDClient\Utility\SampleRateDecider\Contracts;

interface SampleRateSendDecider
{
    public function decide(float $sampleRate): bool;
}

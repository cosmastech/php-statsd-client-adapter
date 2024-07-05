<?php

namespace Cosmastech\StatsDClient\Utility\SampleRateDecider;

class AlwaysSendDecider implements Contracts\SampleRateSendDecider
{
    public function decide(float $sampleRate): bool
    {
        return true;
    }
}

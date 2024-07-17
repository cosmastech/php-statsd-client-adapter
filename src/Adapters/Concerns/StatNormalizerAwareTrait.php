<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Normalizers\Normalizer;

trait StatNormalizerAwareTrait {
    protected Normalizer $statNormalizer;

    public function setStatNormalizer(Normalizer $statNormalizer): void
    {
        $this->statNormalizer = $statNormalizer;
    }

    public function normalizeStat(string|\UnitEnum|array $toNormalize): string
    {

    }
}
<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Contracts;

use Cosmastech\StatsDClientAdapter\Normalizers\Normalizer;

interface TagNormalizerAware
{
    public function setTagNormalizer(Normalizer $tagNormalizer): void;
}

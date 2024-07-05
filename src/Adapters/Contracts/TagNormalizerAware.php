<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Contracts;

use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;

interface TagNormalizerAware
{
    public function setTagNormalizer(TagNormalizer $tagNormalizer): void;
}

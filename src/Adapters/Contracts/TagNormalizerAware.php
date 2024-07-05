<?php

namespace Cosmastech\StatsDClient\Adapters\Contracts;

use Cosmastech\StatsDClient\TagNormalizers\TagNormalizer;

interface TagNormalizerAware
{
    public function setTagNormalizer(TagNormalizer $tagNormalizer): void;
}

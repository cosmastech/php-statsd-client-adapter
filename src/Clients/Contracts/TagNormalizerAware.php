<?php

namespace Cosmastech\StatsDClient\Clients\Contracts;

use Cosmastech\StatsDClient\TagNormalizers\TagNormalizer;

interface TagNormalizerAware
{
    public function setTagNormalizer(TagNormalizer $tagNormalizer): void;
}
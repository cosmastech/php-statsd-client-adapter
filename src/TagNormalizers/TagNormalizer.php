<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

interface TagNormalizer
{
    public function normalize(array $tags): array;
}

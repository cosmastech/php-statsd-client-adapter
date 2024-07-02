<?php

namespace Cosmastech\StatsDClient\TagNormalizers;

interface TagNormalizer
{
    public function normalize(array $tags): array;
}

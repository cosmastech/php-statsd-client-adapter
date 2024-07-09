<?php

namespace Cosmastech\StatsDClientAdapter\TagNormalizers;

interface TagNormalizer
{
    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    public function normalize(array $tags): array;
}

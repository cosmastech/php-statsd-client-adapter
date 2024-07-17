<?php

namespace Cosmastech\StatsDClientAdapter\Normalizers;

interface Normalizer
{
    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    public function normalize(array $tags): array;
}

<?php

namespace Cosmastech\StatsDClient\Clients\Concerns;

use Cosmastech\StatsDClient\TagNormalizers\TagNormalizer;

trait TagNormalizerAwareTrait
{
    protected TagNormalizer $tagNormalizer;

    public function setTagNormalizer(TagNormalizer $tagNormalizer): void
    {
        $this->tagNormalizer = $tagNormalizer;
    }

    protected function normalizeTags(array $tags): array
    {
        return $this->tagNormalizer->normalize($tags);
    }
}
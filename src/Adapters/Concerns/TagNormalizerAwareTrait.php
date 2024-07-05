<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\TagNormalizers\TagNormalizer;

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

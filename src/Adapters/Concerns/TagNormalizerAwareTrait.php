<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

use Cosmastech\StatsDClientAdapter\Normalizers\Normalizer;

trait TagNormalizerAwareTrait
{
    protected Normalizer $tagNormalizer;

    public function setTagNormalizer(Normalizer $tagNormalizer): void
    {
        $this->tagNormalizer = $tagNormalizer;
    }

    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    protected function normalizeTags(array $tags): array
    {
        return $this->tagNormalizer->normalize($tags);
    }
}

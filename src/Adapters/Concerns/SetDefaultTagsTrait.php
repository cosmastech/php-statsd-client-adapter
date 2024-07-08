<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

trait SetDefaultTagsTrait
{
    protected array $defaultTags = [];

    /**
     * @inheritDoc
     */
    public function setDefaultTags(array $defaultTags = []): void
    {
        $this->defaultTags = $defaultTags;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultTags(): array
    {
        return $this->defaultTags;
    }

    /**
     * @param  array<string, mixed>  $tags
     * @return array<string, mixed>
     */
    protected function mergeTags(array $tags): array
    {
        return array_merge($this->defaultTags, $tags);
    }
}

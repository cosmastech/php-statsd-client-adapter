<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

trait WithDefaultTagsTrait
{
    protected array $defaultTags = [];

    /**
     * @inheritDoc
     */
    public function getDefaultTags(): array
    {
        return $this->defaultTags;
    }

    /**
     * @param  array<string, mixed>  $tags
     * @return void
     */
    protected function withDefaultTags(array $defaultTags = []): void
    {
        $this->defaultTags = $defaultTags;
    }

    /**
     * @param  array<string, mixed>  $tags
     * @return array<string, mixed>
     */
    protected function mergeTags(array $tags): array
    {
        return array_merge($this->getDefaultTags(), $tags);
    }
}

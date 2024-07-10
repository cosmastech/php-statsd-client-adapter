<?php

namespace Cosmastech\StatsDClientAdapter\Adapters\Concerns;

trait HasDefaultTagsTrait
{
    /**
     * @var array<mixed, mixed>
     */
    protected array $defaultTags = [];

    /**
     * @inheritDoc
     */
    public function getDefaultTags(): array
    {
        return $this->defaultTags;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultTags(array $defaultTags = []): void
    {
        $this->defaultTags = $defaultTags;
    }

    /**
     * @param  array<mixed, mixed>  $tags
     * @return array<mixed, mixed>
     */
    protected function mergeWithDefaultTags(array $tags): array
    {
        return array_merge($this->getDefaultTags(), $tags);
    }
}

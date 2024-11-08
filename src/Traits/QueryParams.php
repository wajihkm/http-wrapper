<?php

namespace Directions\HttpWrapper\Traits;

trait QueryParams
{
    private array $queryParams;

    public function setQueryParams(array $payload): void
    {
        $this->queryParams = $payload;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    protected function hasQueryParams(): bool
    {
        return ! empty($this->getQueryParams());
    }
}

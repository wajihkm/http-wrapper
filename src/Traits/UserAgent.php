<?php

namespace Simplify\HttpWrapper\Traits;

trait UserAgent
{
    protected ?string $user_agent = null;

    protected function setUserAgent(string $user_agent): void
    {
        $this->user_agent = $user_agent;
    }
}

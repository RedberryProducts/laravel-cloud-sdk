<?php

namespace Redberry\LaravelCloudSdk\Exceptions;

class RateLimitException extends CloudException
{
    public function retryAfter(): ?int
    {
        $value = $this->getResponse()->header('Retry-After');

        return $value !== null ? (int) $value : null;
    }
}

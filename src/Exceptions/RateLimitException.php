<?php

namespace Redberry\LaravelCloudSdk\Exceptions;

use Saloon\Exceptions\Request\Statuses\TooManyRequestsException;

class RateLimitException extends TooManyRequestsException
{
    public function retryAfter(): ?int
    {
        $value = $this->getResponse()->header('Retry-After');

        return $value !== null ? (int) $value : null;
    }
}

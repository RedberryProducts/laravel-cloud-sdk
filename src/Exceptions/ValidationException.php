<?php

namespace Redberry\LaravelCloudSdk\Exceptions;

class ValidationException extends CloudException
{
    public function errors(): array
    {
        $body = json_decode($this->getResponse()->body(), true);

        return $body['errors'] ?? [];
    }
}

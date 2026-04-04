<?php

namespace Redberry\LaravelCloudSdk\Exceptions;

use Saloon\Exceptions\Request\Statuses\UnprocessableEntityException;

class ValidationException extends UnprocessableEntityException
{
    public function errors(): array
    {
        $body = json_decode($this->getResponse()->body(), true);

        return $body['errors'] ?? [];
    }
}

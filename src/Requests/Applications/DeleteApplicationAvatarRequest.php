<?php

namespace Redberry\LaravelCloudSdk\Requests\Applications;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteApplicationAvatarRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/applications/{$this->applicationId}/avatar";
    }
}

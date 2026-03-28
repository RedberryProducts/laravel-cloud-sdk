<?php

namespace Redberry\LaravelCloudSdk\Requests\Environments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteEnvironmentRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $environmentId) {}

    public function resolveEndpoint(): string
    {
        return "/environments/{$this->environmentId}";
    }
}

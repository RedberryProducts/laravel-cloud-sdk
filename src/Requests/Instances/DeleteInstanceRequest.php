<?php

namespace Redberry\LaravelCloudSdk\Requests\Instances;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteInstanceRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $instanceId) {}

    public function resolveEndpoint(): string
    {
        return "/instances/{$this->instanceId}";
    }
}

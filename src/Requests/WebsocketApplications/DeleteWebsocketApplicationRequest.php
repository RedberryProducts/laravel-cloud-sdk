<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketApplications;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteWebsocketApplicationRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $applicationId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-applications/{$this->applicationId}";
    }
}

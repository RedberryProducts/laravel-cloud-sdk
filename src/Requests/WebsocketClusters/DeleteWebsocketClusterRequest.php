<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteWebsocketClusterRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}";
    }
}

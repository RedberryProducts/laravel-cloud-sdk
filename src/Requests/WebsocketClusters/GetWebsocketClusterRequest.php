<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetWebsocketClusterRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/websocket-servers/{$this->clusterId}";
    }

    public function createDtoFromResponse(Response $response): WebsocketClusterData
    {
        $data = $response->json('data');

        return WebsocketClusterData::fromResponse($data['attributes'], $data['id']);
    }
}

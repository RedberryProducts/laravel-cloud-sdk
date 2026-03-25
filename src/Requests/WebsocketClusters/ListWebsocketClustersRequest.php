<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListWebsocketClustersRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/websocket-servers';
    }

    /**
     * @return Collection<int, WebsocketClusterData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => WebsocketClusterData::fromResponse($item['attributes'], $item['id']));
    }
}

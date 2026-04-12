<?php

namespace Redberry\LaravelCloudSdk\Requests\WebsocketClusters;

use Redberry\LaravelCloudSdk\Data\WebsocketClusters\WebsocketClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListWebsocketClustersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/websocket-servers';
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'applications'];
    }

    /**
     * @return WebsocketClusterData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            WebsocketClusterData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListDatabaseClustersRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/databases/clusters';
    }

    /**
     * @return Collection<int, DatabaseClusterData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => DatabaseClusterData::fromResponse($item['attributes'], $item['id']));
    }
}

<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDatabaseClustersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/databases/clusters';
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'databases'];
    }

    /**
     * @return DatabaseClusterData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            DatabaseClusterData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

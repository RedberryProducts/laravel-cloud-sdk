<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseClusterData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseClusterRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $databaseClusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'databases'];
    }

    public function createDtoFromResponse(Response $response): DatabaseClusterData
    {
        return JsonApiHydrator::hydrateOne(
            DatabaseClusterData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDatabasesRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $clusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'database,environments'];
    }

    /**
     * @return DatabaseData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return JsonApiHydrator::hydrateMany(
            DatabaseData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

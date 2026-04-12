<?php

namespace Redberry\LaravelCloudSdk\Requests\Databases;

use Redberry\LaravelCloudSdk\Data\Databases\DatabaseData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private string $clusterId,
        private string $databaseId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->clusterId}/databases/{$this->databaseId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'database,environments'];
    }

    public function createDtoFromResponse(Response $response): DatabaseData
    {
        return JsonApiHydrator::hydrateOne(
            DatabaseData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

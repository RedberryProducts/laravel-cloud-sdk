<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Redberry\LaravelCloudSdk\Support\JsonApiHydrator;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetDatabaseSnapshotRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $snapshotId) {}

    public function resolveEndpoint(): string
    {
        return "/database-snapshots/{$this->snapshotId}";
    }

    protected function defaultQuery(): array
    {
        return ['include' => 'database'];
    }

    public function createDtoFromResponse(Response $response): DatabaseSnapshotData
    {
        return JsonApiHydrator::hydrateOne(
            DatabaseSnapshotData::class,
            $response->json('data'),
            $response->json('included') ?? [],
        );
    }
}

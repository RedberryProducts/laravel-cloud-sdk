<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class ListDatabaseSnapshotsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(private string $databaseClusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}/snapshots";
    }

    /**
     * @return DatabaseSnapshotData[]
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            fn (array $item) => DatabaseSnapshotData::fromResponse($item['attributes'], $item['id']),
            $response->json('data')
        );
    }
}

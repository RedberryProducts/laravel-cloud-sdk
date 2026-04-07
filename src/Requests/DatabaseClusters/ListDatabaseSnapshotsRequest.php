<?php

namespace Redberry\LaravelCloudSdk\Requests\DatabaseClusters;

use Illuminate\Support\Collection;
use Redberry\LaravelCloudSdk\Data\DatabaseClusters\DatabaseSnapshotData;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ListDatabaseSnapshotsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private string $databaseClusterId) {}

    public function resolveEndpoint(): string
    {
        return "/databases/clusters/{$this->databaseClusterId}/snapshots";
    }

    /**
     * @return Collection<int, DatabaseSnapshotData>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect($response->json('data'))
            ->map(fn (array $item) => DatabaseSnapshotData::fromResponse($item['attributes'], $item['id']));
    }
}
